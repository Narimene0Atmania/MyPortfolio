<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('desktop');
})->name('home');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/lang/{locale}', [LocaleController::class, 'update'])
    ->whereIn('locale', array_keys(config('portfolio.locales')))
    ->name('locale.switch');
