<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionGroupController;
use App\Http\Controllers\PermissionItemController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PatioController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\UnidadeMedidaController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\NotaFiscalController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\SaidaController;
use App\Http\Controllers\PrevisaoController;
use App\Http\Controllers\TransferenciaController;

Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/auth/validate', [AuthController::class, 'validateToken']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Permissões
    Route::middleware('permission:permissoes.view')->group(function () {
        Route::get('/permission_groups', [PermissionGroupController::class, 'index']);
        Route::get('/permission_groups/{id}', [PermissionGroupController::class, 'show']);
        Route::get('/permission_items', [PermissionItemController::class, 'index']);
    });
    
    Route::middleware('permission:permissoes.create')->post('/permission_groups', [PermissionGroupController::class, 'store']);
    Route::middleware('permission:permissoes.edit')->put('/permission_groups/{id}', [PermissionGroupController::class, 'update']);
    Route::middleware('permission:permissoes.delete')->delete('/permission_groups/{id}', [PermissionGroupController::class, 'destroy']);

    // Dashboard
    Route::middleware('permission:dashboard.view')->group(function () {
        Route::get('/dashboard/resumo-geral', [DashboardController::class, 'resumoGeral']);
        Route::get('/dashboard/resumo-estoque', [DashboardController::class, 'resumoEstoque']);
        Route::get('/dashboard/balanco', [DashboardController::class, 'balanco']);
    });

    // Grupos
    Route::middleware('permission:grupos.view')->get('/grupos', [GrupoController::class, 'index']);
    Route::middleware('permission:grupos.create')->post('/grupos', [GrupoController::class, 'store']);
    Route::middleware('permission:grupos.edit')->put('/grupos/{id}', [GrupoController::class, 'update']);
    Route::middleware('permission:grupos.delete')->delete('/grupos/{id}', [GrupoController::class, 'destroy']);

    // Materiais
    Route::middleware('permission:materiais.view')->get('/materiais', [MaterialController::class, 'index']);
    Route::middleware('permission:materiais.create')->post('/materiais', [MaterialController::class, 'store']);
    Route::middleware('permission:materiais.edit')->put('/materiais/{id}', [MaterialController::class, 'update']);
    Route::middleware('permission:materiais.delete')->delete('/materiais/{id}', [MaterialController::class, 'destroy']);

    // Pátios
    Route::middleware('permission:patios.view')->get('/patios', [PatioController::class, 'index']);
    Route::middleware('permission:patios.create')->post('/patios', [PatioController::class, 'store']);
    Route::middleware('permission:patios.edit')->put('/patios/{id}', [PatioController::class, 'update']);
    Route::middleware('permission:patios.delete')->delete('/patios/{id}', [PatioController::class, 'destroy']);

    // Fornecedores
    Route::middleware('permission:fornecedores.view')->get('/fornecedores', [FornecedorController::class, 'index']);
    Route::middleware('permission:fornecedores.create')->post('/fornecedores', [FornecedorController::class, 'store']);
    Route::middleware('permission:fornecedores.edit')->put('/fornecedores/{id}', [FornecedorController::class, 'update']);
    Route::middleware('permission:fornecedores.delete')->delete('/fornecedores/{id}', [FornecedorController::class, 'destroy']);

    // Unidades de Medida
    Route::middleware('permission:unidades-medida.view')->get('/unidades-medida', [UnidadeMedidaController::class, 'index']);
    Route::middleware('permission:unidades-medida.create')->post('/unidades-medida', [UnidadeMedidaController::class, 'store']);
    Route::middleware('permission:unidades-medida.edit')->put('/unidades-medida/{id}', [UnidadeMedidaController::class, 'update']);
    Route::middleware('permission:unidades-medida.delete')->delete('/unidades-medida/{id}', [UnidadeMedidaController::class, 'destroy']);

    // Colaboradores
    Route::middleware('permission:colaboradores.view')->get('/colaboradores', [ColaboradorController::class, 'index']);
    Route::middleware('permission:colaboradores.create')->post('/colaboradores', [ColaboradorController::class, 'store']);
    Route::middleware('permission:colaboradores.edit')->put('/colaboradores/{id}', [ColaboradorController::class, 'update']);
    Route::middleware('permission:colaboradores.delete')->delete('/colaboradores/{id}', [ColaboradorController::class, 'destroy']);

    // Notas Fiscais
    Route::middleware('permission:notas-fiscais.view')->get('/notas-fiscais', [NotaFiscalController::class, 'index']);
    Route::middleware('permission:notas-fiscais.create')->post('/notas-fiscais', [NotaFiscalController::class, 'store']);
    Route::middleware('permission:notas-fiscais.edit')->put('/notas-fiscais/{id}', [NotaFiscalController::class, 'update']);
    Route::middleware('permission:notas-fiscais.delete')->delete('/notas-fiscais/{id}', [NotaFiscalController::class, 'destroy']);

    // Entradas
    Route::middleware('permission:entradas.view')->get('/entradas', [EntradaController::class, 'index']);
    Route::middleware('permission:entradas.create')->post('/entradas', [EntradaController::class, 'store']);
    Route::middleware('permission:entradas.edit')->put('/entradas/{id}', [EntradaController::class, 'update']);
    Route::middleware('permission:entradas.delete')->delete('/entradas/{id}', [EntradaController::class, 'destroy']);

    // Saídas
    Route::middleware('permission:saidas.view')->get('/saidas', [SaidaController::class, 'index']);
    Route::middleware('permission:saidas.create')->post('/saidas', [SaidaController::class, 'store']);
    Route::middleware('permission:saidas.edit')->put('/saidas/{id}', [SaidaController::class, 'update']);
    Route::middleware('permission:saidas.delete')->delete('/saidas/{id}', [SaidaController::class, 'destroy']);

    // Transferências
    Route::middleware('permission:transferencias.view')->get('/transferencias', [TransferenciaController::class, 'index']);
    Route::middleware('permission:transferencias.create')->post('/transferencias', [TransferenciaController::class, 'store']);
    Route::middleware('permission:transferencias.edit')->put('/transferencias/{id}', [TransferenciaController::class, 'update']);
    Route::middleware('permission:transferencias.delete')->delete('/transferencias/{id}', [TransferenciaController::class, 'destroy']);

    // Previsões
    Route::middleware('permission:previsoes.view')->get('/previsoes', [PrevisaoController::class, 'index']);
    Route::middleware('permission:previsoes.create')->post('/previsoes', [PrevisaoController::class, 'store']);
    Route::middleware('permission:previsoes.edit')->put('/previsoes/{id}', [PrevisaoController::class, 'update']);
    Route::middleware('permission:previsoes.delete')->delete('/previsoes/{id}', [PrevisaoController::class, 'destroy']);
    
    // Previsões - Relatórios
    Route::middleware('permission:previsao-geral.view')->get('/previsoes/geral', [PrevisaoController::class, 'previsaoGeral']);
    Route::middleware('permission:previsao-por-patio.view')->get('/previsoes/por-patio', [PrevisaoController::class, 'previsaoPorPatio']);
});

