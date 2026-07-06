<?php

use App\Http\Controllers\WordToPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/convert/word-to-pdf', WordToPdfController::class)->name('word-to-pdf.convert');
