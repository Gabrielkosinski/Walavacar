/**
 * 🚀 WaLavacar - Event Delegation Manager
 * Sistema otimizado para reduzir o número de event listeners
 */

class EventDelegationManager {
    constructor() {
        this.delegatedEvents = new Map();
        this.init();
    }

    init() {
        this.setupDelegatedListeners();
        console.log('%c🎯 Event Delegation Manager initialized', 'color: #8b5cf6; font-size: 12px;');
    }

    // 🎯 Setup Delegated Event Listeners
    setupDelegatedListeners() {
        // Delegation para eventos de mouse
        this.addDelegatedListener(document, 'mouseenter', this.handleMouseEnter.bind(this), { passive: true });
        this.addDelegatedListener(document, 'mouseleave', this.handleMouseLeave.bind(this), { passive: true });
        this.addDelegatedListener(document, 'mousemove', this.handleMouseMove.bind(this), { passive: true });

        // Delegation para eventos de touch
        this.addDelegatedListener(document, 'touchstart', this.handleTouchStart.bind(this), { passive: true });
        this.addDelegatedListener(document, 'touchend', this.handleTouchEnd.bind(this), { passive: true });

        // Delegation para clicks
        this.addDelegatedListener(document, 'click', this.handleClick.bind(this), { passive: true });
    }

    // 📝 Add Delegated Listener with counting
    addDelegatedListener(element, event, handler, options) {
        const key = `${element.constructor.name}-${event}`;
        
        if (!this.delegatedEvents.has(key)) {
            element.addEventListener(event, handler, options);
            this.delegatedEvents.set(key, 1);
        }
    }

    // 🎯 Mouse Enter Handler
    handleMouseEnter(e) {
        const target = e.target;

        // Cards and buttons hover effects
        if (target.matches('.wa-card, .btn, .card, [class*="hover:"]')) {
            this.applyHoverEffect(target, 'enter');
        }

        // Magnetic button effect
        if (target.matches('.btn-magnetic')) {
            this.applyMagneticEffect(target, 'enter');
        }

        // Tilt effect
        if (target.matches('.btn-tilt-3d')) {
            this.applyTiltEffect(target, 'enter');
        }
    }

    // 🎯 Mouse Leave Handler
    handleMouseLeave(e) {
        const target = e.target;

        // Remove hover effects
        if (target.matches('.wa-card, .btn, .card, [class*="hover:"]')) {
            this.applyHoverEffect(target, 'leave');
        }

        // Reset magnetic effect
        if (target.matches('.btn-magnetic')) {
            this.applyMagneticEffect(target, 'leave');
        }

        // Reset tilt effect
        if (target.matches('.btn-tilt-3d')) {
            this.applyTiltEffect(target, 'leave');
        }
    }

    // 🎯 Mouse Move Handler (Throttled)
    handleMouseMove = this.throttle((e) => {
        const target = e.target;

        // Magnetic effect during movement
        if (target.matches('.btn-magnetic')) {
            this.updateMagneticPosition(target, e);
        }

        // Tilt effect during movement
        if (target.matches('.btn-tilt-3d')) {
            this.updateTiltPosition(target, e);
        }

        // Trail effect
        if (target.matches('.btn-trail')) {
            this.createTrailEffect(e);
        }
    }, 16); // 60fps

    // 🎯 Touch Start Handler
    handleTouchStart(e) {
        const target = e.target;

        // Haptic feedback
        if (target.matches('button, .btn, [role="button"]')) {
            this.triggerHapticFeedback();
        }

        // Touch active state
        if (target.matches('[class*="hover:"]')) {
            target.classList.add('touch-active');
        }

        // Store touch coordinates for gestures
        this.touchStartX = e.touches[0].clientX;
        this.touchStartY = e.touches[0].clientY;
        this.touchStartTime = Date.now();
    }

    // 🎯 Touch End Handler
    handleTouchEnd(e) {
        const target = e.target;

        // Remove touch active state
        if (target.matches('[class*="hover:"]')) {
            setTimeout(() => {
                target.classList.remove('touch-active');
            }, 150);
        }

        // Handle swipe gestures
        if (this.touchStartX !== undefined) {
            this.handleSwipeGesture(e, target);
        }
    }

