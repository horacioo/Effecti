<?php

use App\Http\Controllers\teste\PDFController;
use App\Http\Controllers\teste\RegistrationController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () { return view('cadastro.home'); });

Route::get('/cadastros/registrar', function () { return view('cadastro.cadastro'); })->name('cadastro.registrar');
Route::get('/cadastros/lista', [RegistrationController::class, 'lista'] )->name('cadastro.lista');
Route::get('/cadastros/editar/{id}', [RegistrationController::class, 'editar'] )->name('cadastro.lista.editar');
Route::delete('/cadastro/deletar/{id}', [RegistrationController::class, 'deletar'])->name('cadastro.deletar');
Route::get('/pdf', [PDFController::class, 'gerarPDF']);