(() => {
    const dialog = document.getElementById('legal-modal');
    if (!dialog || typeof dialog.showModal !== 'function') return;
    const content = dialog.querySelector('[data-legal-content]');
    const status = dialog.querySelector('[data-legal-status]');
    const scroll = dialog.querySelector('[data-modal-scroll]');
    let opener;
    let controller;
    let generation = 0;

    async function open(link) {
        const url = new URL(link.href, location.href);
        if (url.origin !== location.origin || !['http:', 'https:'].includes(url.protocol)) return;
        if (!dialog.open) opener = link;
        controller?.abort();
        controller = new AbortController();
        const current = ++generation;
        content.replaceChildren();
        status.textContent = 'Carregando documento…';
        status.hidden = false;
        scroll.scrollTop = 0;
        if (!dialog.open) dialog.showModal();
        document.documentElement.classList.add('legal-modal-open');
        try {
            const response = await fetch(url, { headers: { Accept: 'application/json' }, credentials: 'same-origin', signal: controller.signal });
            if (!response.ok) throw new Error('Não foi possível abrir o documento. Feche esta janela e tente novamente.');
            const document = await response.json();
            if (current !== generation || !dialog.open) return;
            content.innerHTML = document.html;
            dialog.setAttribute('aria-label', document.title);
            status.hidden = true;
            content.querySelector('h1')?.focus({ preventScroll: true });
        } catch (error) {
            if (error.name === 'AbortError' || current !== generation) return;
            status.textContent = error instanceof TypeError ? 'Não foi possível conectar. Tente novamente em instantes.' : error.message;
            status.focus({ preventScroll: true });
        }
    }
    document.addEventListener('click', event => {
        const link = event.target.closest('a[data-legal-document]');
        if (!link || event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || link.target === '_blank') return;
        const url = new URL(link.href, location.href);
        if (url.origin !== location.origin) return;
        event.preventDefault();
        open(link);
    });
    dialog.querySelector('[data-close-legal]').addEventListener('click', () => dialog.close());
    dialog.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;
        event.preventDefault();
        event.stopPropagation();
        dialog.close();
    });
    dialog.addEventListener('cancel', event => {
        event.preventDefault();
        dialog.close();
    });
    dialog.addEventListener('click', event => {
        const rect = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom)) dialog.close();
    });
    dialog.addEventListener('close', () => {
        if (dialog.open) return;
        generation++;
        controller?.abort();
        document.documentElement.classList.remove('legal-modal-open');
        opener?.focus({ preventScroll: true });
    });
    dialog.dataset.legalReady = 'true';
})();
