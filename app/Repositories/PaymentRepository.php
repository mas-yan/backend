<?php

namespace App\Repositories;

use App\Interfaces\Repositories\PaymentRepositoryInterface;
use App\Models\Pembayaran;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function createPaymentPlan(array $payments)
    {
        return Pembayaran::insert($payments);
    }

    public function getPayments()
    {
        return Pembayaran::with('penjualan')->get();
    }

    public function getPayment($id)
    {
        return Pembayaran::with('penjualan')->findOrFail($id);
    }

    public function updatePayment($id, array $data)
    {
        $payment = Pembayaran::findOrFail($id);
        $payment->update($data);
        return $payment;
    }

    public function getPaymentsBySale($saleId)
    {
        return Pembayaran::where('penjualan_id', $saleId)
            ->with('penjualan')
            ->get();
    }
}
