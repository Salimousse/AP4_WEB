<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conversation;
use Carbon\Carbon;

class CleanupExpiredConversations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversations:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprimer les conversations expirées selon leur type';

    /**
     * Execute the console command.
     * 
     * Règles de suppression:
     * 1. Conversations SANS support humain (chatbot seul) → 30 jours
     * 2. Conversations AVEC support humain → 6 mois
     */
    public function handle()
    {
        $this->info('🗑️  Nettoyage des conversations expirées...');
        $this->newLine();
        
        // ========================================
        // RÈGLE 1: Chatbot seul (admin_active = false)
        // ========================================
        $chatbotOnly = Conversation::where('admin_active', false)
            ->where('created_at', '<', Carbon::now()->subDays(30))
            ->count();
            
        Conversation::where('admin_active', false)
            ->where('created_at', '<', Carbon::now()->subDays(30))
            ->delete();
            
        if ($chatbotOnly > 0) {
            $this->info("✓ {$chatbotOnly} conversation(s) chatbot supprimée(s) (> 30 jours)");
        }

        // ========================================
        // RÈGLE 2: Avec support humain (admin_active = true)
        // ========================================
        $withSupport = Conversation::where('admin_active', true)
            ->where('created_at', '<', Carbon::now()->subMonths(6))
            ->count();
            
        Conversation::where('admin_active', true)
            ->where('created_at', '<', Carbon::now()->subMonths(6))
            ->delete();
            
        if ($withSupport > 0) {
            $this->info("✓ {$withSupport} conversation(s) support supprimée(s) (> 6 mois)");
        }

        // ========================================
        // Résumé
        // ========================================
        $totalDeleted = $chatbotOnly + $withSupport;
        
        $this->newLine();
        if ($totalDeleted > 0) {
            $this->info("✅ Total: {$totalDeleted} conversation(s) supprimée(s)");
        } else {
            $this->info('✅ Aucune conversation à nettoyer');
        }

        return Command::SUCCESS;
    }
}
