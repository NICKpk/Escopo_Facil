(() => {
    const dialog = document.querySelector('#login-modal');
    if (!dialog || typeof dialog.showModal !== 'function') return;
    const form = dialog.querySelector('[data-login-form]');
    const email = form.elements.email;
    const password = form.elements.password;
    const error = dialog.querySelector('.login-modal-error');
    const submit = dialog.querySelector('[type=submit]');
    const passwordToggle = dialog.querySelector('.login-password-toggle');
    let opener;
    let request;
    function clearError() { error.hidden = true; error.textContent = ''; }
    function setBusy(busy) {
        submit.disabled = busy;
        submit.textContent = busy ? 'Entrando…' : 'Entrar na conta';
        form.setAttribute('aria-busy', String(busy));
    }
    document.querySelectorAll('[data-login-trigger]').forEach(trigger => {
        trigger.addEventListener('click', event => {
            if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
            event.preventDefault();
            opener = trigger;
            clearError();
            document.documentElement.classList.add('login-modal-open');
            dialog.showModal();
            email.focus({ preventScroll: true });
        });
    });
    dialog.querySelector('[data-close-login]').addEventListener('click', () => dialog.close());
    dialog.addEventListener('keydown', event => {
        if (event.key === 'Escape') { event.preventDefault(); dialog.close(); }
    });
    dialog.addEventListener('click', event => {
        const rect = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom)) dialog.close();
    });
    dialog.addEventListener('close', () => {
        request?.abort();
        document.documentElement.classList.remove('login-modal-open');
        password.value = '';
        password.type = 'password';
        passwordToggle.setAttribute('aria-pressed', 'false');
        passwordToggle.setAttribute('aria-label', 'Mostrar senha');
        setBusy(false);
        opener?.focus({ preventScroll: true });
    });
    passwordToggle.addEventListener('click', () => {
        const visible = password.type === 'password';
        password.type = visible ? 'text' : 'password';
        passwordToggle.setAttribute('aria-pressed', String(visible));
        passwordToggle.setAttribute('aria-label', visible ? 'Ocultar senha' : 'Mostrar senha');
    });
    form.addEventListener('submit', async event => {
        event.preventDefault();
        if (submit.disabled || !form.reportValidity()) return;
        clearError();
        setBusy(true);
        request = new AbortController();
        try {
            const response = await fetch(form.action, {
                method: 'POST', body: new FormData(form), credentials: 'same-origin',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                signal: request.signal,
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                const messages = Object.values(data.errors || {}).flat().join(' ');
                const validationMessage = messages.includes('These credentials do not match') ? 'E-mail ou senha incorretos.'
                    : messages.includes('Too many login attempts') ? 'Muitas tentativas. Aguarde um pouco e tente novamente.'
                    : messages || 'Confira o e-mail e a senha informados.';
                const message = response.status === 419 ? 'Sua sessão expirou. Atualize a página e tente novamente.'
                    : response.status === 422 ? validationMessage
                    : response.status === 429 ? 'Muitas tentativas. Aguarde um pouco e tente novamente.'
                    : 'Não foi possível entrar agora. Tente novamente.';
                throw new Error(message);
            }
            if (!data.redirect) throw new Error('Não foi possível concluir o login. Atualize a página e tente novamente.');
            window.location.assign(data.redirect);
        } catch (failure) {
            if (failure.name === 'AbortError') return;
            error.textContent = failure instanceof TypeError ? 'Não foi possível conectar. Verifique sua conexão e tente novamente.' : failure.message;
            error.hidden = false;
            error.focus();
        } finally { setBusy(false); }
    });
})();
