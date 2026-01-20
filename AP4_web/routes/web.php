<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\MicrosoftAuthController; // N'oublie pas l'import en haut !
use App\Http\Controllers\FacebookAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ChatbotController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('connected-accounts');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/connected-accounts', function () {
    return view('connected-accounts');
})->middleware(['auth', 'verified'])->name('connected-accounts');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes pour l'authentification Google et Microsoft
Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/callback', [GoogleAuthController::class, 'callbackGoogle']);
Route::delete('auth/google/unlink', [GoogleAuthController::class, 'unlink'])->name('auth.google.unlink');


Route::get('auth/microsoft', [MicrosoftAuthController::class, 'redirect'])->name('auth.microsoft');
Route::get('auth/microsoft/callback', [MicrosoftAuthController::class, 'callback']);
Route::delete('auth/microsoft/unlink', [MicrosoftAuthController::class, 'unlink'])->name('auth.microsoft.unlink');

Route::get('auth/facebook', [FacebookAuthController::class, 'redirect'])->name('auth.facebook');
Route::get('connect/facebook/check', [FacebookAuthController::class, 'callback']);
Route::delete('auth/facebook/unlink', [FacebookAuthController::class, 'unlink'])->name('auth.facebook.unlink');

// Pages statiques
Route::get('/a-propos', [PageController::class, 'about'])->name('about');
Route::get('/politique-de-confidentialite', [PageController::class, 'privacy'])->name('privacy');
Route::get('/conditions-de-vente', [PageController::class, 'terms'])->name('terms');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::post('/chat/{conversationId}/send', [ChatbotController::class, 'sendMessage']);

require __DIR__.'/auth.php';
