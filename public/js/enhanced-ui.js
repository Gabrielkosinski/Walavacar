/**
 * 🚀 WaLavacar - Enhanced UI Components
 * Bibliotecas e funções prontas para melhorar a experiência do usuário
 * Performance optimized with passive event listeners
 */

// ⚡ Performance Optimizations
const performanceOptimizations = {
    // Prevenção de memory leaks
    cleanup() {
        // Limpar event listeners ao descarregar a página
        window.addEventListener('beforeunload', () => {
            document.removeEventListener('touchstart', this.pullToRefreshHandlers.start);
            document.removeEventListener('touchmove', this.pullToRefreshHandlers.move);
            document.removeEventListener('touchend', this.pullToRefreshHandlers.end);
        }, { once: true });
    },

    // Throttle para eventos de alta frequência
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    },

    // Debounce para otimizar performance
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// 🎨 Enhanced Toast Notifications usando SweetAlert2
window.Toast = {
    success(message, title = 'Sucesso!') {
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#f0fdf4',
            color: '#166534',
            iconColor: '#22c55e',
            customClass: {
                popup: 'animate__animated animate__slideInRight'
            }
        });
    },

    error(message, title = 'Erro!') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: '#fef2f2',
            color: '#dc2626',
            iconColor: '#ef4444',
            customClass: {
                popup: 'animate__animated animate__slideInRight'
            }
        });
    },

    warning(message, title = 'Atenção!') {
        Swal.fire({
            icon: 'warning',
            title: title,
            text: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            background: '#fffbeb',
            color: '#d97706',
            iconColor: '#f59e0b',
            customClass: {
                popup: 'animate__animated animate__slideInRight'
            }
        });
    },

    info(message, title = 'Info') {
        Swal.fire({
            icon: 'info',
            title: title,
            text: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#eff6ff',
            color: '#2563eb',
            iconColor: '#3b82f6',
            customClass: {
                popup: 'animate__animated animate__slideInRight'
            }
        });
    }
};

// 💫 Enhanced Modal Dialogs
window.Modal = {
    confirm(options = {}) {
        return Swal.fire({
            title: options.title || 'Confirmar ação?',
            text: options.text || 'Esta ação não pode ser desfeita.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#ef4444',
            confirmButtonText: options.confirmText || 'Sim, confirmar!',
            cancelButtonText: options.cancelText || 'Cancelar',
            reverseButtons: true,
            background: '#ffffff',
            customClass: {
                popup: 'animate__animated animate__zoomIn',
                confirmButton: 'btn-premium',
                cancelButton: 'bg-gray-500 hover:bg-gray-600'
            }
        });
    },

    paymentMethod() {
        return Swal.fire({
            title: '💰 Forma de Pagamento',
            text: 'Selecione como o cliente vai pagar:',
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            showConfirmButton: true,
            confirmButtonText: '💵 Dinheiro',
            denyButtonText: '💳 PIX',
            cancelButtonText: '💳 Cartão',
            confirmButtonColor: '#22c55e',
            denyButtonColor: '#3b82f6',
            cancelButtonColor: '#f59e0b',
            reverseButtons: true,
            customClass: {
                popup: 'animate__animated animate__bounceIn'
            }
        }).then((result) => {
            if (result.isConfirmed) return 'dinheiro';
            if (result.isDenied) return 'pix';
            if (result.dismiss === Swal.DismissReason.cancel) return 'cartao';
            return null;
        });
    },

    loading(message = 'Processando...') {
        Swal.fire({
            title: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            customClass: {
                popup: 'animate__animated animate__fadeIn'
            },
            didOpen: () => {
                Swal.showLoading();
            }
        });
    },

    close() {
        Swal.close();
    }
};

// 🎯 Enhanced Form Validation
window.FormValidator = {
    validateRequired(fields) {
        const errors = [];
        
        fields.forEach(field => {
            const element = document.querySelector(`[name="${field.name}"]`);
            if (!element || !element.value.trim()) {
                errors.push(field.label || field.name);
                this.highlightError(element);
            } else {
                this.clearError(element);
            }
        });

        return errors;
    },

    highlightError(element) {
        if (element) {
            element.classList.add('border-red-500', 'animate__animated', 'animate__shakeX');
            setTimeout(() => {
                element.classList.remove('animate__shakeX');
            }, 1000);
        }
    },

    clearError(element) {
        if (element) {
            element.classList.remove('border-red-500');
            element.classList.add('border-green-500');
            setTimeout(() => {
                element.classList.remove('border-green-500');
            }, 2000);
        }
    }
};

