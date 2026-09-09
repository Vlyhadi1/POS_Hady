<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $user = $request->user();
        $role = strtolower(optional($user->role)->name ?? '');

        $query = $this->baseQuery($request, $from, $to, $user, $role);

        $ringkasan = [
            'transaksi' => (clone $query)->count(),
            'penjualan' => (clone $query)->sum('total_pembayaran'),
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

        return view('laporan.index', compact(
            'transactions',
            'ringkasan',
            'topProducts',
            'from',
            'to',
            'cashiers'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
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

            // BOM agar Excel membaca UTF-8 dengan benar.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'Tanggal',
                'Kasir',
                'Metode Pembayaran',
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
                    $row->total_pembayaran,
                    $row->uang_dibayar ?? 0,
                    $row->kembalian ?? 0,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
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
}
