<footer class="site-footer">
    <div class="site-footer-inner">
        <div class="site-footer-columns">
            <nav aria-label="Website"><h2>Website</h2><a href="{{ url('/') }}">Home</a><a href="#features">Funcionalidades</a><a href="#beneficios">Benefícios</a><a href="#precos">Planos</a><a href="#contato">Contato</a></nav>
            <nav aria-label="Recursos"><h2>Recursos</h2><a href="#features">Gestão de projetos</a><a href="#features">Tarefas e prazos</a><a href="#beneficios">Colaboração em equipe</a><a href="#acesso">Acesso pelo navegador</a></nav>
            <nav aria-label="Sua conta"><h2>Sua conta</h2><a href="{{ route('login') }}" data-login-trigger aria-haspopup="dialog" aria-controls="login-modal">Entre</a><a href="{{ route('register') }}">Crie sua conta</a><a href="{{ route('password.request') }}" data-auth-screen="forgot-password">Recuperar senha</a></nav>
            <nav aria-label="Suporte">
                <h2>Suporte</h2>
                <span class="footer-support-detail"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m4 20 1.2-4.2A8 8 0 1 1 8.2 19L4 20Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M9 8c0 4 3 7 7 7l1-2-2-1-1 1c-1.5-.6-2.4-1.5-3-3l1-1-1-2-2 1Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg><span>(11) 00000-0000<small>WhatsApp demonstrativo</small></span></span>
                <a class="footer-support-detail" href="mailto:Suporte@escopofacil.com.br"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="m3 6 9 7 9-7" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg><span>Suporte@escopofacil.com.br</span></a>
                <span class="footer-support-detail"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg><span>Atendimento das 8h às 18h</span></span>
                <a href="#contato">Falar com a equipe</a>
            </nav>
            <nav aria-label="Legal"><h2>Legal</h2><a href="{{ route('legal.terms') }}" data-legal-document>Termos de Uso</a><a href="{{ route('legal.privacy') }}" data-legal-document>Aviso de Privacidade</a><a href="{{ route('legal.privacy') }}" data-legal-document>Privacidade e dados</a></nav>
        </div>
        <div class="site-footer-bottom">
            <div class="site-footer-identity">
                <a href="{{ url('/') }}" aria-label="Escopo Fácil — início"><img class="site-footer-logo" src="{{ asset('assets/landing/logo-original.svg') }}" alt="Escopo Fácil" width="185" height="53"></a>
                <p>© {{ date('Y') }} Escopo Fácil · Projeto acadêmico</p>
            </div>
            <a class="lgpd-badge" href="{{ route('legal.privacy') }}" data-legal-document aria-label="LGPD — consultar o Aviso de Privacidade"><img src="{{ asset('assets/landing/lgpd-informativo.svg') }}" alt="LGPD — Privacidade e dados" width="182" height="56"></a>
        </div>
        <p class="site-footer-description">Organização simples para projetos, tarefas e equipes. Desenvolvido no Centro Universitário Senac, no curso de Tecnologia em Sistemas para Internet. Ambiente acadêmico de simulação, sem cobranças reais. Consulte os <a href="{{ route('legal.terms') }}" data-legal-document>Termos de Uso</a> e o <a href="{{ route('legal.privacy') }}" data-legal-document>Aviso de Privacidade</a>.</p>
        <p class="site-footer-credit">Criado por Natan Oliveira, Guilherme Augusto e Nicollas Ramos</p>
    </div>
</footer>
