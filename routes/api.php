<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BukuApiController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PeminjamanApiController;
use App\Http\Controllers\Api\V1\AuthTokenController;

Route::post('/v1/auth/login', [AuthTokenController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 1. Endpoint API Buku (CRUD)
Route::apiResource('v1/bukus', BukuApiController::class);

// 2. Endpoint Autentikasi Sanctum (Login & Logout)
Route::post('v1/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('v1/logout', [AuthController::class, 'logout']);

// 3. Endpoint Peminjaman Buku (Dilindungi Auth Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('v1/peminjamans', [PeminjamanApiController::class, 'index']);
    Route::post('v1/peminjamans', [PeminjamanApiController::class, 'store']);
    Route::patch('v1/peminjamans/{id}/kembalikan', [PeminjamanApiController::class, 'kembalikan']);
});