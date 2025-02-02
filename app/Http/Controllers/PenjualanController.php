<?php

namespace App\Http\Controllers;

use App\Services\CommissionService;

class PenjualanController extends Controller
{
    private $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function comission()
    {
        $result = $this->commissionService->getCommissions();
        return response()->json($result);
    }
    public function sale()
    {
        $result = $this->commissionService->getSales();
        return response()->json($result);
    }
}
