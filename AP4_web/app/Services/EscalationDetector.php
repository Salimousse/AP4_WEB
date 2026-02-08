<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * 🔍 Service de détection d'escalade
 * 
 * Détecte si l'utilisateur demande à parler à un humain
 * tout en étant simple et maintenable
 */
class EscalationDetector
{
    /**
     * Mots-clés qui déclenchent une escalade
     */
    private array $escalationKeywords = [
        'humain',
        'admin',
        'parler à',
        'représentant',
        'agent',
        'support humain',
    ];

    /**
     * Vérifie si un message demande une escalade
     */
    public function shouldEscalate(string $message): bool
    {
        $lowerMessage = Str::lower($message);
        
        foreach ($this->escalationKeywords as $keyword) {
            if (Str::contains($lowerMessage, $keyword)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Retourne le message d'escalade standard
     */
    public function getEscalationMessage(): string
    {
        return "Un administrateur va prendre le relais. Veuillez patienter.";
    }
}
