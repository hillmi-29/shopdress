import './bootstrap';

/**
 * Scroll reveal.
 *
 * The `reveal-ready` class is only added once JS runs, so content stays
 * visible when JavaScript is unavailable. Elements reveal once as they
 * enter the viewport. Call `window.ShopDressReveal.refresh()` after
 * injecting new markup (e.g. after AJAX product filtering).
 */
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

function revealElement(element) {
    element.classList.add('is-visible');
}

function initReveal(root = document) {
    const elements = root.querySelectorAll('.reveal:not(.is-visible)');

    if (!elements.length) {
        return;
    }

    if (!('IntersectionObserver' in window) || prefersReducedMotion.matches) {
        elements.forEach(revealElement);
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            revealElement(entry.target);
            observer.unobserve(entry.target);
        });
    }, {
        rootMargin: '0px 0px -8% 0px',
        threshold: 0.08,
    });

    elements.forEach((element) => observer.observe(element));
}

function bootReveal() {
    document.documentElement.classList.add('reveal-ready');
    initReveal();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootReveal, { once: true });
} else {
    bootReveal();
}

window.ShopDressReveal = { refresh: initReveal };

/**
 * Branded page transition loader.
 *
 * Laravel serves full pages, so a click starts a real navigation. We mark the
 * navigation in sessionStorage before leaving; the next document's inline head
 * script reads it and paints the loader immediately. The loader then fades out
 * once the new document is parsed, with a short minimum display time so it
 * never flashes aggressively on fast connections. A direct load (first visit,
 * refresh, or a new tab) is flagged as an entry so the intro stays a beat longer.
 */
const pageLoader = document.getElementById('page-loader');
const navigationKey = 'shopdress:navigating';
const downloadPattern = /\.(?:pdf|zip|rar|7z|csv|xlsx?|docx?|pptx?|jpe?g|png|gif|webp|avif|svg|mp4|mov|mp3|wav)(?:$|[?#])/i;
let loaderHideTimer = null;
let loaderSafetyTimer = null;

function showPageLoader() {
    if (!pageLoader) {
        return;
    }

    if (loaderHideTimer) {
        window.clearTimeout(loaderHideTimer);
        loaderHideTimer = null;
    }

    document.documentElement.classList.add('sd-navigating');
    pageLoader.setAttribute('aria-hidden', 'false');

    if (loaderSafetyTimer) {
        window.clearTimeout(loaderSafetyTimer);
    }

    loaderSafetyTimer = window.setTimeout(hidePageLoader, 12000);
}

function hidePageLoader() {
    if (!pageLoader) {
        return;
    }

    if (loaderHideTimer) {
        window.clearTimeout(loaderHideTimer);
        loaderHideTimer = null;
    }

    if (loaderSafetyTimer) {
        window.clearTimeout(loaderSafetyTimer);
        loaderSafetyTimer = null;
    }

    document.documentElement.classList.remove('sd-navigating', 'sd-entry');
    pageLoader.setAttribute('aria-hidden', 'true');

    try {
        window.sessionStorage.removeItem(navigationKey);
    } catch (error) {
        // Storage can be unavailable; nothing else to clean up.
    }
}

function beginNavigation() {
    try {
        window.sessionStorage.setItem(navigationKey, '1');
    } catch (error) {
        // The overlay still works for this document without storage.
    }

    showPageLoader();
}

function isInternalUrl(url) {
    try {
        return new URL(url, window.location.href).origin === window.location.origin;
    } catch (error) {
        return false;
    }
}

function shouldSkipLink(link) {
    if (link.hasAttribute('download')) {
        return true;
    }

    const target = link.getAttribute('target');
    if (target && target !== '_self') {
        return true;
    }

    if (link.closest('[data-no-loader]')) {
        return true;
    }

    const href = link.getAttribute('href');
    if (!href || href.startsWith('#')) {
        return true;
    }

    if (/^(?:mailto:|tel:|sms:|javascript:|blob:|data:)/i.test(href)) {
        return true;
    }

    if (!isInternalUrl(href)) {
        return true;
    }

    const url = new URL(href, window.location.href);

    if (url.hash && url.pathname === window.location.pathname && url.search === window.location.search) {
        return true;
    }

    return downloadPattern.test(url.pathname);
}

if (pageLoader) {
    if (document.documentElement.classList.contains('sd-navigating')) {
        showPageLoader();
    }

    document.addEventListener('click', (event) => {
        if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        const target = event.target instanceof Element ? event.target : event.target?.parentElement;
        const link = target?.closest('a[href]');

        if (!link || shouldSkipLink(link)) {
            return;
        }

        beginNavigation();
    });

    document.addEventListener('submit', (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement) || event.defaultPrevented) {
            return;
        }

        const target = form.getAttribute('target');
        if (target && target !== '_self') {
            return;
        }

        if (form.closest('[data-no-loader]')) {
            return;
        }

        const method = (form.getAttribute('method') || 'get').toLowerCase();
        if (method === 'dialog') {
            return;
        }

        const action = form.getAttribute('action') || window.location.href;
        if (!isInternalUrl(action)) {
            return;
        }

        beginNavigation();
    });

    function finishPageLoad() {
        if (!document.documentElement.classList.contains('sd-navigating')) {
            return;
        }

        // Give the branded intro a beat on direct entry, but keep link
        // transitions snappy. The loader still fades out smoothly.
        const isEntry = document.documentElement.classList.contains('sd-entry');

        loaderHideTimer = window.setTimeout(hidePageLoader, isEntry ? 900 : 320);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', finishPageLoad, { once: true });
    } else {
        finishPageLoad();
    }

    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            hidePageLoader();
        }
    });

    window.ShopDressPageLoader = { show: showPageLoader, hide: hidePageLoader };
}

/**
 * Keep native scrolling calm for users who prefer reduced motion.
 */
prefersReducedMotion.addEventListener?.('change', (event) => {
    if (event.matches) {
        document.querySelectorAll('.reveal').forEach(revealElement);
    }
});
