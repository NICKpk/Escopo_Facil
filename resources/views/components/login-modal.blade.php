@props(['authScreen' => null, 'authEmail' => null, 'authToken' => null])
@php
    $initialScreen = $authScreen ?? old('_auth_screen', '');
    $modalEmail = $authEmail ?? old('email', auth()->user()?->email ?? '');
    $modalEmail = is_string($modalEmail) ? $modalEmail : '';
@endphp
<dialog class="login-modal" id="login-modal" aria-labelledby="auth-login-title" data-initial-screen="{{ $initialScreen }}" data-home="{{ url('/') }}" data-dashboard="{{ route('dashboard') }}" data-authenticated="{{ auth()->check() ? 'true' : 'false' }}" data-verified="{{ auth()->user()?->hasVerifiedEmail() ? 'true' : 'false' }}">
    <button class="login-modal-close" type="button" aria-label="Fechar janela" data-close-login>×</button>
    <div class="modal-scroll" data-modal-scroll>
    <div class="login-modal-content">
        <img class="login-modal-logo" src="{{ asset('assets/landing/logo-original.svg') }}" alt="Escopo Fácil" width="290" height="83">
        <p class="login-modal-error" role="alert" tabindex="-1" @if(! $errors->any()) hidden @endif>{{ $errors->first() }}</p>
        <p class="auth-modal-status" role="status" tabindex="-1" @if(! session('status')) hidden @endif>{{ session('status') === 'verification-link-sent' ? 'Um novo link de confirmação foi enviado.' : session('status') }}</p>

        <section data-auth-panel="login">
            <h2 id="auth-login-title">Acesse sua conta</h2><p class="auth-description">Entre com seu e-mail e senha para continuar.</p>
            <form method="POST" action="{{ route('login') }}" data-auth-form data-login-form>
                @csrf <input type="hidden" name="_auth_screen" value="login">
                <x-auth-modal-field id="modal-login-email" name="email" label="Email" type="email" placeholder="seuemail@exemplo.com" autocomplete="username" :value="$modalEmail" autofocus />
                <x-auth-modal-field id="modal-login-password" name="password" label="Senha" type="password" placeholder="Digite sua senha" autocomplete="current-password" />
                <a class="login-forgot" href="{{ route('password.request') }}" data-auth-screen="forgot-password">Esqueceu a senha?</a>
                <button class="login-modal-submit" type="submit">Entrar na conta</button>
            </form>
            <p class="login-create">Ainda não tem uma conta? <a href="{{ route('register') }}" data-auth-screen="register">Criar conta</a></p>
        </section>

        <section data-auth-panel="register" hidden>
            <h2 id="auth-register-title">Crie sua conta</h2><p class="auth-description">Comece a organizar seus projetos com o Escopo Fácil.</p>
            <form method="POST" action="{{ route('register') }}" data-auth-form>
                @csrf <input type="hidden" name="_auth_screen" value="register">
                <input type="hidden" name="terms_version" value="{{ config('legal.version') }}">
                <input type="hidden" name="privacy_notice_version" value="{{ config('legal.version') }}">
                <x-auth-modal-field id="auth-register-name" name="name" label="Nome completo" placeholder="Como podemos chamar você?" autocomplete="name" :value="old('name', '')" maxlength="255" />
                <x-auth-modal-field id="auth-register-email" name="email" label="Email" type="email" placeholder="seuemail@exemplo.com" autocomplete="email" :value="$modalEmail" maxlength="255" />
                <div class="login-modal-field"><label for="auth-job-role">Seu cargo<span aria-hidden="true">*</span></label>
                    <select id="auth-job-role" name="job_role" required aria-describedby="auth-job-role-error"><option value="">Selecione seu cargo</option>
                        @foreach(['dev-frontend' => 'Desenvolvedor Frontend', 'dev-backend' => 'Desenvolvedor Backend', 'dev-fullstack' => 'Desenvolvedor Full Stack', 'ui-ux-designer' => 'UI/UX Designer', 'product-owner' => 'Product Owner', 'scrum-master' => 'Scrum Master', 'tech-lead' => 'Tech Lead', 'qa-testes' => 'QA / Testes'] as $role => $label)
                            <option value="{{ $role }}" @selected(old('job_role') === $role)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div><p id="auth-job-role-error" class="auth-field-error" data-error-for="job_role" hidden></p>
                <x-auth-modal-field id="auth-register-password" name="password" label="Senha" type="password" placeholder="Use pelo menos 8 caracteres" autocomplete="new-password" minlength="8" />
                <x-auth-modal-field id="auth-register-confirmation" name="password_confirmation" label="Confirmar senha" type="password" placeholder="Digite a senha novamente" autocomplete="new-password" minlength="8" />
                <label class="auth-terms"><input type="checkbox" name="terms" value="1" required @checked(old('terms')) aria-describedby="auth-terms-error"><span>Aceito os <a href="{{ route('legal.terms', ['version' => config('legal.version')]) }}" data-legal-document>Termos e Condições de Uso</a>.</span></label>
                <p class="auth-privacy-note">Saiba como seus dados são utilizados no <a href="{{ route('legal.privacy', ['version' => config('legal.version')]) }}" data-legal-document>Aviso de Privacidade</a>.</p>
                <p class="auth-field-error" id="auth-terms-error" data-error-for="terms" hidden></p>
                <button class="login-modal-submit" type="submit">Criar minha conta</button>
            </form>
            <p class="login-create">Já tem uma conta? <a href="{{ route('login') }}" data-auth-screen="login">Entre</a></p>
        </section>

        <section data-auth-panel="forgot-password" hidden>
            <h2 id="auth-forgot-title">Esqueceu sua senha?</h2><p class="auth-description">Informe seu e-mail para receber um link de recuperação.</p>
            <form method="POST" action="{{ route('password.email') }}" data-auth-form>
                @csrf <input type="hidden" name="_auth_screen" value="forgot-password">
                <x-auth-modal-field id="auth-forgot-email" name="email" label="Email" type="email" placeholder="seuemail@exemplo.com" autocomplete="email" :value="$modalEmail" />
                <button class="login-modal-submit" type="submit">Enviar link de recuperação</button>
            </form>
            <p class="login-create"><a href="{{ route('login') }}" data-auth-screen="login">Voltar para o login</a></p>
        </section>

        <section data-auth-panel="reset-link-sent" hidden>
            <div class="auth-success-icon" aria-hidden="true">✉</div><h2 id="auth-sent-title">Confira seu e-mail</h2>
            <p class="auth-description">Se o endereço estiver cadastrado, você receberá um link para criar uma nova senha. Confira também a caixa de spam.</p>
            <a class="login-modal-submit" href="{{ route('login') }}" data-auth-screen="login">Voltar para o login</a>
            <p class="login-create">Não recebeu? <a href="{{ route('password.request') }}" data-auth-screen="forgot-password">Tentar novamente</a></p>
        </section>

        <section data-auth-panel="reset-password" hidden>
            <h2 id="auth-reset-title">Crie uma nova senha</h2><p class="auth-description">Escolha uma senha com pelo menos 8 caracteres.</p>
            <form method="POST" action="{{ route('password.store') }}" data-auth-form>
                @csrf <input type="hidden" name="_auth_screen" value="reset-password"><input type="hidden" name="token" value="{{ $authToken ?? '' }}">
                <x-auth-modal-field id="auth-reset-email" name="email" label="Email" type="email" placeholder="seuemail@exemplo.com" autocomplete="username" :value="$modalEmail" />
                <x-auth-modal-field id="auth-reset-password" name="password" label="Nova senha" type="password" placeholder="Use pelo menos 8 caracteres" autocomplete="new-password" minlength="8" />
                <x-auth-modal-field id="auth-reset-confirmation" name="password_confirmation" label="Confirmar nova senha" type="password" placeholder="Digite a nova senha novamente" autocomplete="new-password" minlength="8" />
                <button class="login-modal-submit" type="submit">Salvar nova senha</button>
            </form>
            <p class="login-create"><a href="{{ route('password.request') }}" data-auth-screen="forgot-password">Solicitar outro link</a></p>
        </section>

        <section data-auth-panel="password-reset" hidden>
            <div class="auth-success-icon" aria-hidden="true">✓</div><h2 id="auth-password-saved-title">Senha atualizada!</h2><p class="auth-description">Agora você pode entrar na sua conta usando a nova senha.</p>
            <a class="login-modal-submit" href="{{ route('login') }}" data-auth-screen="login">Entrar na conta</a>
        </section>

        <section data-auth-panel="verify-email" hidden>
            <div class="auth-success-icon" aria-hidden="true">✉</div><h2 id="auth-verify-title">Confirme seu e-mail</h2>
            <p class="auth-description">Enviamos um link para <strong data-auth-email>{{ $modalEmail }}</strong>. Confirme seu endereço para acessar o sistema.</p>
            <button class="login-modal-submit" type="button" data-check-verification>Já confirmei meu e-mail</button>
            <form method="POST" action="{{ route('verification.send') }}" data-auth-form class="auth-secondary-form">@csrf <button class="auth-text-button" type="submit">Reenviar e-mail de confirmação</button></form>
            <form method="POST" action="{{ route('logout') }}" data-auth-form class="auth-secondary-form">@csrf <button class="auth-text-button" type="submit">Sair e usar outra conta</button></form>
        </section>

        <section data-auth-panel="email-verified" hidden>
            <div class="auth-success-icon" aria-hidden="true">✓</div><h2 id="auth-verified-title">E-mail confirmado!</h2><p class="auth-description">Sua conta está pronta. Vamos organizar suas próximas entregas?</p>
            <a class="login-modal-submit" data-system-entry href="{{ route('dashboard') }}">Acessar o sistema</a>
        </section>

        <section data-auth-panel="verification-invalid" hidden>
            <h2 id="auth-invalid-title">Este link não é válido</h2><p class="auth-description">O link de confirmação expirou ou foi alterado. Entre na sua conta para solicitar outro.</p>
            <a class="login-modal-submit" href="{{ route('login') }}" data-auth-screen="login">Entrar na conta</a>
        </section>

        <section data-auth-panel="confirm-password" hidden>
            <h2 id="auth-confirm-title">Confirme sua senha</h2><p class="auth-description">Para proteger sua conta, confirme sua senha antes de continuar.</p>
            <form method="POST" action="{{ route('password.confirm') }}" data-auth-form>
                @csrf <input type="hidden" name="_auth_screen" value="confirm-password">
                <x-auth-modal-field id="auth-confirm-password" name="password" label="Senha" type="password" placeholder="Digite sua senha" autocomplete="current-password" />
                <button class="login-modal-submit" type="submit">Confirmar e continuar</button>
            </form>
        </section>
    </div>
    </div>
</dialog>
<script type="application/json" id="auth-modal-routes">{!! \Illuminate\Support\Js::encode(['login' => route('login'), 'register' => route('register'), 'forgot-password' => route('password.request'), 'verify-email' => route('verification.notice'), 'confirm-password' => route('password.confirm')]) !!}</script>
