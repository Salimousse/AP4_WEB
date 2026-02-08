<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * 📝 Service de réponses par défaut
 * 
 * Genère des réponses intelligentes quand l'IA n'est pas disponible
 */
class FallbackResponses
{
    /**
     * Mappe des mots-clés vers les réponses
     */
    private array $responses = [
        'festival|dispo' => "🎵 **Festival Cale Sons 2026** 🎵\n\n📅 **Dates**: Août 2026\n🎭 **Thème**: 'Terres de Légendes'\n🎪 **Activités**: Concerts, Ateliers créatifs\n\nQue souhaitez-vous savoir de plus ? (tarifs, programmation, hébergement...)",
        
        'tarif|prix|billet' => "💰 **Tarifs Festival 2026**\n\n🎫 Pass 1 jour: 45€\n🎟️ Pass 2 jours: 80€\n🌟 Pass VIP: 120€\n\n✨ Réductions étudiants disponibles !",
        
        'programme|artiste|concert' => "🎤 **Programmation 2026**\n\n🌟 Têtes d'affiche à venir\n🎸 Scènes multiples\n🎶 Ambiance 'Terres de Légendes'\n\nLe programme complet sera dévoilé prochainement !",
        
        'lieu|où|adresse' => "📍 **Localisation**\n\nLe festival se déroule dans un cadre exceptionnel.\n🚗 Parkings disponibles\n🚌 Navettes spéciales\n\nPlus d'infos sur l'accès bientôt !",
    ];

    /**
     * Génère une réponse basée sur le message utilisateur
     */
    public function generate(string $message): string
    {
        $lowerMessage = Str::lower($message);

        foreach ($this->responses as $pattern => $response) {
            if (Str::containsAny($lowerMessage, explode('|', $pattern))) {
                return $response;
            }
        }

        // Réponse par défaut
        return "Bonjour ! 😊 Je suis l'assistant du Festival Cale Sons 2026.\n\nJe peux vous renseigner sur :\n🎵 Les festivals disponibles\n💰 Les tarifs\n📅 Les dates\n🎤 La programmation\n📍 L'accès\n\nQue souhaitez-vous savoir ?";
    }
}
