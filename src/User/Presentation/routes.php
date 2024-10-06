<?php

use Illuminate\Support\Facades\Route;
use Src\User\Presentation\UserController;

Route::group([
    'prefix' => 'user'
], function () {
    Route::get('/', [UserController::class, 'get']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});
