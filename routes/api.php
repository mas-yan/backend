<?php

use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/commissions', [PenjualanController::class, 'comission']);
Route::prefix('payments')->group(function () {
    Route::post('/', [PembayaranController::class, 'store']);
    Route::get('/', [PembayaranController::class, 'index']);
    Route::get('/{id}', [PembayaranController::class, 'show']);
    Route::put('/{id}', [PembayaranController::class, 'update']);
});

Route::prefix('sales')->group(function () {
    Route::get('/', [PenjualanController::class, 'sale']);
    Route::get('{saleId}/payments', [PembayaranController::class, 'getBySale']);
});
