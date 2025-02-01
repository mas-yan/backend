<?php

namespace App\Repositories;

use App\Interfaces\Repositories\CommissionRepositoryInterface;
use App\Models\Penjualan;

class CommissionRepository implements CommissionRepositoryInterface
{
    public function getGroupedSalesData()
    {
        return Penjualan::join('marketings', 'penjualans.marketing_id', '=', 'marketings.id')
            ->selectRaw('
                marketings.name as marketing_name,
                YEAR(penjualans.date) as year,
                MONTH(penjualans.date) as month,
                SUM(penjualans.total_balance) as omzet
            ')
            ->groupBy('marketing_id', 'year', 'month', 'marketings.name')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('marketing_id')
            ->get();
    }
}
