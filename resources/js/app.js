import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const q = (s, r = document) => r.querySelector(s);
    const qa = (s, r = document) => [...r.querySelectorAll(s)];

    const notif = q('[data-notif]');
    const notifPanel = q('[data-notif-panel]');
    notif?.querySelector('[data-notif-toggle]')?.addEventListener('click', (e) => {
        e.stopPropagation();
        notifPanel?.classList.toggle('hidden');
    });
    document.addEventListener('click', () => notifPanel?.classList.add('hidden'));

    const userPanel = q('[data-user-panel]');
    q('[data-user-toggle]')?.addEventListener('click', (e) => {
        e.stopPropagation();
        userPanel?.classList.toggle('hidden');
    });
    document.addEventListener('click', () => userPanel?.classList.add('hidden'));

    const searchPanel = q('[data-search-panel]');
    const searchInput = q('[data-search-input]');
    q('[data-search-toggle]')?.addEventListener('click', () => {
        searchPanel?.classList.toggle('hidden');
        if (!searchPanel?.classList.contains('hidden')) searchInput?.focus();
    });
    document.addEventListener('keydown', (e) => {
        if (e.altKey && e.key.toLowerCase() === 't') {
            e.preventDefault();
            notifPanel?.classList.toggle('hidden');
        }
        if (e.key === 'Escape') searchPanel?.classList.add('hidden');
    });

    let searchTimer;
    searchInput?.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => searchInput.form?.submit(), 450);
    });

    q('[data-mobile-toggle]')?.addEventListener('click', () => {
        q('[data-mobile-menu]')?.classList.toggle('hidden');
    });

    const carousel = q('[data-carousel]');
    if (carousel) {
        const slides = qa('[data-slide]', carousel);
        const dots = qa('[data-dot]', carousel);
        let idx = 0;
        let timer;
        const go = (n) => {
            idx = (n + slides.length) % slides.length;
            slides.forEach((s, i) => s.classList.toggle('hidden', i !== idx));
            dots.forEach((d, i) => d.classList.toggle('bg-gold', i === idx));
            dots.forEach((d, i) => d.classList.toggle('bg-white/30', i !== idx));
        };
        const auto = () => {
            clearInterval(timer);
            timer = setInterval(() => go(idx + 1), 6000);
        };
        carousel.querySelector('[data-prev]')?.addEventListener('click', () => { go(idx - 1); auto(); });
        carousel.querySelector('[data-next]')?.addEventListener('click', () => { go(idx + 1); auto(); });
        dots.forEach((d, i) => d.addEventListener('click', () => { go(i); auto(); }));
        go(0);
        auto();
    }
});
