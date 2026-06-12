<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CamisetaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TallaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas de la API con validación de ID numérico


Route::apiResource('clientes', ClienteController::class)
    ->parameters(['clientes' => 'id'])
    ->where(['id' => '[0-9]+']);

Route::apiResource('camisetas', CamisetaController::class)
    ->parameters(['camisetas' => 'id'])
    ->where(['id' => '[0-9]+']);

Route::apiResource('tallas', TallaController::class)
    ->parameters(['tallas' => 'id'])
    ->where(['id' => '[0-9]+']);
