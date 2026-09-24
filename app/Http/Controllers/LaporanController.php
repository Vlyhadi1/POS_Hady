<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\User;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Setting;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $this->validateDateFilters($request);
        [$from, $to] = $this->dateRange($request);
        $user = $request->user();
        $role = strtolower(optional($user->role)->name ?? '');

        $query = $this->baseQuery($request, $from, $to, $user, $role);

        $ringkasan = [
            'transaksi' => (clone $query)->count(),
            'penjualan' => (clone $query)->sum('total_pembayaran'),
            'diskon' => (clone $query)->sum('diskon'),
            'cash' => (clone $query)->where('metode_pembayaran', 'CASH')->sum('total_pembayaran'),
            'non_tunai' => (clone $query)->whereIn('metode_pembayaran', ['QRIS', 'TRANSFER'])->sum('total_pembayaran'),
        ];

        $transactions = (clone $query)
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        $topProducts = DB::table('item_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'item_penjualan.penjualan_id')
            ->join('produk', 'produk.id', '=', 'item_penjualan.produk_id')
            ->where('penjualan.status', 'COMPLETED')
            ->whereBetween('penjualan.created_at', [
                $from->copy()->startOfDay(),
                $to->copy()->endOfDay(),
            ])
            ->when($role === 'kasir', fn ($q) => $q->where('penjualan.user_id', $user->id))
            ->when($role === 'admin' && $request->filled('kasir'), fn ($q) => $q->where('penjualan.user_id', $request->integer('kasir')))
            ->when($request->filled('metode') && in_array(strtoupper($request->input('metode')), ['CASH', 'QRIS', 'TRANSFER'], true),
                fn ($q) => $q->where('penjualan.metode_pembayaran', strtoupper($request->input('metode'))))
            ->groupBy('produk.id', 'produk.nama')
            ->select(
                'produk.nama',
                DB::raw('SUM(item_penjualan.kuantitas) AS qty'),
                DB::raw('SUM(item_penjualan.subtotal) AS total')
            )
            ->orderByDesc('qty')
            ->limit(10)
            ->get();

        $cashiers = $role === 'admin'
            ? User::whereHas('role', fn ($q) => $q->whereRaw('LOWER(name) = ?', ['kasir']))
                ->orderBy('name')
                ->get()
            : collect();

        $dailyRows = (clone $query)
            ->selectRaw('DATE(created_at) AS tanggal, SUM(total_pembayaran) AS total')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('tanggal')
            ->get();
        $dailyLabels = $dailyRows->pluck('tanggal')->map(fn ($date) => Carbon::parse($date)->format('d/m'))->values();
        $dailyValues = $dailyRows->pluck('total')->map(fn ($total) => (int) $total)->values();

        return view('laporan.index', compact(
            'transactions',
            'ringkasan',
            'topProducts',
            'from',
            'to',
            'cashiers',
            'dailyLabels',
            'dailyValues'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $this->validateDateFilters($request);
        [$from, $to] = $this->dateRange($request);
        $user = $request->user();
        $role = strtolower(optional($user->role)->name ?? '');

        $query = $this->baseQuery($request, $from, $to, $user, $role);

        $rows = $query->latest('created_at')->get();

        $filename = 'laporan-penjualan-' .
            $from->format('Y-m-d') . '-sampai-' .
            $to->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'Tanggal',
                'Kasir',
                'Metode Pembayaran',
                'Diskon',
                'Total',
                'Uang Dibayar',
                'Kembalian',
            ]);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->created_at?->format('Y-m-d H:i:s'),
                    $row->user?->name ?? '-',
                    $row->metode_pembayaran,
                    $row->diskon ?? 0,
                    $row->total_pembayaran,
                    $row->uang_dibayar ?? 0,
                    $row->kembalian ?? 0,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function pdf(Request $request)
    {
        $this->validateDateFilters($request);
        [$from, $to] = $this->dateRange($request);
        $user = $request->user();
        $role = strtolower(optional($user->role)->name ?? '');
        $query = $this->baseQuery($request, $from, $to, $user, $role);
        $transactions = (clone $query)->latest('created_at')->get();

        $summary = [
            'transactions' => $transactions->count(),
            'discount' => (int) $transactions->sum('diskon'),
            'sales' => (int) $transactions->sum('total_pembayaran'),
        ];
        $setting = Setting::current();
        $logoPath = $setting->store_logo ? storage_path('app/public/' . $setting->store_logo) : null;
        $logoDataUri = is_file($logoPath ?? '')
            ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $pdf = Pdf::loadView('laporan.pdf', compact('transactions', 'summary', 'from', 'to', 'setting', 'logoDataUri'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-' . $from->format('Y-m-d') . '-sampai-' . $to->format('Y-m-d') . '.pdf');
    }

    public function excel(Request $request)
    {
        $this->validateDateFilters($request);
        [$from, $to] = $this->dateRange($request);
        $user = $request->user();
        $role = strtolower(optional($user->role)->name ?? '');
        $rows = $this->baseQuery($request, $from, $to, $user, $role)
            ->latest('created_at')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penjualan');
        $sheet->fromArray([
            ['ID', 'Tanggal', 'Kasir', 'Metode Pembayaran', 'Diskon', 'Total', 'Uang Dibayar', 'Kembalian'],
        ], null, 'A1');

        $data = $rows->map(fn ($row) => [
            $row->id,
            $row->created_at?->format('Y-m-d H:i:s'),
            $row->user?->name ?? '-',
            $row->metode_pembayaran,
            (int) ($row->diskon ?? 0),
            (int) $row->total_pembayaran,
            (int) ($row->uang_dibayar ?? 0),
            (int) ($row->kembalian ?? 0),
        ])->all();

        if ($data !== []) {
            $sheet->fromArray($data, null, 'A2');
        }

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'laporan-penjualan-' . $from->format('Y-m-d') . '-sampai-' . $to->format('Y-m-d') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            fn () => $writer->save('php://output'),
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    private function baseQuery(Request $request, Carbon $from, Carbon $to, $user, string $role)
    {
        return Penjualan::query()
            ->with('user')
            ->where('status', 'COMPLETED')
            ->whereBetween('created_at', [
                $from->copy()->startOfDay(),
                $to->copy()->endOfDay(),
            ])
            ->when($role === 'kasir', fn ($q) => $q->where('user_id', $user->id))
            ->when(
                $role === 'admin' && $request->filled('kasir'),
                fn ($q) => $q->where('user_id', $request->integer('kasir'))
            )
            ->when(
                $request->filled('metode') &&
                in_array(strtoupper($request->input('metode')), ['CASH', 'QRIS', 'TRANSFER'], true),
                fn ($q) => $q->where('metode_pembayaran', strtoupper($request->input('metode')))
            );
    }

    private function dateRange(Request $request): array
    {
        try {
            $from = $request->filled('from')
                ? Carbon::createFromFormat('Y-m-d', $request->input('from'))
                : Carbon::today();

            $to = $request->filled('to')
                ? Carbon::createFromFormat('Y-m-d', $request->input('to'))
                : Carbon::today();
        } catch (\Throwable $e) {
            $from = Carbon::today();
            $to = Carbon::today();
        }

        if ($from->gt($to)) {
            [$from, $to] = [$to, $from];
        }

        return [$from, $to];
    }

    private function validateDateFilters(Request $request): void
    {
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:to'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ], [
            'from.date_format' => 'Format tanggal mulai harus YYYY-MM-DD.',
            'to.date_format' => 'Format tanggal akhir harus YYYY-MM-DD.',
            'to.after_or_equal' => 'Tanggal akhir tidak boleh sebelum tanggal mulai.',
        ]);

        if ($request->filled('from') && $request->filled('to') && Carbon::parse($request->input('from'))->diffInDays(Carbon::parse($request->input('to'))) > 366) {
            abort(422, 'Rentang laporan maksimal 366 hari.');
        }
    }
}
