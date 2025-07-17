/* 🚀 WaLavacar - Advanced UI Interactions - Event Delegation Optimized */

class WaLavacarUI {
    constructor() {
        this.init();
    }

    init() {
        this.setupCustomStyles();
        this.setupMobileOptimizations();
        this.setupPerformanceOptimizations();
        console.log('🚀 WaLavacar Advanced UI - Event Delegation Mode');
    }

    // 🎨 Custom Styles (não baseados em event listeners)
    setupCustomStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .btn-ultra-premium:hover { 
                box-shadow: 0 0 30px rgba(102, 126, 234, 0.6); 
                transition: all 0.3s ease;
            }
            .btn-magnetic { 
                transition: transform 0.1s ease-out; 
            }
            .btn-tilt-3d { 
                perspective: 1000px; 
                transition: transform 0.1s ease-out; 
            }
            .btn-trail {
                cursor: pointer;
                position: relative;
            }
            .btn-ripple-advanced {
                position: relative;
                overflow: hidden;
            }
            .touch-active {
                transform: scale(0.95);
                transition: transform 0.1s ease;
            }
            @keyframes particle-explosion {
                0% { transform: scale(1) translate(0, 0); opacity: 1; }
                100% { transform: scale(0) translate(var(--tx), var(--ty)); opacity: 0; }
            }
            .ui-particle {
                animation: particle-explosion 0.6s ease-out forwards;
            }
        `;
        document.head.appendChild(style);
    }

    // 📱 Otimizações Mobile
    setupMobileOptimizations() {
        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        
        if (isMobile) {
            // Reduzir animações em dispositivos móveis
            document.documentElement.style.setProperty('--animation-duration', '200ms');
            
            // Otimizar performance em mobile
            document.querySelectorAll('.btn, .card, .modal').forEach(el => {
                el.style.willChange = 'transform, opacity';
            });
        }
    }

    // ⚡ Otimizações de Performance
    setupPerformanceOptimizations() {
        // Intersection Observer para elementos que entram/saem da viewport
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-viewport');
                    } else {
                        entry.target.classList.remove('in-viewport');
                    }
                });
            }, { threshold: 0.1 });

            // Observar elementos com animações
            document.querySelectorAll('.wa-card, .btn-premium').forEach(el => {
                observer.observe(el);
            });
        }

        // Respeitar preferência de movimento reduzido
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.style.setProperty('--animation-duration', '0ms');
            console.log('🔒 Reduced motion preference detected');
        }
    }

    // 🎯 Utility Methods (chamados pelo EventDelegationManager)
    static createParticleExplosion(x, y, colors = ['#667eea', '#764ba2', '#f093fb', '#fbbf24', '#ec4899']) {
        const particleCount = 8; // Reduzido para performance
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'ui-particle';
            
            const color = colors[Math.floor(Math.random() * colors.length)];
            const size = Math.random() * 4 + 2;
            const angle = (i / particleCount) * Math.PI * 2;
            const velocity = Math.random() * 80 + 40;
            
            const tx = Math.cos(angle) * velocity;
            const ty = Math.sin(angle) * velocity;
            
            particle.style.cssText = `
                position: fixed;
                top: ${y}px;
                left: ${x}px;
                width: ${size}px;
                height: ${size}px;
                background: ${color};
                border-radius: 50%;
                pointer-events: none;
                z-index: 10000;
                box-shadow: 0 0 8px ${color};
                --tx: ${tx}px;
                --ty: ${ty}px;
            `;
            
            document.body.appendChild(particle);
            
            // Remove após animação
            setTimeout(() => particle.remove(), 600);
        }
    }

    static createRippleEffect(element, x, y) {
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const rippleX = x - rect.left - size / 2;
        const rippleY = y - rect.top - size / 2;
        
        const ripple = document.createElement('span');
        ripple.style.cssText = `
            position: absolute;
            top: ${rippleY}px;
            left: ${rippleX}px;
            width: ${size}px;
            height: ${size}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: scale(0);
            pointer-events: none;
            z-index: 1;
        `;
        
        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        element.appendChild(ripple);
        
        const animation = ripple.animate([
            { transform: 'scale(0)', opacity: 1 },
            { transform: 'scale(2)', opacity: 0 }
        ], { duration: 600, easing: 'ease-out' });
        
        animation.onfinish = () => ripple.remove();
    }

    static updateMagneticPosition(element, x, y) {
        const rect = element.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        const deltaX = x - centerX;
        const deltaY = y - centerY;
        
        const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
        const maxDistance = 100;
        
        if (distance < maxDistance) {
            const strength = (maxDistance - distance) / maxDistance;
            const moveX = deltaX * strength * 0.3;
            const moveY = deltaY * strength * 0.3;
            
            element.style.transform = `translate(${moveX}px, ${moveY}px)`;
        }
    }

    static updateTiltPosition(element, x, y) {
        const rect = element.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        const deltaX = x - centerX;
        const deltaY = y - centerY;
        
        const rotateX = (deltaY / rect.height) * -20;
        const rotateY = (deltaX / rect.width) * 20;
        
        element.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
    }

    static resetTransform(element) {
        element.style.transform = '';
    }

    static resetMagnetic(element) {
        element.style.transform = 'translate(0, 0)';
    }

    static resetTilt(element) {
        element.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
    }
}

// 🚀 Initialize Advanced UI
const waLavacarUI = new WaLavacarUI();

// Export para uso pelo EventDelegationManager
window.WaLavacarUI = WaLavacarUI;

console.log('%c⚡ Advanced UI optimized with Event Delegation', 'color: #22c55e; font-size: 12px;');
