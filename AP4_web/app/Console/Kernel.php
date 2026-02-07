<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * 
     * ========================================
     * 🗑️  NETTOYAGE AUTOMATIQUE DES CONVERSATIONS
     * ========================================
     * 
     * Exécuté CHAQUE JOUR à 2h du matin
     * 
     * Règles de suppression:
     * ✓ Chatbot seul (admin_active=false) → suppression après 30 jours
     * ✓ Support humain (admin_active=true) → suppression après 6 mois
     * 
     * Avantages:
     * ✅ Évite que la BDD grossisse à l'infini
     * ✅ Garde les conversations importantes (support) plus longtemps
     * ✅ Simple à ajuster (voir plus bas)
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('conversations:cleanup')
            ->daily()
            ->at('02:00')
            ->name('cleanup_conversations')
            ->description('Supprimer les conversations expirées');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
