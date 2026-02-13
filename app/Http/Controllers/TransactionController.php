<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('kasir.transactions.index', compact('products'));
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'total' => $request->total
            ]);

            foreach ($request->products as $item) {

                $product = Product::findOrFail($item['id']);

                // Kurangi stok
                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok tidak cukup");
                }

                $product->decrement('stock', $item['qty']);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['qty'],
                ]);
            }
        });

        return response()->json(['success' => true]);
    }
    public function history(Request $request)
    {
        $query = Transaction::with('user')->latest();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $transactions = $query->get();

        $totalAll = $transactions->sum('total');

        return view('kasir.history.index', compact('transactions', 'totalAll'));
    }


    public function show($id)
    {
        $transaction = Transaction::with('details.product', 'user')
            ->findOrFail($id);

        return view('kasir.history.show', compact('transaction'));
    }

    public function printPdf($id)
    {
        $transaction = Transaction::with('details.product', 'user')
            ->findOrFail($id);

        $pdf = Pdf::loadView('kasir.history.pdf', compact('transaction'));

        return $pdf->download('struk-transaksi-'.$transaction->id.'.pdf');
    }


}
