/**
 * Motion for the public site.
 *
 * Deliberately dependency-free: reveals ride IntersectionObserver, and the
 * scroll-linked pieces (parallax, progress rail) are pure CSS where the browser
 * supports scroll-driven animations. Nothing here is required for the page to
 * be readable — `resources/css/app.css` only hides a reveal once `html.js` is
 * present, so a script failure leaves the content visible.
 */

const REDUCED_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)');

/** Mark every `[data-reveal]` visible without animating. */
function revealEverything() {
    document.querySelectorAll('[data-reveal]').forEach((el) => el.classList.add('is-in'));
}

/**
 * Reveal elements as they enter the viewport.
 *
 * `data-reveal-delay` staggers a single element; `data-reveal-stagger` on a
 * container staggers its `[data-reveal]` children by that many milliseconds.
 */
function initReveals() {
    const targets = document.querySelectorAll('[data-reveal]');

    if (!targets.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        revealEverything();

        return;
    }

    document.querySelectorAll('[data-reveal-stagger]').forEach((group) => {
        const step = Number(group.dataset.revealStagger) || 70;

        group.querySelectorAll('[data-reveal]').forEach((child, index) => {
            // An explicit per-element delay always wins over the group's.
            if (!child.dataset.revealDelay) {
                child.style.setProperty('--reveal-delay', `${index * step}ms`);
            }
        });
    });

    targets.forEach((el) => {
        if (el.dataset.revealDelay) {
            el.style.setProperty('--reveal-delay', `${el.dataset.revealDelay}ms`);
        }
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-in');
                // Reveals are one-way; stop paying for elements already shown.
                observer.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -12% 0px', threshold: 0.1 }
    );

    targets.forEach((el) => observer.observe(el));
}

/** Flip `data-stuck` on the header once the page has scrolled past its height. */
function initStickyHeader() {
    const header = document.querySelector('.site-header');

    if (!header) {
        return;
    }

    // A zero-height sentinel above the header beats a scroll listener: the
    // browser reports the crossing instead of us polling scrollY every frame.
    const sentinel = document.createElement('div');
    sentinel.setAttribute('aria-hidden', 'true');
    sentinel.style.cssText = 'position:absolute;top:0;height:1px;width:100%;pointer-events:none';
    document.body.prepend(sentinel);

    if (!('IntersectionObserver' in window)) {
        return;
    }

    new IntersectionObserver(
        ([entry]) => {
            header.dataset.stuck = String(!entry.isIntersecting);
        },
        { threshold: 0 }
    ).observe(sentinel);
}

/**
 * Count a number up when it scrolls into view.
 *
 * Reads its target from `data-count-to` and keeps whatever prefix/suffix the
 * markup already shows via `data-count-prefix` / `data-count-suffix`.
 */
function initCounters() {
    const counters = document.querySelectorAll('[data-count-to]');

    if (!counters.length || !('IntersectionObserver' in window)) {
        return;
    }

    const run = (el) => {
        const target = Number(el.dataset.countTo);
        const prefix = el.dataset.countPrefix ?? '';
        const suffix = el.dataset.countSuffix ?? '';

        if (!Number.isFinite(target)) {
            return;
        }

        const duration = 1400;
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            // easeOutExpo — fast out of the gate, settles rather than stops.
            const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);

            el.textContent = `${prefix}${Math.round(target * eased)}${suffix}`;

            if (progress < 1) {
                requestAnimationFrame(tick);
            }
        };

        requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                run(entry.target);
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.5 }
    );

    counters.forEach((el) => observer.observe(el));
}

function init() {
    if (REDUCED_MOTION.matches) {
        revealEverything();
    } else {
        initReveals();
        initCounters();
    }

    initStickyHeader();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
} else {
    init();
}

// Someone turning reduced motion on mid-visit should not be left with hidden
// content waiting on an observer that no longer matters.
REDUCED_MOTION.addEventListener('change', (event) => {
    if (event.matches) {
        revealEverything();
    }
});
