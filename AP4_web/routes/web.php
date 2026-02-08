<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FacebookAuthController;
use App\Http\Controllers\MicrosoftAuthController;
use App\Models\Sponsor;

Route::get('/', function () {
    $sponsors = Sponsor::limit(5)->get();
    return view('welcome', compact('sponsors'));
});

Route::get('/dashboard', function () {
    return redirect()->route('connected-accounts');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/connected-accounts', function () {
    return view('connected-accounts');
})->middleware(['auth', 'verified'])->name('connected-accounts');



Route::get("/login-admin", function () {
    return view("auth.login-admin");
})->name("login-admin");

// Espace admin : dashboard protégé par le middleware is_admin
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/interventions', [\App\Http\Controllers\Admin\InterventionController::class, 'index'])->name('admin.interventions');
    Route::get('/admin/interventions/{id}', [\App\Http\Controllers\Admin\InterventionController::class, 'show'])->name('admin.intervention.show');
    Route::post('/admin/interventions/{id}/respond', [\App\Http\Controllers\Admin\InterventionController::class, 'respond'])->name('admin.intervention.respond');
});

// Route de validation Stripe (hors auth car Stripe redirige sans session)
// IMPORTANT : AVANT le groupe auth pour éviter le conflit avec /reservation/{idManif}
Route::get('/reservation/validation', [ReservationController::class, 'validerPaiement'])->name('reservation.validation');

// API pour vérifier un billet via QR code
Route::get('/api/verify-ticket/{token}', [ReservationController::class, 'verifyTicket'])->name('api.verify-ticket');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/reservation/{idManif}', [ReservationController::class, 'showForm'])->name('reservation.create');
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/mes-reservations', [ProfileController::class, 'ShowTicket'])->name('page.mes-reservations');
    Route::get('/ticket/{idBillet}', [ReservationController::class, 'showTicket'])->name('page.ticket-reservation');
    
    // Routes pour les avis
    Route::get('/avis/form/{idBillet}', [\App\Http\Controllers\AvisController::class, 'showForm'])->name('avis.form');
    Route::post('/avis/{idBillet}', [\App\Http\Controllers\AvisController::class, 'store'])->name('avis.store');
}); // <-- cette accolade ferme le groupe auth

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
Route::get('/assistance', [PageController::class, 'support'])->name('support');
Route::get('/a-propos', [PageController::class, 'about'])->name('about');
Route::get('/politique-de-confidentialite', [PageController::class, 'privacy'])->name('privacy');
Route::get('/conditions-de-vente', [PageController::class, 'terms'])->name('terms');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// ========================================
// 💬 ROUTES DU CHATBOT
// ========================================
Route::group(['prefix' => 'chat/{conversationId}'], function () {
    Route::post('send', [ChatbotController::class, 'sendMessage']);
    Route::get('check', [ChatbotController::class, 'checkMessage']);
    Route::get('messages', [ChatbotController::class, 'getMessages']);
});

Route::get('/festivals', [PageController::class, 'festivals'])->name('festivals');
Route::get('/programme/{id}', [PageController::class, 'festival'])->name('programme');
Route::get('/billet/{idBillet}', [ReservationController::class, 'showTicket'])->name('reservation.success');
Route::get('/avis/{idManif}', [\App\Http\Controllers\AvisController::class, 'showByManifestration'])->name('avis.index');

require __DIR__.'/auth.php';
