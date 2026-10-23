<article class="legal-document">
    <div class="legal-document-header">
        <p class="legal-eyebrow">Escopo Fácil · Projeto acadêmico</p>
        <h1 tabindex="-1">{{ $title }}</h1>
        <p class="legal-version">Versão {{ $version }} · Publicada em {{ $publishedOn }}</p>
    </div>
    @if(! config('legal.institution') || ! config('legal.support_email'))
        <p class="legal-draft"><strong>Minuta para o ambiente acadêmico.</strong> A identificação institucional ou o contato ainda precisam ser completados pela equipe antes de ampliar os testes com dados pessoais reais.</p>
    @endif
    <section class="legal-identification" aria-label="Identificação do projeto">
        <p><strong>Responsáveis:</strong> {{ config('legal.responsible') }}</p>
        <p><strong>Instituição e curso:</strong> {{ config('legal.institution') ?: 'Informação ainda não definida pela equipe.' }}</p>
        <p><strong>Contato:</strong> <a href="mailto:{{ config('legal.support_email') }}">{{ config('legal.support_email') }}</a></p>
    </section>
    @include($documentView)
    <nav class="legal-related" aria-label="Documentos relacionados">
        @if($type === 'terms')<a href="{{ route('legal.privacy') }}" data-legal-document>Consultar o Aviso de Privacidade →</a>
        @else<a href="{{ route('legal.terms') }}" data-legal-document>Consultar os Termos de Uso →</a>@endif
    </nav>
</article>
