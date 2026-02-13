@extends('layouts.app')

@section('content')

    <h3>Detail Transaksi</h3>

    <p><strong>Tanggal:</strong> {{ $transaction->created_at }}</p>
    <p><strong>Kasir:</strong> {{ $transaction->user->name }}</p>




    <table class="table table-bordered">
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

    <h4>Total: Rp {{ number_format($transaction->total) }}</h4>

    <a href="{{ route('transactions.history') }}" class="btn btn-secondary">
        Kembali
    </a>

    <a href="{{ route('transactions.print', $transaction->id) }}"
       class="btn btn-success">
        Cetak PDF
    </a>

@endsection
