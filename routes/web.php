<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class , 'index'])->middleware(['auth', 'verified'])->name('dashboard.index');




Route::middleware('auth')->group(function () {
    Route::get('/task', [TaskController::class , 'index'])->name('task.index');
    Route::get('/task/add', [TaskController::class , 'create'])->name('task.form');
    Route::post('/task/store', [TaskController::class , 'store'])->name('task.store');
    Route::delete('/task/store/{id}', [TaskController::class , 'destroy'])->name('task.destroy');
    Route::get('/task/{task}/edit', [TaskController::class, 'edit'])->name('task.edit');
    Route::put('/task/{task}', [TaskController::class, 'update'])->name('task.update');
    Route::patch('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('task.updateStatus');
});





Route::middleware('auth')->group(function () {
    Route::get('/search', [SearchController::class , 'index'])->name('search.index');
    Route::get('/search/filter', [SearchController::class , 'search'])->name('search.filter');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
