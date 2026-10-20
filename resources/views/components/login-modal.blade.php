<dialog class="login-modal" id="login-modal" aria-labelledby="login-modal-title" aria-describedby="login-modal-description">
    <button class="login-modal-close" type="button" aria-label="Fechar login" data-close-login>×</button>
    <div class="login-modal-content">
        <img class="login-modal-logo" src="{{ asset('assets/landing/logo-original.svg') }}" alt="Escopo Fácil" width="290" height="83">
        <h2 id="login-modal-title">Acesse sua conta</h2>
        <p id="login-modal-description">Entre com seu e-mail e senha para continuar.</p>
        <form method="POST" action="{{ route('login') }}" data-login-form>
            @csrf
            <div class="login-modal-field">
                <label for="modal-login-email">Email<span aria-hidden="true">*</span></label>
                <input id="modal-login-email" name="email" type="email" placeholder="seuemail@exemplo.com" autocomplete="username" required autofocus>
            </div>
            <div class="login-modal-field login-password-field">
                <label for="modal-login-password">Senha<span aria-hidden="true">*</span></label>
                <input id="modal-login-password" name="password" type="password" placeholder="Digite sua senha" autocomplete="current-password" required>
                <button class="login-password-toggle" type="button" aria-label="Mostrar senha" aria-pressed="false" aria-controls="modal-login-password">
                    <svg viewBox="0 0 24 24" width="23" height="23" fill="none" aria-hidden="true"><path d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.2A10.8 10.8 0 0112 5c5.5 0 9 7 9 7a17.4 17.4 0 01-3.1 4M6.4 6.4A20.4 20.4 0 003 12s3.5 7 9 7a10.7 10.7 0 005.6-1.6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <p class="login-modal-error" role="alert" tabindex="-1" hidden></p>
            <a class="login-forgot" href="{{ route('password.request') }}">Esqueceu a senha?</a>
            <button class="login-modal-submit" type="submit">Entrar na conta</button>
        </form>
        <p class="login-create">Ainda não tem uma conta? <a href="{{ route('register') }}">Criar conta</a></p>
    </div>
</dialog>
