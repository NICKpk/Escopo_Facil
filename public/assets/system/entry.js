(() => {
    const dialog = document.getElementById('system-entry');
    if (!dialog) return;
    const video = dialog.querySelector('[data-entry-video]');
    const fallback = dialog.querySelector('[data-entry-fallback]');
    const dashboardPath = new URL(dialog.dataset.dashboard, location.href).pathname;
    let destination;
    let timeout;
    let started = false;
    let leaving = false;

    function finish() {
        if (leaving || !destination) return;
        leaving = true;
        clearTimeout(timeout);
        location.assign(destination.href);
    }
    function showFallback() {
        video.hidden = true;
        fallback.hidden = false;
        clearTimeout(timeout);
        timeout = setTimeout(finish, 700);
    }
    function open(url) {
        const target = new URL(url, location.href);
        if (target.origin !== location.origin || !['http:', 'https:'].includes(target.protocol)) return false;
        if (started) return true;
        started = true;
        destination = target;
        // A separate top-layer dialog keeps the animation above the login blur.
        if (typeof dialog.showModal !== 'function') { finish(); return true; }
        document.documentElement.classList.add('system-entry-open');
        dialog.showModal();
        timeout = setTimeout(finish, 9000); // A stalled video must not trap a signed-in user.
        video.muted = true;
        video.play().catch(showFallback);
        return true;
    }
    video.addEventListener('ended', finish);
    video.addEventListener('error', showFallback);
    video.querySelector('source').addEventListener('error', showFallback);
    dialog.addEventListener('cancel', event => { event.preventDefault(); finish(); });
    // Authenticated links from the landing page use the same entry animation.
    document.addEventListener('click', event => {
        const link = event.target.closest('a');
        if (!link || event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || link.target === '_blank') return;
        const url = new URL(link.href, location.href);
        if (!link.hasAttribute('data-system-entry') && (url.origin !== location.origin || url.pathname !== dashboardPath)) return;
        if (open(link.href)) event.preventDefault();
    });
    window.addEventListener('pageshow', event => {
        if (!event.persisted) return;
        clearTimeout(timeout);
        video.pause();
        video.currentTime = 0;
        video.hidden = false;
        fallback.hidden = true;
        if (dialog.open) dialog.close();
        document.documentElement.classList.remove('system-entry-open');
        started = false;
        leaving = false;
    });
    window.EscopoSystemEntry = { open };
})();
