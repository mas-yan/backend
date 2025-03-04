<?php

namespace App\Services;

use App\Interfaces\Repositories\PaymentRepositoryInterface;
use App\Models\Pembayaran;
use App\Models\Penjualan;
use Exception;

class PaymentService
{
    private $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Create payment plan for a sale
     */
    public function createPaymentPlan(array $data): void
    {
        $penjualan = Penjualan::where('transaction_number', $data['transaction_number'])->first();

        // Check existing payments
        $existingPayments = $this->paymentRepository->getPaymentsBySale($penjualan->id);
        if ($existingPayments->count() > 0) {
            throw new Exception('Payment plan already exists for this sale');
        }

        // Calculate installment amount
        $installmentAmount = $penjualan->grand_total / $data['jumlah_installment'];

        // Prepare payment data
        $payments = [];
        for ($i = 0; $i < $data['jumlah_installment']; $i++) {
            $dueDate = now()->addMonths($i + 1);
            $payments[] = [
                'penjualan_id' => $penjualan->id,
                'installment_ke' => $i + 1,
                'due_date' => $dueDate,
                'amount' => $installmentAmount,
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        $this->paymentRepository->createPaymentPlan($payments);
    }

    /**
     * Get all payments
     */
    public function getAllPayments()
    {
        return $this->paymentRepository->getPayments();
    }

    /**
     * Get single payment by ID
     */
    public function getPaymentById($id)
    {
        return $this->paymentRepository->getPayment($id);
    }

    /**
     * Update payment status
     */
    public function updatePayment($id, array $data)
    {
        $payment = $this->paymentRepository->getPayment($id);

        if (isset($data['status']) && $data['status'] === 'paid') {
            $data['payment_date'] = now();
        }

        return $this->paymentRepository->updatePayment($id, $data);
    }

    /**
     * Get payments by sale ID
     */
    public function getPaymentsBySaleId($saleId)
    {
        return $this->paymentRepository->getPaymentsBySale($saleId);
    }
}