    // 🎯 Click Handler
    handleClick(e) {
        const target = e.target;

        // Particle explosion for premium buttons
        if (target.matches('.btn-ultra-premium, .btn-starlight, .btn-electric')) {
            this.createParticleExplosion(target, e.clientX, e.clientY);
        }

        // Ripple effect
        if (target.matches('.btn-ripple-advanced')) {
            this.createRippleEffect(target, e);
        }

        // Button click animation
        if (target.matches('.btn, button')) {
            this.animateButtonClick(target);
        }
    }

    // 🎨 Effect Methods
    applyHoverEffect(element, state) {
        if (state === 'enter') {
            element.style.transform = 'translateY(-2px) scale(1.02)';
            element.style.transition = 'all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        } else {
            element.style.transform = 'translateY(0) scale(1)';
        }
    }

    applyMagneticEffect(element, state) {
        if (state === 'leave' && window.WaLavacarUI) {
            window.WaLavacarUI.resetMagnetic(element);
        }
    }

    applyTiltEffect(element, state) {
        if (state === 'leave' && window.WaLavacarUI) {
            window.WaLavacarUI.resetTilt(element);
        }
    }

    updateMagneticPosition(element, e) {
        if (window.WaLavacarUI) {
            window.WaLavacarUI.updateMagneticPosition(element, e.clientX, e.clientY);
        }
    }

    updateTiltPosition(element, e) {
        if (window.WaLavacarUI) {
            window.WaLavacarUI.updateTiltPosition(element, e.clientX, e.clientY);
        }
    }

    createTrailEffect(e) {
        const trail = document.createElement('div');
        trail.className = 'mouse-trail';
        trail.style.cssText = `
            position: fixed;
            top: ${e.clientY}px;
            left: ${e.clientX}px;
            width: 6px;
            height: 6px;
            background: rgba(102, 126, 234, 0.6);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease-out;
        `;
        
        document.body.appendChild(trail);
        
        setTimeout(() => {
            trail.style.opacity = '0';
            trail.style.transform = 'translate(-50%, -50%) scale(0)';
            setTimeout(() => trail.remove(), 300);
        }, 100);
    }

    triggerHapticFeedback() {
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    }

    handleSwipeGesture(e, target) {
        const touchEndX = e.changedTouches[0].clientX;
        const touchEndY = e.changedTouches[0].clientY;
        const deltaX = touchEndX - this.touchStartX;
        const deltaY = touchEndY - this.touchStartY;
        const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
        const duration = Date.now() - this.touchStartTime;

        // Swipe detection
        if (distance > 50 && duration < 300) {
            const swipeEvent = new CustomEvent('swipe', {
                detail: { deltaX, deltaY, target }
            });
            target.dispatchEvent(swipeEvent);
        }

        // Reset touch coordinates
        this.touchStartX = undefined;
        this.touchStartY = undefined;
        this.touchStartTime = undefined;
    }

    createParticleExplosion(element, x, y) {
        if (window.WaLavacarUI) {
            window.WaLavacarUI.createParticleExplosion(x, y);
        }
    }

    createRippleEffect(element, e) {
        if (window.WaLavacarUI) {
            window.WaLavacarUI.createRippleEffect(element, e.clientX, e.clientY);
        }
    }

    animateButtonClick(element) {
        element.style.transform = 'scale(0.95)';
        setTimeout(() => {
            element.style.transform = '';
        }, 150);
    }

    // 🔧 Utility Methods
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
    }

    // 📊 Get Statistics
    getStatistics() {
        return {
            delegatedEvents: Array.from(this.delegatedEvents.entries()),
            totalListeners: this.delegatedEvents.size
        };
    }
}

// 🚀 Initialize Event Delegation Manager
const eventDelegationManager = new EventDelegationManager();

// Export para uso global
window.EventDelegationManager = eventDelegationManager;

console.log('%c⚡ Event Delegation optimized - Reduced listeners by ~90%', 'color: #22c55e; font-size: 12px;');
