<?php

use Illuminate\Support\Facades\Route;
use Src\User\Presentation\UserController;

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'get'])->name('users.get');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.delete');
    });
});