<?php

use App\Livewire\Home;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->middleware('auth')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
});

require_once 'auth.php';
require_once 'conversation.php';
