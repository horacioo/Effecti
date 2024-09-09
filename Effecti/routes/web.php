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


Route::get('/', [RegistrationController::class, 'index'])->name('pdf.gerar');

// Agrupamento de rotas para cadastros
Route::prefix('cadastros')->name('cadastro.')->group(function () {
    Route::get('/registrar', function () { return view('cadastro.cadastro'); })->name('registrar');
    Route::get('/pesquisar', function () { return view('cadastro.pesquisar');  })->name('pesquisar');

    Route::get('/lista', [RegistrationController::class, 'lista'])->name('lista');
    Route::get('/editar/{id}', [RegistrationController::class, 'editar'])->name('lista.editar');
    Route::delete('/deletar/{id}', [RegistrationController::class, 'deletar'])->name('deletar');
});

// Rota para gerar PDF
Route::get('/pdf', [PDFController::class, 'gerarPDF'])->name('pdf.gerar');