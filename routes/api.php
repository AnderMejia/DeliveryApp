<?php

use App\Http\Controllers\CompraController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RepartidorController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('repartidores', RepartidorController::class);
Route::resource('proveedores', ProveedorController::class);
Route::resource('productos', ProductoController::class);
Route::resource('users', UserController::class);
Route::resource('formapagos', FormaPagoController::class);
Route::resource('compras',  CompraController::class);

