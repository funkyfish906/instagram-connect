<?php

use App\Http\Controllers\InstagramConnectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InstagramConnectController::class, 'index'])->name('instagram-connect.index');
Route::get('connect', [InstagramConnectController::class, 'connect'])->name('instagram-connect.connect');
