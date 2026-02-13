@extends('layouts.app')

@section('content')
    <h3>History Transaksi</h3>

    <form method="GET" class="row mb-3">
        <div class="col-md-3">
            <input type="date" name="start_date"
                   class="form-control"
                   value="{{ request('start_date') }}">
        </div>

        <div class="col-md-3">
            <input type="date" name="end_date"
                   class="form-control"
                   value="{{ request('end_date') }}">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                Filter
            </button>
        </div>

        <div class="col-md-2">
            <a href="{{ route('transactions.history') }}"
               class="btn btn-secondary w-100">
                Reset
            </a>
        </div>
    </form>


    <table class="table table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kasir</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $t)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $t->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ $t->user->name }}</td>
                <td>Rp {{ number_format($t->total) }}</td>
                <td>
                    <a href="{{ route('transactions.show', $t->id) }}"
                       class="btn btn-info btn-sm">
                        Detail
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        <h5>Total Transaksi:
            <span class="text-success">
            Rp {{ number_format($totalAll) }}
        </span>
        </h5>
    </div>

@endsection
