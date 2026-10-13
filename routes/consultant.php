<?php

use App\Http\Controllers\Consultant\CustomerController;
use App\Http\Controllers\Consultant\CustomerNoteController;
use App\Http\Controllers\Consultant\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Consultant
|--------------------------------------------------------------------------
| A consultant's own customer book — their tool for their own business, not
| a channel into ITM's clients. Guarded by auth + the consultant role.
*/

Route::middleware(['auth', 'verified', 'consultant'])
    ->prefix('consultant')
    ->name('consultant.')
    ->group(function () {
        Route::resource('customers', CustomerController::class);

        Route::post('customers/{customer}/notes', [CustomerNoteController::class, 'store'])
            ->name('customers.notes.store');

        Route::post('customers/{customer}/tasks', [TaskController::class, 'store'])
            ->name('customers.tasks.store');

        Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
        Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });
