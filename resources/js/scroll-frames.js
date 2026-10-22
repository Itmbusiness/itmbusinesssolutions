/** Scroll through the registration-to-compliance image sequence. */
function initScrollFrames() {
    const sequence = document.querySelector('[data-scroll-frames]');
    const canvas = sequence?.querySelector('canvas');
    const context = canvas?.getContext('2d');

    if (!sequence || !context) {
        return;
    }

    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const saveData = navigator.connection?.saveData ?? false;
    const frameCount = Number(sequence.dataset.frameCount);
    const frameTemplate = sequence.dataset.frameTemplate;
    const frames = new Map();
    const pending = new Set();
    let visible = !('IntersectionObserver' in window);
    let requestedFrame = 1;
    let drawnFrame = 0;
    let animationFrame = 0;

    if (!Number.isInteger(frameCount) || frameCount < 2 || !frameTemplate) {
        return;
    }

    const enabled = () => !reducedMotion.matches && !saveData;
    const frameUrl = (index) => frameTemplate.replace(/001\.jpg$/, `${String(index).padStart(3, '0')}.jpg`);

    const draw = (image, index) => {
        const bounds = canvas.getBoundingClientRect();
        // The source is 1920×1080; a larger canvas only spends memory.
        const pixelRatio = Math.min(window.devicePixelRatio || 1, 2, 1920 / bounds.width, 1080 / bounds.height);
        const width = Math.round(bounds.width * pixelRatio);
        const height = Math.round(bounds.height * pixelRatio);

        if (!width || !height) {
            return;
        }

        if (canvas.width !== width || canvas.height !== height) {
            canvas.width = width;
            canvas.height = height;
        }

        // Match the still image's object-fit: cover and right-biased focal point.
        const sourceWidth = Math.min(image.naturalWidth, image.naturalHeight * width / height);
        const sourceHeight = Math.min(image.naturalHeight, image.naturalWidth * height / width);
        const sourceX = (image.naturalWidth - sourceWidth) * 0.75;
        const sourceY = (image.naturalHeight - sourceHeight) * 0.5;

        context.drawImage(image, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, width, height);
        drawnFrame = index;
        canvas.classList.add('is-ready');
    };

    const loadFrame = (index) => {
        if (frames.has(index) || pending.has(index) || pending.size >= 3) {
            return;
        }

        pending.add(index);
        const image = new Image();
        image.decoding = 'async';
        image.onload = () => {
            pending.delete(index);
            frames.delete(index);
            frames.set(index, image);

            // Retain nearby decoded frames while keeping memory bounded.
            while (frames.size > 6) {
                frames.delete(frames.keys().next().value);
            }

            if (enabled() && visible && index === requestedFrame) {
                draw(image, index);
            }

            schedule();
        };
        image.onerror = () => pending.delete(index);
        image.src = frameUrl(index);
    };

    const update = () => {
        animationFrame = 0;

        if (!enabled() || !visible) {
            return;
        }

        // The scene fills the section, so its own bounds cover the full story.
        const bounds = sequence.getBoundingClientRect();
        const progress = Math.min(1, Math.max(0, (window.innerHeight - bounds.top) / (window.innerHeight + bounds.height)));
        const step = window.innerWidth < 768 ? 4 : 1;
        const rawFrame = 1 + progress * (frameCount - 1);
        requestedFrame = Math.min(frameCount, 1 + Math.round((rawFrame - 1) / step) * step);

        if (frames.has(requestedFrame)) {
            if (drawnFrame !== requestedFrame) {
                draw(frames.get(requestedFrame), requestedFrame);
            }
        } else {
            loadFrame(requestedFrame);
        }

        // One adjacent frame smooths normal scrolling without fetching the whole sequence.
        if (frames.has(requestedFrame)) {
            loadFrame(Math.min(frameCount, requestedFrame + step));
        }
    };

    function schedule() {
        if (!animationFrame && enabled() && visible) {
            animationFrame = requestAnimationFrame(update);
        }
    }

    if ('IntersectionObserver' in window) {
        new IntersectionObserver(([entry]) => {
            visible = entry.isIntersecting;
            schedule();
        }, { rootMargin: '200px 0px' }).observe(sequence);
    }

    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', () => {
        drawnFrame = 0;
        schedule();
    }, { passive: true });
    reducedMotion.addEventListener('change', () => {
        canvas.classList.toggle('is-ready', !reducedMotion.matches && drawnFrame > 0);
        schedule();
    });

    schedule();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScrollFrames, { once: true });
} else {
    initScrollFrames();
}
