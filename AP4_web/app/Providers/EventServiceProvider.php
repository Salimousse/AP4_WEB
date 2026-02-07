<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\UserLoggedOut;
use App\Listeners\CleanupConversationsOnLogout;
use Illuminate\Auth\Events\Logout;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Écouter l'événement de déconnexion Laravel par défaut
        Logout::class => [
            CleanupConversationsOnLogout::class,
        ],
    ];
}
