<?php

use Illuminate\Support\Facades\Route;
use App\http\controllers\CategoriaController;
use App\http\controllers\ProductoController;
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
Route::resource('almacen/categoria',CategoriaController::class);
Route::resource('almacen/producto',ProductoController::class);
