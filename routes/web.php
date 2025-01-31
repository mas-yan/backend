<?php

use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
