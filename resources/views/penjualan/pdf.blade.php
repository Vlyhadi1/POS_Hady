<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Nota Transaksi #{{ $penjualan->id }}</title>
    <style>
        @page { margin: 24px; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 11px; }
        .header { border-bottom: 2px solid #15803d; padding-bottom: 10px; margin-bottom: 14px; }
        .logo { max-width: 58px; max-height: 58px; float: right; }
        h1 { color: #14532d; font-size: 20px; margin: 0 0 4px; }
        .muted { color: #64748b; }
        .meta { width: 100%; margin-bottom: 14px; }
        .meta td { padding: 3px 0; }
        table.items { width: 100%; border-collapse: collapse; }
        .items th { background: #14532d; color: white; text-align: left; }
        .items th, .items td { padding: 7px; border-bottom: 1px solid #dbe2ea; }
        .right { text-align: right; }
        .totals { width: 100%; margin-top: 12px; }
        .totals td { padding: 4px; }
        .grand { color: #14532d; font-size: 15px; font-weight: bold; border-top: 2px solid #14532d; }
        .footer { text-align: center; margin-top: 24px; color: #64748b; }
    </style>
</head>
<body>
    <div class="header">
        @if($logoDataUri)
            <img class="logo" src="{{ $logoDataUri }}">
        @endif
        <h1>{{ $setting->store_name }}</h1>
        <div class="muted">{{ $setting->store_address ?: 'Alamat toko belum diatur' }}</div>
        <div class="muted">{{ $setting->store_phone ?: '' }} {{ $setting->store_email ? '• '.$setting->store_email : '' }}</div>
    </div>

    <table class="meta">
        <tr><td>Nomor Transaksi</td><td class="right">#{{ $penjualan->id }}</td></tr>
        <tr><td>Tanggal</td><td class="right">{{ $penjualan->created_at?->format('d/m/Y H:i') }}</td></tr>
        <tr><td>Kasir</td><td class="right">{{ $penjualan->user?->name ?? '-' }}</td></tr>
    </table>

    <table class="items">
        <thead><tr><th>Produk</th><th class="right">Harga</th><th class="right">Qty</th><th class="right">Subtotal</th></tr></thead>
        <tbody>
            @foreach($penjualan->itemPenjualan as $item)
                <tr>
                    <td>{{ $item->produk?->nama ?? 'Produk Terhapus' }}</td>
                    <td class="right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="right">{{ $item->kuantitas }}</td>
                    <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal</td><td class="right">Rp {{ number_format($penjualan->itemPenjualan->sum('subtotal'), 0, ',', '.') }}</td></tr>
        <tr><td>Diskon</td><td class="right">- Rp {{ number_format($penjualan->diskon ?? 0, 0, ',', '.') }}</td></tr>
        <tr class="grand"><td>Total</td><td class="right">Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</td></tr>
        @if($penjualan->metode_pembayaran === 'CASH')
            <tr><td>Bayar</td><td class="right">Rp {{ number_format($penjualan->uang_dibayar ?? 0, 0, ',', '.') }}</td></tr>
            <tr><td>Kembalian</td><td class="right">Rp {{ number_format($penjualan->kembalian ?? 0, 0, ',', '.') }}</td></tr>
        @endif
    </table>

    <div class="footer">{{ $setting->receipt_footer ?: 'Terima kasih telah berbelanja.' }}</div>
</body>
</html>
