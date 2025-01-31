<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data penjualan yang sudah dikelompokkan
        $salesData = Penjualan::join('marketings', 'penjualans.marketing_id', '=', 'marketings.id')
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

        // Mapping nama bulan dalam Bahasa Indonesia
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

        // Format data hasil perhitungan
        $result = [];
        foreach ($salesData as $data) {
            $commissionInfo = $this->calculateCommission($data->omzet);

            $result[] = [
                'Marketing' => $data->marketing_name,
                'Bulan' => $monthNames[$data->month] . ' ' . $data->year,
                'Omzet' => $data->omzet,
                'Komisi' => $commissionInfo['percentage'] . '%',
                'Komisi Nominal' => $commissionInfo['nominal']
            ];
        }

        return response()->json(['data' => $result]);
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Penjualan $penjualan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penjualan $penjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        //
    }
}
