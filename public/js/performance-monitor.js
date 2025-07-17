/**
 * 🚀 WaLavacar - Performance Monitor
 * Sistema para monitorar e otimizar performance em tempo real
 */

class PerformanceMonitor {
    constructor() {
        this.init();
    }

    init() {
        this.monitorEventListeners();
        this.optimizeScrollEvents();
        this.preventMemoryLeaks();
        this.setupPerformanceObserver();
    }

    // 📊 Monitor Event Listeners Performance
    monitorEventListeners() {
        const originalAddEventListener = EventTarget.prototype.addEventListener;
        const listenerCount = new Map();

        EventTarget.prototype.addEventListener = function(type, listener, options) {
            // Garantir que listeners de scroll/touch sejam passivos
            if (['scroll', 'touchstart', 'touchmove', 'touchend', 'mousemove', 'mousewheel', 'wheel'].includes(type)) {
                if (typeof options === 'boolean') {
                    options = { capture: options, passive: true };
                } else if (!options) {
                    options = { passive: true };
                } else if (typeof options === 'object' && options.passive === undefined) {
                    options.passive = true;
                }
            }

            // Contar listeners para debug
            const key = `${this.constructor.name || 'Unknown'}-${type}`;
            listenerCount.set(key, (listenerCount.get(key) || 0) + 1);

            return originalAddEventListener.call(this, type, listener, options);
        };

        // Debug console
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            setTimeout(() => {
                console.log('%c📊 Event Listeners Report:', 'color: #10b981; font-weight: bold;');
                listenerCount.forEach((count, key) => {
                    console.log(`  ${key}: ${count} listeners`);
                });
            }, 2000);
        }
    }

    // ⚡ Optimize Scroll Events
    optimizeScrollEvents() {
        let scrollTimeout;
        const scrollHandlers = new Set();

        window.addEventListener('scroll', () => {
            if (scrollTimeout) return;
            
            scrollTimeout = requestAnimationFrame(() => {
                scrollHandlers.forEach(handler => handler());
                scrollTimeout = null;
            });
        }, { passive: true });

        // API pública para registrar scroll handlers otimizados
        window.registerScrollHandler = (handler) => {
            scrollHandlers.add(handler);
            return () => scrollHandlers.delete(handler);
        };
    }

    // 🧹 Prevent Memory Leaks
    preventMemoryLeaks() {
        const weakRefs = new Set();

        // Cleanup ao descarregar página
        window.addEventListener('beforeunload', () => {
            weakRefs.forEach(ref => {
                const obj = ref.deref();
                if (obj && obj.cleanup) {
                    obj.cleanup();
                }
            });
            weakRefs.clear();
        }, { once: true });

        // API para registrar objetos que precisam de cleanup
        window.registerForCleanup = (obj) => {
            if (typeof WeakRef !== 'undefined') {
                weakRefs.add(new WeakRef(obj));
            }
        };
    }

    // 🔍 Setup Performance Observer
    setupPerformanceObserver() {
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    // Log performance issues in development (ajustado threshold)
                    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                        if (entry.duration > 500) { // Aumentado de 50ms para 500ms
                            console.warn(`⚠️ Slow operation detected: ${entry.name} took ${entry.duration.toFixed(2)}ms`);
                        } else if (entry.duration > 250) { // Info para operações moderadamente lentas
                            console.info(`ℹ️ Moderate load time: ${entry.name} took ${entry.duration.toFixed(2)}ms`);
                        }
                    }
                });
            });

            try {
                observer.observe({ entryTypes: ['measure', 'navigation', 'resource'] });
            } catch (e) {
                console.warn('Performance Observer not fully supported:', e);
            }
        }
    }

    // 🎯 Utility Methods
    static measurePerformance(name, fn) {
        performance.mark(`${name}-start`);
        const result = fn();
        performance.mark(`${name}-end`);
        performance.measure(name, `${name}-start`, `${name}-end`);
        return result;
    }

    static debounce(func, wait) {
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

    static throttle(func, limit) {
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
    }
}

// 🚀 Initialize Performance Monitor
const performanceMonitor = new PerformanceMonitor();

// Export para uso global
window.PerformanceMonitor = PerformanceMonitor;

console.log('%c🔧 Performance Monitor initialized', 'color: #8b5cf6; font-size: 12px;');
console.log('%c✅ All event listeners optimized for mobile performance', 'color: #22c55e; font-size: 10px;');
