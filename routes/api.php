<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\plnController;
use App\Http\Controllers\indihomeController;
use App\Http\Controllers\authController;

Route::post('listrik', [plnController::class, 'cekPln'])->middleware('verifyHeader');
Route::post('indihome', [indihomeController::class, 'cekIndihome'])->middleware('verifyHeader');

