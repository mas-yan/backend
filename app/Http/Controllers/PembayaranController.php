<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penjualan;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'penjualan_id' => 'required|exists:penjualans,id',
    //         'jumlah_installment' => 'required|integer|min:1',
    //         'due_dates' => 'required|array'
    //     ]);

    //     $penjualan = Penjualan::findOrFail($request->penjualan_id);

    //     // Cek apakah sudah ada pembayaran untuk transaksi ini
    //     if (Pembayaran::where('penjualan_id', $request->penjualan_id)->exists()) {
    //         return response()->json(['message' => 'Pembayaran untuk transaksi ini sudah ada'], 400);
    //     }

    //     // Hitung jumlah per installment
    //     $installmentAmount = $penjualan->grand_total / $request->jumlah_installment;

    //     $payments = [];
    //     foreach ($request->due_dates as $index => $dueDate) {
    //         $payments[] = [
    //             'penjualan_id' => $request->penjualan_id,
    //             'installment_ke' => $index + 1,
    //             'due_date' => $dueDate,
    //             'amount' => $installmentAmount,
    //             'status' => 'unpaid',
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ];
    //     }

    //     Pembayaran::insert($payments);

    //     return response()->json(['message' => 'Payment plan created successfully']);
    // }

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
                'due_dates' => 'required|array|size:' . $request->jumlah_installment
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
