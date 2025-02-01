<?php

namespace App\Interfaces\Repositories;

interface PaymentRepositoryInterface
{
    public function createPaymentPlan(array $payments);
    public function getPayments();
    public function getPayment($id);
    public function updatePayment($id, array $data);
    public function getPaymentsBySale($saleId);
}
