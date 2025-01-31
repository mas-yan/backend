<?php

use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/commissions', [PenjualanController::class, 'index']);
Route::prefix('payments')->group(function () {
    Route::post('/', [PembayaranController::class, 'store']);
    Route::get('/', [PembayaranController::class, 'index']);
    Route::get('/{id}', [PembayaranController::class, 'show']);
    Route::put('/{id}', [PembayaranController::class, 'update']);
});

Route::get('/sales/{saleId}/payments', [PembayaranController::class, 'getBySale']);
