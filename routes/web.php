<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\ArtigoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [UserController::class, 'index']);

Route::get('/create', function () {
    return view('create');
});

Route::post('/post', [UserController::class, 'store']);
Route::post('/edit/', [UserController::class, 'edit']);
Route::put('/update/{id}', [UserController::class, 'update']);
Route::get('/contas', [ContaController::class, 'index']);
Route::post('/delete/', [UserController::class, 'delete']);

// Delete artigo
Route::delete('artigo/{id}', [ArtigoController::class, 'destroy']);
