<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Middleware\CheckIsLogged;
use App\Http\Middleware\CheckIsNotLogged;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::middleware([CheckIsNotLogged::class])->group(function(){
    Route::get('/login', [AuthController::class, 'login']);
    Route::post('/loginSubmit', [AuthController::class, 'loginSubmit']);
});

// If User is Logged In
Route::middleware([CheckIsLogged::class])->group(function (){
    Route::get('/', [MainController::class, 'index'])->name('home');
    Route::get('/newnote', [MainController::class, 'newNote'])->name('new');

    Route::get('/edit/{id}', [MainController::class, 'editNote'])->name('edit'); // Edit Route
    Route::get('/delete/{id}', [MainController::class, 'deleteNote'])->name('delete'); // Delete Route

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});