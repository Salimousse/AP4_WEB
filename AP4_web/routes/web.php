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

Route::get('/', function () {
    return view('welcome');
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/reservation/{idManif}', [ReservationController::class, 'showForm'])->name('reservation.create');
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/mes-reservations', [ProfileController::class, 'ShowTicket'])->name('page.mes-reservations');
}); // <-- cette accolade ferme le groupe auth

// Route temporaire pour tester les WebSockets
Route::get('/test-websocket', function () {
    return view('test-websocket');
});

// Dev helper: déclenche un message et broadcast pour tester en direct
Route::get('/debug/ws-broadcast', function () {
    $conversation = \App\Models\Conversation::firstOrCreate(
        ['conversation_id' => 'conv_test_real'],
        ['admin_active' => false]
    );

    $message = \App\Models\Message::create([
        'conversation_id' => $conversation->id,
        'sender' => 'user',
        'content' => 'Message debug ' . now()->toDateTimeString(),
    ]);

    broadcast(new \App\Events\MessageSent($message));

    return response()->json(['ok' => true, 'conversation' => $conversation->conversation_id]);
});
Route::get('/reservation/validation', [ReservationController::class, 'validerPaiement'])->name('reservation.validation');
Route::get('/ticket/{idBillet}', [ReservationController::class, 'showTicket'])->name('page.ticket-reservation');
Route::get('/billet/{idBillet}', [ReservationController::class, 'showTicket'])->name('reservation.success');




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
Route::get('/chat/{conversationId}/check', [ChatbotController::class, 'checkMessage']);
Route::get('/chat/{conversationId}/messages', [ChatbotController::class, 'getMessages']);

Route::get('/programme', [PageController::class, 'festival'])->name('programme');




require __DIR__.'/auth.php';
