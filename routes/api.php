<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\InscritoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:api'])->group(function () {

    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/logout', [AuthController::class, 'logout']);

    // Usuário
    Route::get('/usuario/index', [UserController::class, 'index']);
    Route::get('/usuario/show/{id}', [UserController::class, 'show']);
    Route::put('/usuario/update/{id}', [UserController::class, 'update']);

    // Evento
    Route::get('/evento/index', [EventoController::class, 'index'])->name('eventos.index');
    Route::get('/evento/show/{id}', [EventoController::class, 'show'])->name('eventos.show');
    Route::put('/evento/update/{id}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/evento/destroy/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');

    // Inscrito
    Route::get('/inscrito/create', [InscritoController::class, 'create'])->name('inscritos.create');
    Route::get('/inscrito/index', [InscritoController::class, 'index'])->name('inscritos.index');
    Route::post('/inscrito/store', [InscritoController::class, 'store'])->name('inscritos.store');
    Route::get('/inscrito/show/{id}', [InscritoController::class, 'show'])->name('inscritos.show');
    Route::put('/inscrito/update/{id}', [InscritoController::class, 'update'])->name('inscritos.update');
    Route::delete('/inscrito/destroy/{id}', [InscritoController::class, 'destroy'])->name('inscritos.destroy');
});

// Route::group([

//     'middleware' => 'api',
//     'prefix' => 'auth'

// ], function ($router) {

//     Route::post('login', 'AuthController@login');
//     Route::post('logout', 'AuthController@logout');
//     Route::post('refresh', 'AuthController@refresh');
//     Route::post('me', 'AuthController@me');

// });
