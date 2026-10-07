<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use App\Models\Plan;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/plans', [PlanController::class, 'index']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grupo protegido por autenticação
Route::middleware(['auth'])->group(function () {
    // Rotas de Perfil/login
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // [FRONT-END GUI]: Rotas de Assinatura e Checkout

    // Rotas de checkout e assinatura
    Route::get('/checkout/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/checkout/process', [SubscriptionController::class, 'process'])->name('subscription.process');
});

// Rotas do Painel Administrativo
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('plans', AdminPlanController::class);
});

Route::middleware(['auth', 'subscription'])->group(function () {
    Route::get('/teste-projeto', function () {
        return "Parabéns! Tens uma subscrição ativa e conseguiste aceder.";
    });
});

require __DIR__.'/auth.php';
