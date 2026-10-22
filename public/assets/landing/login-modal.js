(() => {
    const dialog = document.querySelector('#login-modal');
    if (!dialog || typeof dialog.showModal !== 'function') return;
    const routes = JSON.parse(document.querySelector('#auth-modal-routes').textContent);
    const panels = [...dialog.querySelectorAll('[data-auth-panel]')];
    const error = dialog.querySelector('.login-modal-error');
    const status = dialog.querySelector('.auth-modal-status');
    const home = dialog.dataset.home;
    let opener;
    let activeScreen = 'login';
    let requestId = 0;
    let historyClosing = false;
    let continuingCheckout = false;

    function clearFeedback() {
        error.hidden = true;
        status.hidden = true;
        dialog.querySelectorAll('[data-error-for]').forEach(element => { element.hidden = true; element.textContent = ''; });
        dialog.querySelectorAll('[aria-invalid]').forEach(element => element.removeAttribute('aria-invalid'));
    }
    function showStatus(message) { if (message) { status.textContent = message; status.hidden = false; } }
    function setSession(data) {
        if (data.csrf) dialog.querySelectorAll('[name=_token]').forEach(input => { input.value = data.csrf; });
        if (data.csrf) document.querySelector('meta[name=csrf-token]')?.setAttribute('content', data.csrf);
        if (typeof data.authenticated === 'boolean') dialog.dataset.authenticated = String(data.authenticated);
        if (typeof data.verified === 'boolean') dialog.dataset.verified = String(data.verified);
        if (data.email) {
            dialog.querySelectorAll('[data-auth-email]').forEach(element => { element.textContent = data.email; });
            dialog.querySelectorAll('input[type=email]').forEach(input => { input.value = data.email; });
        }
    }
    function resolveScreen(screen) {
        if (['login', 'register'].includes(screen) && dialog.dataset.authenticated === 'true') {
            return dialog.dataset.verified === 'true' ? 'email-verified' : 'verify-email';
        }
        return screen;
    }
    function showScreen(screen, { history = false, focus = true, clear = true } = {}) {
        screen = resolveScreen(screen);
        const panel = panels.find(element => element.dataset.authPanel === screen);
        if (!panel) return;
        if (clear) clearFeedback();
        activeScreen = screen;
        panels.forEach(element => { element.hidden = element !== panel; });
        dialog.setAttribute('aria-labelledby', panel.querySelector('h2').id);
        if (!dialog.open) {
            document.documentElement.classList.add('login-modal-open');
            dialog.showModal();
        }
        dialog.querySelector('[data-modal-scroll]').scrollTop = 0;
        if (history && routes[screen]) window.history.pushState({ authScreen: screen }, '', routes[screen]);
        if (focus) {
            const control = panel.querySelector('input:not([type=hidden]), select, button, a');
            control?.focus({ preventScroll: true });
        }
    }
    function screenForURL(url) {
        const match = Object.entries(routes).find(([, route]) => new URL(route).pathname === url.pathname);
        return match?.[0];
    }
    document.addEventListener('click', event => {
        const link = event.target.closest('a');
        if (!link || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || link.target === '_blank' || link.hasAttribute('download')) return;
        const url = new URL(link.href, location.href);
        if (url.origin !== location.origin) return;
        const screen = link.dataset.authScreen || screenForURL(url);
        if (!screen) return;
        event.preventDefault();
        opener = link.closest('#login-modal') ? opener : link;
        showScreen(screen, { history: true });
    });
    dialog.querySelector('[data-close-login]').addEventListener('click', () => dialog.close());
    dialog.addEventListener('keydown', event => {
        if (event.key === 'Escape') { event.preventDefault(); dialog.close(); }
    });
    dialog.addEventListener('click', event => {
        const rect = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom)) dialog.close();
        const toggle = event.target.closest('[data-password-toggle]');
        if (toggle) {
            const input = document.getElementById(toggle.getAttribute('aria-controls'));
            const visible = input.type === 'password';
            input.type = visible ? 'text' : 'password';
            toggle.setAttribute('aria-pressed', String(visible));
            toggle.setAttribute('aria-label', visible ? 'Ocultar senha' : 'Mostrar senha');
        }
    });
    function clearPasswords() {
        dialog.querySelectorAll('input[autocomplete="current-password"],input[autocomplete="new-password"]').forEach(input => { input.value = ''; input.type = 'password'; });
        dialog.querySelectorAll('[data-password-toggle]').forEach(button => { button.setAttribute('aria-pressed', 'false'); button.setAttribute('aria-label', 'Mostrar senha'); });
    }
    dialog.addEventListener('close', () => {
        if (dialog.open) return;
        const programmatic = continuingCheckout;
        continuingCheckout = false;
        document.documentElement.classList.remove('login-modal-open');
        clearPasswords();
        if (!historyClosing) window.history.replaceState({}, '', home);
        historyClosing = false;
        opener?.focus({ preventScroll: true });
        window.dispatchEvent(new CustomEvent('escopo:auth-closed', { detail: { programmatic } }));
    });
    window.addEventListener('popstate', event => {
        const screen = event.state?.authScreen || screenForURL(new URL(location.href));
        if (screen) showScreen(screen);
        else if (dialog.open) { historyClosing = true; dialog.close(); }
    });
    async function send(url, options = {}, form = null) {
        const generation = ++requestId;
        clearFeedback();
        const buttons = form ? [...form.querySelectorAll('[type=submit]')] : [];
        buttons.forEach(button => { button.disabled = true; button.dataset.originalLabel = button.textContent; button.textContent = 'Aguarde…'; });
        form?.setAttribute('aria-busy', 'true');
        try {
            const response = await fetch(url, { ...options, credentials: 'same-origin', headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await response.json().catch(() => ({}));
            setSession(data);
            if (generation !== requestId) return;
            if (!response.ok) {
                if (response.status === 503 && data.authenticated && activeScreen === 'register') {
                    showScreen('verify-email');
                    throw new Error('Sua conta foi criada, mas o e-mail não pôde ser enviado. Use a opção de reenviar em instantes.');
                }
                if (response.status === 401) {
                    dialog.dataset.authenticated = 'false';
                    showScreen('login');
                    throw new Error('Entre na sua conta para continuar.');
                }
                if (response.status === 422) {
                    const messages = Object.values(data.errors || {}).flat();
                    if (form) Object.entries(data.errors || {}).forEach(([name, entries]) => {
                        const label = [...form.querySelectorAll('[data-error-for]')].find(element => element.dataset.errorFor === name);
                        if (label) { label.textContent = entries.join(' '); label.hidden = false; }
                        form.elements.namedItem(name)?.setAttribute('aria-invalid', 'true');
                    });
                    throw new Error(messages.join(' ') || 'Confira os campos informados.');
                }
                throw new Error(response.status === 419 ? 'Sua sessão expirou. Atualize a página e tente novamente.'
                    : response.status === 429 ? 'Aguarde um pouco antes de tentar novamente.'
                    : response.status === 503 ? 'Não foi possível enviar o e-mail agora. Tente novamente em instantes.'
                    : 'Não foi possível concluir agora. Tente novamente.');
            }
            const authResponse = new CustomEvent('escopo:auth-response', { cancelable: true, detail: data });
            if (!window.dispatchEvent(authResponse)) return;
            if (data.redirect) {
                if (dialog.open) {
                    const enteringSystem = form?.hasAttribute('data-login-form') && data.verified;
                    if (!enteringSystem || !window.EscopoSystemEntry?.open(data.redirect)) location.assign(data.redirect);
                }
                return;
            }
            if (!data.screen) throw new Error('Não foi possível concluir a solicitação. Atualize a página e tente novamente.');
            if (routes[data.screen]) window.history.replaceState({ authScreen: data.screen }, '', routes[data.screen]);
            if (data.screen === 'password-reset') {
                clearPasswords();
                window.history.replaceState({ authScreen: 'password-reset' }, '', routes.login);
            }
            if (dialog.open) {
                showScreen(data.screen, { focus: true });
                showStatus(data.message || (data.screen === 'verify-email' && activeScreen === 'verify-email' ? 'A confirmação ainda não foi concluída. Confira sua caixa de entrada.' : ''));
            }
        } catch (failure) {
            if (generation !== requestId) return;
            error.textContent = failure instanceof TypeError ? 'Não foi possível conectar. Verifique sua conexão e tente novamente.' : failure.message;
            error.hidden = false;
            if (dialog.open) error.focus();
        } finally {
            buttons.forEach(button => { button.disabled = false; button.textContent = button.dataset.originalLabel; });
            form?.setAttribute('aria-busy', 'false');
        }
    }
    dialog.querySelectorAll('[data-auth-form]').forEach(form => form.addEventListener('submit', event => {
        event.preventDefault();
        if (form.getAttribute('aria-busy') === 'true' || !form.reportValidity()) return;
        send(form.action, { method: 'POST', body: new FormData(form) }, form);
    }));
    dialog.querySelector('[data-check-verification]').addEventListener('click', async event => {
        event.target.disabled = true;
        await send(routes['verify-email']);
        event.target.disabled = false;
    });
    window.EscopoAuthModal = {
        open(screen = 'login') {
            opener = document.activeElement;
            showScreen(screen, { history: true });
        },
        close() {
            if (!dialog.open) return;
            continuingCheckout = true;
            historyClosing = true;
            dialog.close();
        },
    };
    if (dialog.dataset.initialScreen) showScreen(dialog.dataset.initialScreen, { clear: false });
    dialog.dataset.authReady = 'true';
})();