// 📱 Enhanced Mobile Interactions - Performance Optimized
window.MobileUI = {
    pullToRefreshHandlers: {},
    
    addPullToRefresh() {
        let startY = 0;
        let currentY = 0;
        let refreshThreshold = 100;
        
        // Usar métodos nomeados para poder remover listeners se necessário
        this.pullToRefreshHandlers.start = (e) => {
            startY = e.touches[0].clientY;
        };
        
        this.pullToRefreshHandlers.move = performanceOptimizations.throttle((e) => {
            currentY = e.touches[0].clientY;
            const diff = currentY - startY;
            
            if (diff > refreshThreshold && window.scrollY === 0) {
                Toast.info('Solte para atualizar...', 'Pull to Refresh');
            }
        }, 100); // Throttle a 100ms para melhor performance
        
        this.pullToRefreshHandlers.end = (e) => {
            const diff = currentY - startY;
            if (diff > refreshThreshold && window.scrollY === 0) {
                location.reload();
            }
        };
        
        document.addEventListener('touchstart', this.pullToRefreshHandlers.start, { passive: true });
        document.addEventListener('touchmove', this.pullToRefreshHandlers.move, { passive: true });
        document.addEventListener('touchend', this.pullToRefreshHandlers.end, { passive: true });
    },

    addHapticFeedback() {
        // Event delegation é tratado pelo EventDelegationManager
        console.log('📳 Haptic feedback delegated to EventDelegationManager');
    },

    addSwipeGestures(element, callbacks = {}) {
        let startX = 0;
        let startY = 0;
        
        const touchStartHandler = (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        };
        
        const touchEndHandler = (e) => {
            const endX = e.changedTouches[0].clientX;
            const endY = e.changedTouches[0].clientY;
            const diffX = startX - endX;
            const diffY = startY - endY;

            if (Math.abs(diffX) > Math.abs(diffY)) {
                if (diffX > 50 && callbacks.swipeLeft) {
                    callbacks.swipeLeft();
                } else if (diffX < -50 && callbacks.swipeRight) {
                    callbacks.swipeRight();
                }
            } else {
                if (diffY > 50 && callbacks.swipeUp) {
                    callbacks.swipeUp();
                } else if (diffY < -50 && callbacks.swipeDown) {
                    callbacks.swipeDown();
                }
            }
        };
        
        element.addEventListener('touchstart', touchStartHandler, { passive: true });
        element.addEventListener('touchend', touchEndHandler, { passive: true });
    }
};

// ⚡ Enhanced Loading States
window.LoadingUI = {
    showButton(button, text = 'Carregando...') {
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `
            <div class="flex items-center justify-center">
                <iconify-icon icon="svg-spinners:3-dots-bounce" class="text-xl mr-2"></iconify-icon>
                ${text}
            </div>
        `;
        
        return () => {
            button.disabled = false;
            button.innerHTML = originalText;
        };
    },

    restoreButton(button, originalContent) {
        button.disabled = false;
        button.innerHTML = originalContent;
    },

    showPage(message = 'Carregando...') {
        const loader = document.createElement('div');
        loader.id = 'page-loader';
        loader.className = 'fixed inset-0 bg-white/90 backdrop-blur-sm z-50 flex items-center justify-center';
        loader.innerHTML = `
            <div class="text-center">
                <iconify-icon icon="svg-spinners:ring-resize" class="text-6xl text-blue-600 mb-4"></iconify-icon>
                <div class="text-lg font-semibold text-gray-700">${message}</div>
            </div>
        `;
        
        document.body.appendChild(loader);
        
        return () => {
            const loaderElement = document.getElementById('page-loader');
            if (loaderElement) {
                loaderElement.classList.add('animate__animated', 'animate__fadeOut');
                setTimeout(() => loaderElement.remove(), 300);
            }
        };
    }
};

// 🎨 Enhanced Card Animations - Event Delegation Optimized
window.CardAnimations = {
    addHoverEffects() {
        // Event delegation é tratado pelo EventDelegationManager
        console.log('🎨 Card hover effects delegated to EventDelegationManager');
    },

    addClickEffects() {
        // Event delegation é tratado pelo EventDelegationManager
        console.log('🎨 Card click effects delegated to EventDelegationManager');
    }
};

// 🚀 Initialize Enhanced UI on page load
document.addEventListener('DOMContentLoaded', () => {
    // Performance optimizations
    performanceOptimizations.cleanup();
    
    // Inicializar melhorias móveis
    MobileUI.addHapticFeedback();
    MobileUI.addPullToRefresh();
    
    // Inicializar animações
    CardAnimations.addHoverEffects();
    CardAnimations.addClickEffects();
    
    // Otimizações de performance para mobile
    if (/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
        // Reduzir animações em dispositivos móveis menos potentes
        document.documentElement.style.setProperty('--animation-duration', '200ms');
        
        // Usar will-change em elementos que serão animados
        document.querySelectorAll('.btn, .card, .modal').forEach(el => {
            el.style.willChange = 'transform, opacity';
        });
    }
    
    // Console de boas-vindas
    console.log('%c🚀 WaLavacar Enhanced UI Loaded!', 'color: #3b82f6; font-size: 16px; font-weight: bold;');
    console.log('%cFeatures: Toast, Modal, FormValidator, MobileUI, LoadingUI', 'color: #10b981; font-size: 12px;');
    console.log('%c⚡ Performance: Passive listeners, throttled events, memory optimized', 'color: #f59e0b; font-size: 10px;');
}, { passive: true });

// 📞 WhatsApp Integration Enhancement
window.WhatsApp = {
    send(number, message) {
        const cleanNumber = number.replace(/\D/g, '');
        const encodedMessage = encodeURIComponent(message);
        const url = `https://wa.me/55${cleanNumber}?text=${encodedMessage}`;
        window.open(url, '_blank');
        
        Toast.success('WhatsApp aberto!', 'Mensagem enviada');
    },

    sendTemplate(number, cliente, carro, valor) {
        const message = `
Olá ${cliente}! 

🚗 Seu carro está pronto para buscar!

📋 Detalhes:
• Veículo: ${carro}
• Valor: R$ ${valor}

Aguardamos você! 😊

*WaLavacar*
        `.trim();
        
        this.send(number, message);
    }
};
