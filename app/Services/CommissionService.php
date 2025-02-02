<?php

namespace App\Services;

use App\Interfaces\Repositories\CommissionRepositoryInterface;

class CommissionService
{
    private $commissionRepository;

    public function __construct(CommissionRepositoryInterface $commissionRepository)
    {
        $this->commissionRepository = $commissionRepository;
    }

    public function getSales()
    {
        $getSales =  $this->commissionRepository->getSales();

        return $getSales->map(function ($data) {

            return [
                'id' => $data->id,
                'transaction_number' => $data->transaction_number,
                'marketing' => $data->marketing->name,
                'date' => $data->date,
                'cargo_fee' => $data->cargo_fee,
                'total_balance' => $data->total_balance,
                'grand_total' => $data->grand_total,
            ];
        });
    }

    public function getCommissions()
    {
        $salesData = $this->commissionRepository->getGroupedSalesData();

        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $salesData->map(function ($data) use ($monthNames) {
            $commissionInfo = $this->calculateCommission($data->omzet);

            return [
                'marketing' => $data->marketing_name,
                'bulan' => $monthNames[$data->month] . ' ' . $data->year,
                'omzet' => $data->omzet,
                'komisi' => $commissionInfo['percentage'],
                'komisi_nominal' => $commissionInfo['nominal']
            ];
        });
    }

    private function calculateCommission($omzet)
    {
        if ($omzet >= 500000000) {
            $percentage = 10;
        } elseif ($omzet >= 200000000) {
            $percentage = 5;
        } elseif ($omzet >= 100000000) {
            $percentage = 2.5;
        } else {
            $percentage = 0;
        }

        $nominal = $omzet * ($percentage / 100);

        return [
            'percentage' => $percentage,
            'nominal' => $nominal
        ];
    }
}
