import './bootstrap';

import Alpine from 'alpinejs';
import { webSocketService } from './websocket-service.js';
import { ChatAdapter } from './chat-adapter.js';

window.Alpine = Alpine;
window.webSocketService = webSocketService;
window.ChatAdapter = ChatAdapter;

// Démarrer Alpine.js
Alpine.start();

// Initialiser le WebSocket au démarrage
webSocketService.init();
