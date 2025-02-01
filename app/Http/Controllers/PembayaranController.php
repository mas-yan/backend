<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create payment plan (POST /api/payments)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'penjualan_id' => 'required|exists:penjualans,id',
                'jumlah_installment' => 'required|integer|min:1',
            ]);

            $this->paymentService->createPaymentPlan($validated);
            return response()->json(['message' => 'Payment plan created successfully'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get all payments (GET /api/payments)
     */
    public function index(): JsonResponse
    {
        $payments = $this->paymentService->getAllPayments();
        return response()->json($payments);
    }

    /**
     * Get single payment (GET /api/payments/{id})
     */
    public function show($id): JsonResponse
    {
        try {
            $payment = $this->paymentService->getPaymentById($id);
            return response()->json($payment);
        } catch (Exception $e) {
            return response()->json(['error' => 'Payment not found'], 404);
        }
    }

    /**
     * Update payment (PUT /api/payments/{id})
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'sometimes|in:paid,unpaid'
            ]);

            $payment = $this->paymentService->updatePayment($id, $validated);
            return response()->json($payment);
        } catch (Exception $e) {
            return response()->json(['error' => 'Payment update failed'], 400);
        }
    }

    /**
     * Get payments by sale (GET /api/sales/{saleId}/payments)
     */
    public function getBySale($saleId): JsonResponse
    {
        try {
            $payments = $this->paymentService->getPaymentsBySaleId($saleId);
            return response()->json($payments);
        } catch (Exception $e) {
            return response()->json(['error' => 'Invalid sale ID'], 400);
        }
    }
}
