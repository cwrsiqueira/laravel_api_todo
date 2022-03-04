<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/ping', [ApiController::class, 'pong']);

Route::get('/unauthorized', [AuthController::class, 'loginFail'])->name("login");

Route::post('/user', [AuthController::class, 'create']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware("auth:api")->get('/auth/logout', function(){
    Auth::logout();
    return ["success" => "Usuário deslogado"];
});

Route::middleware("auth:api")->post('/todo', [ApiController::class, 'add']);
Route::middleware("auth:api")->get('/todo', [ApiController::class, 'getAll']);
Route::get('/todo/{id}', [ApiController::class, 'getById']);
Route::put('/todo/{id}', [ApiController::class, 'update']);
Route::delete('/todo/{id}', [ApiController::class, 'delete']);


// AUTENTICAÇÃO COM SANCTUM
// Route::get('/unauthorized', [AuthController::class, 'loginFail'])->name("login");
// Route::middleware("auth:sanctum")->get('/auth/logout', [AuthController::class, 'logout']);
// Route::post('/auth', [AuthController::class, 'login']);
// Route::middleware("auth:sanctum")->post('/todo', [ApiController::class, 'add']);
// Route::middleware("auth:sanctum")->get('/todo', [ApiController::class, 'getAll']);
