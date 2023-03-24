<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RepartidorController;
use App\Http\Controllers\ProveedorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\CompraController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin',[HomeController::class,'dash'])->name('admin.dash')->middleware('auth.admin');
Route::resource('repartidores', RepartidorController::class)->middleware('auth.admin');
Route::resource('proveedores', ProveedorController::class)->middleware('auth.admin');
Route::resource('productos', ProductoController::class)->middleware('auth.admin');
Route::get('/productos-compra',[ProductoController::class,'index']);
Route::get('/compras',[CompraController::class,'index']);

Route::resource('pagos', FormaPagoController::class);

