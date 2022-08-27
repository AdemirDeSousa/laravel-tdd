<?php

use App\Http\Controllers\Api\ShortUrl\ShortUrlController;
use App\Http\Controllers\Api\ShortUrl\StatsController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index']);

Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('api.short-url.store');
Route::delete('/short-urls/{shortUrl:code}', [ShortUrlController::class, 'destroy'])->name('api.short-url.delete');

Route::get('/short-urls/{shortUrl:code}/stats/last-visit', [StatsController::class, 'lastVisit'])->name('api.short-url.stats.last-visit');
Route::get('/short-urls/{shortUrl:code}/stats/visits', [StatsController::class, 'visits'])->name('api.short-url.stats.visits');

