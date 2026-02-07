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
// 💬 ROUTES DU CHATBOT DE SUPPORT
// ========================================
// Ces routes gèrent la communication en temps réel avec le chatbot
// Utilisées par le widget de chat sur la page /assistance

/**
 * Envoyer un message utilisateur au chatbot
 * 
 * Endpoint: POST /chat/{conversationId}/send
 * 
 * Payload JSON:
 * {
 *   "message": "Quelle est le prix des places ?",
 *   "conversationId": "uuid-or-random-id"
 * }
 * 
 * Processus:
 * 1. Valide le message et crée/récupère une conversation
 * 2. Stocke le message utilisateur en base
 * 3. Détecte les mots-clés d'escalade (admin, humain, parler à)
 * 4. Appelle l'API Google Gemini pour générer une réponse
 * 5. Broadcast la réponse via WebSocket en temps réel
 * 6. Retourne la réponse et diffuse l'événement MessageSent
 * 
 * Response:
 * {
 *   "reply": "Texte de la réponse du bot"
 * }
 * 
 * Écouteur WebSocket (côté client):
 *   window.Echo.channel('conversation.' + conversationId)
 *     .listen('.message.sent', (message) => { ... })
 */
Route::post('/chat/{conversationId}/send', [ChatbotController::class, 'sendMessage']);

/**
 * Vérifier s'il y a une réponse admin
 * 
 * Endpoint: GET /chat/{conversationId}/check
 * 
 * Utilisé dans une boucle d'interrogation (polling) pour vérifier
 * si un admin humain a répondu à une demande d'escalade
 * 
 * Retour: null si pas de réponse, ou le contenu du message admin
 * 
 * Response:
 * {
 *   "message": "Voici la réponse de l'admin" ou null
 * }
 * 
 * Flux d'escalade:
 * 1. Utilisateur écrit "parler à un humain"
 * 2. ChatbotController détecte le mot-clé et envoie AdminRequested
 * 3. Frontend poll /check toutes les 2 secondes
 * 4. Quand un admin répond, /check retourne la réponse
 */
Route::get('/chat/{conversationId}/check', [ChatbotController::class, 'checkMessage']);

/**
 * Récupérer l'historique complet des messages
 * 
 * Endpoint: GET /chat/{conversationId}/messages
 * 
 * Retourne tous les messages de la conversation (user, bot, admin)
 * triés par date croissante.
 * 
 * Utilisé pour restaurer l'historique lors du chargement
 * (Actuellement DÉSACTIVÉ dans support.blade.php pour
 * éviter de montrer l'historique aux utilisateurs non-auth)
 * 
 * Response:
 * {
 *   "messages": [
 *     {
 *       "id": 1,
 *       "sender": "user|bot|admin",
 *       "content": "Texte du message",
 *       "created_at": "2024-01-15T10:30:00Z"
 *     },
 *     ...
 *   ]
 * }
 */
Route::get('/chat/{conversationId}/messages', [ChatbotController::class, 'getMessages']);

Route::get('/festivals', [PageController::class, 'festivals'])->name('festivals');
Route::get('/programme/{id}', [PageController::class, 'festival'])->name('programme');
Route::get('/billet/{idBillet}', [ReservationController::class, 'showTicket'])->name('reservation.success');
Route::get('/avis/{idManif}', [\App\Http\Controllers\AvisController::class, 'showByManifestration'])->name('avis.index');

require __DIR__.'/auth.php';
