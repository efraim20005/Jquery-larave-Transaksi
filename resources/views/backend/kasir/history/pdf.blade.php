<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 5px; text-align: center; }
        h3 { text-align: center; }
    </style>
</head>
<body>

<h3>STRUK PEMBELIAN</h3>

<p>
    <strong>ID Transaksi:</strong> {{ $transaction->id }} <br>
    <strong>Tanggal:</strong> {{ $transaction->created_at }} <br>
    <strong>Kasir:</strong> {{ $transaction->user->name }}
</p>

<table>
    <thead>
    <tr>
        <th>Produk</th>
        <th>Qty</th>
        <th>Harga</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($transaction->details as $d)
        <tr>
            <td>{{ $d->product->name }}</td>
            <td>{{ $d->qty }}</td>
            <td>{{ number_format($d->price) }}</td>
            <td>{{ number_format($d->subtotal) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h4 style="text-align:right;">
    Total: Rp {{ number_format($transaction->total) }}
</h4>

<p style="text-align:center; margin-top:20px;">
    Terima kasih atas pembelian Anda.
</p>

</body>
</html>
