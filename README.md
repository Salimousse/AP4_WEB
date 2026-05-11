# Plateforme de Gestion de Festival & Événements (AP4 Web)

Ce projet est une application web complète développée avec **Laravel** dédiée à la gestion, l'organisation et la billetterie de festivals et d'événements culturels (concerts, conférences, ateliers).

##  Fonctionnalités Principales

###  Billetterie & Réservations
- **Système de Réservation** : Réservation de billets pour les différentes manifestations.
- **Paiements en ligne sécurisés** : Intégration de l'API **Stripe** pour la gestion des transactions (via `stripe/stripe-php`).
- **Génération de QR Codes** : Création de QR Codes pour la validation électronique des billets (via `simplesoftwareio/simple-qrcode`).
- **Gestion des Types de Paiements** : Support de multiples méthodes de paiement.

### 📅 Gestion des Événements
- **Festivals et Manifestations** : Création et gestion complète de festivals.
- **Multi-formats** : Prise en charge de divers types d'événements tels que les **Concerts**, **Conférences** et **Ateliers**.
- **Gestion des Lieux** : Attribution de salles et lieux spécifiques pour chaque événement.
- **Domaines & Spécialités** : Catégorisation des événements par domaine.

###  Intervenants & Artistes
- **Gestion des Profils** : Suivi des artistes et des intervenants.
- **Affectation aux événements** : Gestion de qui *anime*, *présente* ou *produit* lors d'une manifestation.

### Chatbot & Messagerie en Temps Réel
- **Messagerie Instantanée** : Système de chat en temps réel (alimenté par **Reverb / Pusher**).
- **Service Client Automatisé (Chatbot)** : Intégration d'un assistant virtuel (`ChatbotService`).
- **Détection d'Escalade** : Système intelligent (`EscalationDetector`) capable d'analyser la conversation pour rediriger un utilisateur vers un administrateur humain si besoin.
- **Gestion des Conversations** : Nettoyage automatique des sessions de chat à la déconnexion (`CleanupConversationsOnLogout`).

### Sponsoring
- **Gestion des Sponsors** : Mise en valeur des partenaires commerciaux du festival.
- **Niveaux de Sponsoring** : Hiérarchisation des sponsors selon leur contribution financier (Niveaux de sponsors).

### Utilisateurs & Administration
- **Rôles & Accès** : Support des clients, administrateurs et intervenants.
- **Connexion Sociale** : Intégration potentielle des connexions via réseaux sociaux (via `SocialiteProviders`).
- **Avis et Retours** : Système permettant aux clients de laisser des avis sur les événements.

---

##  Stack Technique

- **Framework Backend** : [Laravel](https://laravel.com/) (PHP)
- **Frontend** : Blade, HTML/CSS/JS compilés via **Vite**
- **Design Web** : **Tailwind CSS**
- **Bases de données** : MySQL / PostgreSQL (selon votre configuration)
- **Websockets** : Laravel Reverb / Pusher pour le temps réel
- **Paiements** : Stripe API

##  Prérequis

Avant de commencer, assurez-vous d'avoir installé :
- [PHP](https://www.php.net/) (version requise par votre version de Laravel, ex: 8.2+)
- [Composer](https://getcomposer.org/)
- [Node.js & npm](https://nodejs.org/)

## Installation
 
1. **Cloner le répertoire :**
   ```bash
   git clone <url-de-votre-repo>
   cd AP4_web
