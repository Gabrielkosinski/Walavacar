/**
 * 🔐 WaLavacar - CSRF Token Manager
 * Sistema para gerenciar e renovar tokens CSRF automaticamente
 */

class CSRFManager {
    constructor() {
        this.token = null;
        this.refreshInterval = null;
        this.init();
    }

    init() {
        this.token = this.getToken();
        this.setupAutoRefresh();
        this.setupRequestInterceptor();
        console.log('🔐 CSRF Manager inicializado');
    }

    // 🎯 Obter token CSRF atual
    getToken() {
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        return metaToken ? metaToken.getAttribute('content') : null;
    }

    // 🔄 Renovar token CSRF
    async refreshToken() {
        try {
            const response = await fetch('/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateToken(data.token);
                console.log('🔄 Token CSRF renovado');
                return true;
            }
        } catch (error) {
            console.error('❌ Erro ao renovar token CSRF:', error);
            return false;
        }
        return false;
    }

    // 📝 Atualizar token em todos os lugares
    updateToken(newToken) {
        this.token = newToken;
        
        // Atualizar meta tag
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) {
            metaToken.setAttribute('content', newToken);
        }

        // Atualizar inputs CSRF
        document.querySelectorAll('input[name="_token"]').forEach(input => {
            input.value = newToken;
        });

        // Trigger evento customizado
        window.dispatchEvent(new CustomEvent('csrf-token-updated', {
            detail: { token: newToken }
        }));
    }

    // ⏰ Configurar renovação automática
    setupAutoRefresh() {
        // Renovar token a cada 470 minutos (10 min antes do vencimento de 480 min = 8 horas)
        const refreshTime = 470 * 60 * 1000; // 7h50min em milissegundos
        
        this.refreshInterval = setInterval(() => {
            console.log('🔄 Renovação automática de token CSRF iniciada...');
            this.refreshToken();
        }, refreshTime);
        
        console.log(`⏰ Auto-renovação configurada para ${Math.round(refreshTime / (60 * 1000))} minutos`);
    }

    // 🌐 Interceptar requisições para adicionar token
    setupRequestInterceptor() {
        // Interceptar fetch requests
        const originalFetch = window.fetch;
        window.fetch = async (url, options = {}) => {
            // Adicionar token CSRF se não estiver presente
            if (options.method && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(options.method.toUpperCase())) {
                options.headers = options.headers || {};
                
                if (!options.headers['X-CSRF-TOKEN']) {
                    options.headers['X-CSRF-TOKEN'] = this.getToken();
                }
            }

            try {
                const response = await originalFetch(url, options);
                
                // Se receber 419, tentar renovar token e repetir requisição
                if (response.status === 419) {
                    console.log('🔄 Token expirado, renovando...');
                    
                    const renewed = await this.refreshToken();
                    if (renewed && options.headers) {
                        options.headers['X-CSRF-TOKEN'] = this.getToken();
                        return await originalFetch(url, options);
                    }
                }
                
                return response;
            } catch (error) {
                console.error('❌ Erro na requisição:', error);
                throw error;
            }
        };
    }

    // 🎯 Método público para obter token atual
    getCurrentToken() {
        return this.getToken();
    }

    // 🛠️ Método público para forçar renovação
    async forceRefresh() {
        return await this.refreshToken();
    }

    // 🧹 Cleanup
    destroy() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
    }
}

// 🚀 Inicializar CSRF Manager
const csrfManager = new CSRFManager();

// Export para uso global
window.CSRFManager = csrfManager;

// 🔧 Função helper para pegar token (compatibilidade)
window.getCSRFToken = () => csrfManager.getCurrentToken();

console.log('%c🔐 CSRF Manager carregado - Auto-renovação ativa', 'color: #10b981; font-size: 12px;');
