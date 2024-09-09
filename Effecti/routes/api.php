<?php

use App\Http\Controllers\teste\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/Cadastro/Registra', [RegistrationController::class, 'salva'])->name("salvarCadastro");
Route::get('/Cadastro/Email/Verifica', [RegistrationController::class, 'VerificaEmail'])->name("VerificaEmail");
Route::get('/Cadastro/Cpf/Verifica', [RegistrationController::class, 'validaCPF'])->name("VerificaCpf");
Route::put('/Cadastro/editar/commit', [RegistrationController::class, 'update'])->name("salvarEdicao");
Route::get('/Cadastro/consultar', [RegistrationController::class, 'pesquisar'])->name("pesquisa");
