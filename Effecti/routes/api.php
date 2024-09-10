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


/**
 * Grupo de rotas para cadastro e gerenciamento de registros.
 */
Route::prefix('Cadastro')->group(function () {
    // Rota para registrar um novo cadastro
    Route::post('/Registra', [RegistrationController::class, 'salva'])->name('salvarCadastro');

    // Rota para verificar o e-mail durante o cadastro
    Route::get('/Email/Verifica', [RegistrationController::class, 'VerificaEmail'])->name('VerificaEmail');

    // Rota para validar o CPF durante o cadastro
    Route::get('/Cpf/Verifica', [RegistrationController::class, 'validaCPF'])->name('VerificaCpf');

    // Rota para atualizar um cadastro existente
    Route::put('/editar/commit', [RegistrationController::class, 'update'])->name('salvarEdicao');

    // Rota para pesquisar registros
    Route::post('/consultar', [RegistrationController::class, 'pesquisar'])->name('pesquisa');
});
