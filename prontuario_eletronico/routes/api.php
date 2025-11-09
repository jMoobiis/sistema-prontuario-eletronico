<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AtendimentoController;

Route::get('/ping', fn() => response()->json(['message' => 'API OK!']));

Route::apiResource('pacientes', PacienteController::class);
Route::apiResource('atendimentos', AtendimentoController::class);

