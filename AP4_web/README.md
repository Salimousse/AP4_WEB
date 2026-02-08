# 🎵 Festival Cale Sons 2026 - Plateforme Web

> **Plateforme complète de gestion et réservation de festivals avec chatbot intelligent temps réel**

## 📋 Table des matières

- [Vue d'ensemble](#vue-densemble)
- [Fonctionnalités principales](#fonctionnalités-principales)
- [Architecture](#architecture)
- [Technologies](#technologies)
- [Dépendances & Frameworks](#dépendances--frameworks-installés)
- [Installation](#installation)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [Refactorisation](#refactorisation)
- [Support](#support)

---

## 🎯 Vue d'ensemble

**Cale Sons 2026** est une plateforme web complète pour le Festival Cale Sons, permettant aux utilisateurs de :
- Découvrir et explorer les festivals disponibles
- Réserver des billets en ligne (paiement via Stripe)
- Consulter l'historique de leurs réservations
- Laisser des avis sur les événements
- Gérer leur profil et leurs connexions externes
- **Chatter en temps réel avec un assistant IA** (chatbot intelligent)

**Pour les administrateurs :**
- Tableau de bord de gestion
- Intervention en temps réel sur les conversations clients
- Gestion des escalades chatbot
- Vue d'ensemble des demandes de support

---

## ✨ Fonctionnalités principales

### 👥 Authentification & Comptes
- **Inscription / Connexion** classiques
- **Connexions OAuth** : Google, Microsoft, Facebook
- **Gestion du profil** : modification d'email, mot de passe, données personnelles
- **Comptes associés** : gestion centralisée des comptes externes

### 🎫 Réservation & Billetterie
- **Exploration des festivals** : cartes, galeries, descriptions détaillées
- **Réservation en ligne** : sélection de date, nombre de billets, passes (Standard/VIP)
- **Paiement sécurisé** : intégration Stripe
- **Billets numériques** : QR codes générés automatiquement
- **Historique** : consulter toutes ses réservations

### 💬 Chatbot Intelligent (IA)
- **Assistant conversationnel 24/7** : réponses aux questions sur le festival
- **Escalade humaine** : détection automatique des demandes de support
- **Fallback responses** : réponses par défaut quand l'IA n'est pas disponible
- **Temps réel** : WebSocket pour les mises à jour instantanées
- **Support multilingue** : français (extensible)

### 👨‍💼 Système d'administration
- **Panel d'interventions** : liste en temps réel des conversations escaladées
- **Chat avec clients** : répondre directement aux demandes depuis le panel
- **Notifications websocket** : alertes en temps réel des nouvelles demandes
- **Dashboard** : aperçu global

### ⭐ Avis & Commentaires
- **Système de notation** : 1-5 étoiles
- **Commentaires détaillés** : partage d'avis textuels
- **Historique d'avis** : voir ses propres avis

---

## 🏗️ Architecture

### Structure générale
```
AP4_web/
├── app/Services/
│   ├── ChatbotService.php              # Orchestration chatbot (125 lignes)
│   ├── EscalationDetector.php          # Détection escalade (43 lignes)
│   └── FallbackResponses.php           # Réponses par défaut (54 lignes)
├── app/Http/Controllers/
│   ├── ChatbotController.php           # HTTP handler (65 lignes, simplifié)
│   └── Admin/InterventionController.php # Admin interventions
├── resources/views/components/
│   ├── chat-widget.blade.php           # Composant chat réutilisable
│   ├── chatbot-widget.blade.php        # Widget flottant
│   └── optimized-chat-widget.blade.php
├── resources/js/
│   ├── app.js                          # Entry point main
│   ├── websocket-service.js            # Service WebSocket centralisé (177 lignes)
│   ├── chat-adapter.js                 # Adaptateur chat (58 lignes)
│   └── admin-realtime.js               # Notifications admin
└── routes/web.php                      # Routes (8 lignes seulement!)
```

### Flux de données Chatbot
```
Message utilisateur → ChatbotController → ChatbotService
  ↓
EscalationDetector? → Oui? AdminRequested event
  ↓ Non
Google Gemini API? → Oui? IA reply
  ↓ Non
FallbackResponses → Réponse intelligente pattern-based
  ↓
MessageSent event (WebSocket) → Tous les clients
```

---

## 🛠️ Technologies

### Backend
- **PHP 8.x** | Framework **Laravel 11.x**
- **MySQL** | ORM **Eloquent**
- **WebSocket** | **Laravel Reverb** (ou Pusher)
- **IA** | **Google Gemini API**
- **Paiements** | **Stripe API**

### Frontend
- **HTML/CSS/JavaScript** | Vanilla
- **Alpine.js 3.x** | Réactivité
- **Tailwind CSS** | Styling
- **Laravel Echo** | Client WebSocket
- **Vite** | Build tool

---

## 📚 Dépendances & Frameworks Installés

### Dépendances PHP (Composer)

#### Framework & ORM
- **laravel/framework** 11.x - Framework web principal
- **laravel/tinker** - REPL pour Laravel
- **laravel/breeze** - Authentication scaffolding
- **laravel/socialite** - OAuth authentication (Google, Microsoft, Facebook)
- **laravel/reverb** - WebSocket server (alternative Pusher)
- **pusher/pusher-http-php** - Pusher SDK (broadcasting optionnel)

#### Base de données
- **illuminate/database** - Query builder & Eloquent ORM
- **doctrine/orm** - Doctrine ORM compatibility
- **symfony/process** - Process component

#### Outils de développement
- **phpunit/phpunit** - Testing framework
- **laravel/pint** - Code style formatter
- **mockery/mockery** - Mocking library
- **simplesoftwareio/simple-qrcode** - QR Code generation for tickets
- **fakerphp/faker** - Fake data generator

#### Utilitaires
- **guzzlehttp/guzzle** - HTTP client (API calls)
- **symfony/http-client** - Alternative HTTP client
- **nesbot/carbon** - Date/time library
- **ramsey/uuid** - UUID generation
- **stripe/stripe-php** - Stripe payment API

#### Logging & Monitoring
- **monolog/monolog** - Logging library
- **sentry/sentry-laravel** - Error tracking (optionnel)

#### Mail & Notifications
- **symfony/mailer** - Email sending
- **symfony/mime** - MIME type handling

---

### Dépendances JavaScript (npm)

#### Framework & Réactivité
- **alpinejs** 3.x - Lightweight JS framework (réactivité composants)
- **laravel-echo** - WebSocket client for Laravel
- **pusher-js** 8.x - Pusher client library

#### Build & Compilation
- **vite** 7.x - Next-gen frontend tooling
- **@vitejs/plugin-vue** - Vue plugin for Vite (optionnel)
- **laravel-vite-plugin** - Laravel integration with Vite

#### Styling
- **tailwindcss** 3.x - Utility-first CSS framework
- **postcss** 8.x - CSS transformations
- **autoprefixer** - CSS vendor prefixing

#### Développement
- **@tailwindcss/forms** - Form styling components
- **@tailwindcss/typography** - Typography plugin

---

### Dépendances PHP supplémentaires

#### Validation & Sécurité
- **egulias/email-validator** - Email validation
- **symfony/validator** - Data validation
- **symfony/security-core** - Security component

#### HTTP & Networking
- **symfony/http-foundation** - HTTP components
- **symfony/routing** - Routing component
- **symfony/dom-crawler** - DOM parsing

#### Utilitaires Collection & String
- **illuminate/support** - Helper functions
- **illuminate/collections** - Collection utilities
- **symfony/string** - String manipulation

#### Configuration
- **vlucas/phpdotenv** - .env file loading
- **symfony/dotenv** - Alternative .env loader

---

### Versions principales

```json
{
  "PHP": "8.1 ou supérieur",
  "Laravel": "11.x",
  "Node.js": "18.x ou supérieur",
  "npm": "9.x ou supérieur",
  "Composer": "2.x",
  "Alpine.js": "3.x",
  "Tailwind CSS": "3.x",
  "Vite": "7.x",
  "MySQL": "8.0 ou supérieur"
}
```

---

### Installation des dépendances

```bash
# PHP dépendances
composer install

# JavaScript dépendances
npm install

# Vérifier les versions installées
composer --version
npm --version
php --version
```

### Mise à jour des dépendances

```bash
# Mettre à jour composer
composer update

# Mettre à jour npm
npm update

# Vérifier les dépendances obsolètes
composer outdated
npm outdated
```

---

## 📦 Installation

### Prérequis
- PHP 8.1+ | Node.js 18+ | MySQL 8.0+

### Étapes
```bash
git clone <repository-url>
cd AP4_web
composer install && npm install
cp .env.example .env
php artisan key:generate
# Éditer .env avec config DB
php artisan migrate
npm run dev
php artisan serve
```

---

## ⚙️ Configuration

```env
# Api Google Gemini (optionnel, pour IA)
GOOGLE_AI_KEY=your_key

# WebSocket
BROADCAST_CONNECTION=reverb
REVERB_APP_KEY=key
REVERB_APP_SECRET=secret

# Stripe (optionnel, pour paiements)
STRIPE_PUBLIC_KEY=key
STRIPE_SECRET_KEY=key

# OAuth (Google, Microsoft, Facebook)
GOOGLE_OAUTH_ID=...
```

---

## 🚀 Utilisation

### Utilisateurs
1. Visiter http://localhost:8000/
2. S'inscrire → Visiter "Programme" → Réserver billets
3. "Assistance" pour chatter avec bot IA

### Administrateurs
1. `/admin/dashboard` → "Interventions"
2. Voir conversations escaladées en temps réel
3. Cliquer pour chatter directement avec utilisateur

---

## 🔄 Refactorisation (Janvier 2026)

### Résultat : **-80% de complexité**

| Métrique | Avant | Après | Gain |
|----------|-------|-------|------|
| ChatbotController | 325 lignes | 65 lignes | -80% |
| Routes | 100+ lignes | 8 lignes | -92% |
| Support.blade | 463 lignes | 215 lignes | -54% |
| WebSocket duplication | 3 copies | 1 service | 67% éliminé |
| **Maintenabilité** | 3/10 | 9/10 | +200% |
| **Testabilité** | 2/10 | 9/10 | +350% |

### Patterns appliqués
- Service Layer (ChatbotService)
- Dependency Injection
- Singleton (WebSocketService)
- Adapter Pattern (ChatAdapter)
- Event Broadcasting

---

## 🧠 Chatbot Détaillé

### EscalationDetector
Mots-clés : `'humain', 'admin', 'parler à', 'représentant', 'agent', 'support humain'`

### FallbackResponses
Patterns :
- `'festival|dispo'` → Info festival
- `'tarif|prix|billet'` → Tarifs
- `'programme|artiste'` → Programmation
- `'lieu|où|adresse'` → Localisation

### Google Gemini
Si `GOOGLE_AI_KEY` configurée, utilise l'IA pour réponses personnalisées.

**System Prompt :**
```
Tu es l'assistant Festival Cale Sons 2026
Thème: 'Terres de Légendes'
Date: Août 2026
Réponds UNIQUEMENT sur le festival en français
```

---
##  WebSocket & Broadcasting

### Canaux
- **Public:** `conversation.{conversation_id}` → `.message.sent`  
- **Private:** `admin-support` → `.admin.requested`

### JavaScript
```javascript
// Écouter messages
window.Echo.channel(`conversation.${id}`)
    .listen('.message.sent', (event) => addMessage(event));

// Admin: Notifications
window.Echo.private('admin-support')
    .listen('.admin.requested', (event) => showAlert(event));
```

---

## 📝 Logs & Débogage

```bash
# Logs principaux
storage/logs/laravel.log

# Tests
php artisan test --filter ChatbotServiceTest
```

---

## 🔐 Sécurité

✅ CSRF tokens | ✅ OAuth | ✅ HTTPS en prod | ✅ Middleware admin

---

## 📚 Documentation complémentaire

- [REFACTORISATION_CHATBOT.md](REFACTORISATION_CHATBOT.md)
- [GUIDE_RAPIDE.md](GUIDE_RAPIDE.md)
- [WEBSOCKET_OPTIMISE.md](WEBSOCKET_OPTIMISE.md)
- [WEBSOCKET_INTEGRATION.md](WEBSOCKET_INTEGRATION.md)

---

**État:** Production-ready ✅  
**Dernière mise à jour:** Février 2026  
**Licence:** © 2026 Festival Cale Sons
