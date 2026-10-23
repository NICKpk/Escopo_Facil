<section class="contact-section section" id="contato" aria-labelledby="contact-title">
    <p class="eyebrow">Contato</p>
    <h2 id="contact-title">Entre em contato</h2>
    <p class="contact-description">Tem dúvidas sobre o Escopo Fácil? Envie uma mensagem e<br class="desktop"> fale com a nossa equipe.</p>
    <form class="contact-form" id="mensagem" action="{{ route('contact.send') }}" method="POST" data-contact-form>
        @csrf
        <div class="contact-fields">
            <label>Nome<input name="name" type="text" placeholder="Ex. Nome Sobrenome" autocomplete="name" required maxlength="120"></label>
            <label>Email<input name="email" type="email" placeholder="seu@email.com" autocomplete="email" required maxlength="254"></label>
        </div>
        <label>Mensagem<textarea name="message" placeholder="Sua mensagem" required maxlength="5000" rows="3"></textarea></label>
        <label>Você já usa o Escopo Fácil?<select name="uses_platform" required><option value="">Selecione…</option><option value="yes">Sim</option><option value="no">Não</option></select></label>
        <button class="button" type="submit">Enviar mensagem</button>
        <p class="contact-feedback" role="status" aria-live="polite" hidden></p>
        <p class="contact-privacy">Seus dados serão usados para responder ao contato. <a href="{{ route('legal.privacy') }}" data-legal-document>Aviso de Privacidade</a></p>
    </form>
    <div class="contact-support">
        <div class="contact-support-copy">
            <p class="support-eyebrow">Pessoas por trás do projeto</p>
            <h3>Quem criou o Escopo Fácil<br><span>está aqui para ajudar.</span></h3>
            <p>Tem uma dúvida, uma sugestão ou precisa de uma mão para começar? Fale diretamente com a equipe que está construindo o projeto.</p>
            <a class="button" href="#mensagem">Falar com a equipe <span aria-hidden="true">→</span></a>
        </div>
        <div class="contact-support-visual">
            <div class="contact-support-photo"><img src="{{ asset('assets/landing/equipe-escopo-facil-adesivos.png') }}" alt="Composição da equipe do Escopo Fácil em uma mesa de trabalho, com adesivos da marca nos dois notebooks" width="1536" height="1024" loading="lazy"></div>
            <span class="support-curve" aria-hidden="true"></span>
        </div>
    </div>
</section>
