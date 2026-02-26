<?php

use App\Http\Controllers\FeedController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('feeds', FeedController::class)->except(['show']);
Route::patch('feeds/{feed}/toggle', [FeedController::class, 'toggle'])->name('feeds.toggle');
