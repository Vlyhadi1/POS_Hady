<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        @page { margin: 28px; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 11px; }
        h1 { margin: 0 0 5px; color: #14532d; font-size: 22px; }
        .muted { color: #64748b; }
        .period { margin-bottom: 18px; }
        .summary { width: 100%; margin-bottom: 18px; }
        .summary td { width: 33.33%; padding: 10px; background: #f0fdf4; border: 1px solid #bbf7d0; }
        .summary-label { color: #64748b; font-size: 10px; }
        .summary-value { margin-top: 4px; color: #14532d; font-size: 15px; font-weight: bold; }
        table.transactions { width: 100%; border-collapse: collapse; }
        .transactions th { background: #14532d; color: white; text-align: left; }
        .transactions th, .transactions td { padding: 8px; border: 1px solid #dbe2ea; }
        .transactions .right { text-align: right; }
        .footer { margin-top: 18px; color: #64748b; font-size: 9px; }
    </style>
</head>
<body>
    @if($logoDataUri)
        <img src="{{ $logoDataUri }}" style="float:right;max-width:60px;max-height:60px">
    @endif
    <h1>Laporan Penjualan - {{ $setting->store_name }}</h1>
    <div class="muted">{{ $setting->store_address ?: '' }} {{ $setting->store_phone ? '• '.$setting->store_phone : '' }}</div>
    <div class="period muted">Periode: {{ $from->translatedFormat('d F Y') }} s/d {{ $to->translatedFormat('d F Y') }}</div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-label">Total Transaksi</div>
                <div class="summary-value">{{ number_format($summary['transactions'], 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Total Diskon</div>
                <div class="summary-value">Rp {{ number_format($summary['discount'], 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Total Penjualan</div>
                <div class="summary-value">Rp {{ number_format($summary['sales'], 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="transactions">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Metode</th>
                <th class="right">Diskon</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>#{{ $transaction->id }}</td>
                    <td>{{ $transaction->created_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $transaction->user?->name ?? '-' }}</td>
                    <td>{{ $transaction->metode_pembayaran }}</td>
                    <td class="right">Rp {{ number_format($transaction->diskon ?? 0, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($transaction->total_pembayaran, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;">Tidak ada transaksi pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dicetak pada {{ now()->translatedFormat('d F Y H:i') }} WIB</div>
</body>
</html>
