<?php

namespace App\Interfaces\Repositories;

interface CommissionRepositoryInterface
{
    public function getGroupedSalesData();
    public function getSales();
}
