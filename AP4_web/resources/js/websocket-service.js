/**
 * 📡 Service WebSocket centralisé
 * 
 * Gère toute la communication temps réel avec Laravel Echo
 * Responsabilités:
 * - Initialiser Echo
 * - S'abonner aux canaux
 * - Dispatcher les événements
 */

export class WebSocketService {
    constructor() {
        this.listeners = new Map();
        this.channels = new Map();
        this.maxRetries = 10;
        this.currentRetry = 0;
    }

    /**
     * Initialise le service WebSocket
     * Attend que window.Echo soit disponible
     */
    init() {
        if (!window.Echo) {
            if (this.currentRetry < this.maxRetries) {
                this.currentRetry++;
                setTimeout(() => this.init(), 1000);
            } else {
                console.error('❌ WebSocket indisponible après ' + this.maxRetries + ' tentatives');
            }
            return;
        }

        this.currentRetry = 0;
        console.log('✅ WebSocket connecté (Laravel Echo disponible)');
    }

    /**
     * S'abonne à un canal public
     * @param {string} channelName - Nom du canal (ex: 'conversation.conv_123')
     * @param {function} onSuccess - Callback quand Echo est prêt
     */
    subscribePublic(channelName, onSuccess = null) {
        this.ensureReady(() => {
            if (this.channels.has(channelName)) {
                console.warn(`⚠️ Déjà abonné à ${channelName}`);
                return;
            }

            const channel = window.Echo.channel(channelName);
            this.channels.set(channelName, channel);
            
            console.log(`✅ Abonné au canal public: ${channelName}`);
            
            if (onSuccess) onSuccess(channel);
        });
    }

    /**
     * S'abonne à un canal privé (nécessite authentification)
     * @param {string} channelName - Nom du canal (ex: 'admin-support')
     * @param {function} onSuccess - Callback quand OK
     */
    subscribePrivate(channelName, onSuccess = null) {
        this.ensureReady(() => {
            if (this.channels.has(channelName)) {
                console.warn(`⚠️ Déjà abonné à ${channelName}`);
                return;
            }

            const channel = window.Echo.private(channelName);
            this.channels.set(channelName, channel);
            
            console.log(`✅ Abonné au canal privé: ${channelName}`);
            
            if (onSuccess) onSuccess(channel);
        });
    }

    /**
     * Écoute un événement sur un canal
     * @param {string} channelName - Nom du canal
     * @param {string} eventName - Nom de l'événement (ex: 'message.sent')
     * @param {function} callback - Fonction appelée quand l'événement arrive
     */
    on(channelName, eventName, callback) {
        this.ensureReady(() => {
            let channel = this.channels.get(channelName);

            if (!channel) {
                console.warn(`⚠️ Canal ${channelName} non trouvé, enregistrement quand disponible`);
                this.subscribePublic(channelName, (ch) => {
                    ch.listen(`.${eventName}`, callback);
                });
            } else {
                channel.listen(`.${eventName}`, callback);
                console.log(`✅ Écouteur enregistré: ${channelName} → .${eventName}`);
            }
        });
    }

    /**
     * Écoute un événement ET enregistre un handler
     * @param {string} channelName
     * @param {string} eventName
     * @param {function} handler  
     */
    listen(channelName, eventName, handler) {
        const key = `${channelName}:${eventName}`;
        
        if (!this.listeners.has(key)) {
            this.listeners.set(key, []);
        }
        
        this.listeners.get(key).push(handler);
        this.on(channelName, eventName, handler);
    }

    /**
     * Émite un événement (appelle tous les handlers)
     * @param {string} channelName
     * @param {string} eventName
     * @param {object} data
     */
    emit(channelName, eventName, data) {
        const key = `${channelName}:${eventName}`;
        const handlers = this.listeners.get(key) || [];
        
        handlers.forEach(handler => {
            try {
                handler(data);
            } catch (error) {
                console.error(`❌ Erreur handler ${key}:`, error);
            }
        });
    }

    /**
     * Attend que Echo soit prêt avant d'exécuter une fonction
     */
    ensureReady(callback) {
        if (window.Echo) {
            callback();
        } else {
            setTimeout(() => this.ensureReady(callback), 100);
        }
    }

    /**
     * Se désabonne d'un canal
     * @param {string} channelName
     */
    unsubscribe(channelName) {
        if (this.channels.has(channelName)) {
            this.channels.delete(channelName);
            console.log(`✅ Désabonné du canal: ${channelName}`);
        }
    }
}

// Export une instance unique (singleton)
export const webSocketService = new WebSocketService();
webSocketService.init();
