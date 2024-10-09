<?php

use Illuminate\Support\Facades\Route;
use Src\SalaryHistory\Presentation\SalaryHistoryController;

Route::group([
    'prefix' => 'salary_histories'
], function () {
    Route::post('/', [SalaryHistoryController::class, 'store'])->name('salary_histories.store');
});
