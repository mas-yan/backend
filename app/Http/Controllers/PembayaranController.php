<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'penjualan_id' => 'required|exists:penjualans,id',
            'jumlah_installment' => 'required|integer|min:1',
            'due_dates' => 'required|array'
        ]);

        $penjualan = Penjualan::findOrFail($request->penjualan_id);

        // Cek apakah sudah ada pembayaran untuk transaksi ini
        if (Pembayaran::where('penjualan_id', $request->penjualan_id)->exists()) {
            return response()->json(['message' => 'Pembayaran untuk transaksi ini sudah ada'], 400);
        }

        // Hitung jumlah per installment
        $installmentAmount = $penjualan->grand_total / $request->jumlah_installment;

        $payments = [];
        foreach ($request->due_dates as $index => $dueDate) {
            $payments[] = [
                'penjualan_id' => $request->penjualan_id,
                'installment_ke' => $index + 1,
                'due_date' => $dueDate,
                'amount' => $installmentAmount,
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        Pembayaran::insert($payments);

        return response()->json(['message' => 'Payment plan created successfully']);
    }

    public function index()
    {
        $payments = Pembayaran::with('penjualan')->get();
        return response()->json($payments);
    }

    public function show($id)
    {
        $payment = Pembayaran::with('penjualan')->findOrFail($id);
        return response()->json($payment);
    }

    public function update(Request $request, $id)
    {
        $payment = Pembayaran::findOrFail($id);

        $request->validate([
            'status' => 'sometimes|in:paid,unpaid'
        ]);

        if ($request->has('status') && $request->status === 'paid') {
            $payment->update([
                'payment_date' => now(),
                'status' => 'paid'
            ]);
        }

        return response()->json($payment);
    }

    public function getBySale($saleId)
    {
        $payments = Pembayaran::where('penjualan_id', $saleId)
            ->with('penjualan')
            ->get();

        return response()->json($payments);
    }
}
