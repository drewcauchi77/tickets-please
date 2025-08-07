<?php

use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthorsController;
use App\Http\Controllers\Api\V1\AuthorTicketsController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tickets', TicketController::class)->except('update');
    Route::put('tickets/{ticket}', [TicketController::class, 'replace'])->name('tickets.replace');
    Route::patch('tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');

    Route::apiResource('users', UserController::class)->except('update');
    Route::put('users/{ticket}', [UserController::class, 'replace'])->name('users.replace');
    Route::patch('users/{ticket}', [UserController::class, 'update'])->name('users.update');

    Route::apiResource('authors', AuthorsController::class)->except(['store', 'update', 'destroy']);

    Route::apiResource('authors.tickets', AuthorTicketsController::class)->except('update');
    Route::put('authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'replace'])->name('author.tickets.replace');
    Route::patch('authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'update'])->name('author.tickets.update');
});
