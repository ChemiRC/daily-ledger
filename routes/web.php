<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirigir la raíz al login si no hay sesión, o al dashboard si la hay
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

// Todas tus rutas protegidas por Login
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Tu página principal ahora será /dashboard
    Route::get('/dashboard', [ExpenseController::class, 'index'])->name('dashboard');
    
    // Rutas de Gastos
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Rutas de Categorías
    Route::post('/categories', [ExpenseController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{category}', [ExpenseController::class, 'destroyCategory'])->name('categories.destroy');

    // Rutas de Perfil (las trae Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';