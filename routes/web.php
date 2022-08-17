<?php

use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/register', \App\Http\Controllers\RegisterController::class)->name('register');

Route::middleware('auth')->group(function(){
    Route::get('/todo', \App\Http\Controllers\Todo\IndexController::class)->name('todo.index');
    Route::post('/todo', \App\Http\Controllers\Todo\CreateController::class)->name('todo.store');
    Route::put('/todo/{todo}', \App\Http\Controllers\Todo\UpdateController::class)->name('todo.update');
    Route::delete('/todo/{todo}', \App\Http\Controllers\Todo\DeleteController::class)->name('todo.destroy');
});

