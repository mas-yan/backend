<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    private $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function index()
    {
        $result = $this->commissionService->getCommissions();
        return response()->json(['data' => $result]);
    }
}
