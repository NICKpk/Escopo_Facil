<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\LegalDocumentController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Models\Plan;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/plans', [PlanController::class, 'index']);
Route::post('/contato', [ContactController::class, 'send'])->middleware('throttle:5,1')->name('contact.send');
Route::get('/termos-de-uso/{version?}', [LegalDocumentController::class, 'terms'])->name('legal.terms');
Route::get('/privacidade/{version?}', [LegalDocumentController::class, 'privacy'])->name('legal.privacy');
Route::get('/assinar/pro', [SubscriptionController::class, 'start'])->name('subscription.start');
Route::get('/checkout/options/pro', [SubscriptionController::class, 'options'])->name('subscription.options');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grupo protegido por autenticação
Route::middleware(['auth', 'verified'])->group(function () {
    // Rotas de Perfil/login
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // [FRONT-END GUI]: Rotas de Assinatura e Checkout

    // Rotas de checkout e assinatura
    Route::get('/checkout/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/payments/{payment}', [SubscriptionController::class, 'status'])->middleware('throttle:20,1')->name('subscription.status');
    Route::post('/payments/{payment}/simulate', [SubscriptionController::class, 'simulate'])->middleware('throttle:10,1')->name('subscription.simulate');
    Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/checkout/process', [SubscriptionController::class, 'process'])->middleware('throttle:10,1')->name('subscription.process');
});

// Rotas do Painel Administrativo
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('plans', AdminPlanController::class);
});

Route::middleware(['auth', 'verified', 'subscription'])->group(function () {
    Route::get('/teste-projeto', function () {
        return "Parabéns! Tens uma subscrição ativa e conseguiste aceder.";
    });
});

require __DIR__.'/auth.php';
