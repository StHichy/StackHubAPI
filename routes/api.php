<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GerenciamentoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CanaisController;


Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest') ->name('register');

Route::post('/login', [ProfileController::class, 'createToken'])->name('login');

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {

});
require __DIR__.'/auth.php';



