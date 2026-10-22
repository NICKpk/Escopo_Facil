<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <title>Seu plano Pro — Escopo Fácil</title>
    <link rel="stylesheet" href="{{ asset('assets/landing/checkout.css') . '?v=' . filemtime(public_path('assets/landing/checkout.css')) }}">
    @vite('resources/js/checkout.js')
</head>
<body>
<div class="checkout-top"><a href="{{ url('/') }}"><img src="{{ asset('assets/landing/logo-original.svg') }}" alt="Escopo Fácil" width="160" height="46"></a><a class="back" href="{{ url('/#precos') }}">← Voltar aos planos</a></div>
<main class="checkout-shell" id="checkout" data-endpoint="{{ route('subscription.process') }}" data-plan="{{ $plan->id }}" data-period="{{ $period }}">
    <aside class="order" aria-label="Resumo do plano">
        <p class="eyebrow">Seu próximo passo</p><h1>Mais espaço para<br>suas ideias.</h1>
        <p class="description">Organize cada detalhe e leve seus projetos mais longe com o Escopo Pro.</p>
        <nav class="periods" aria-label="Período do plano">
            <a href="{{ route('subscription.checkout', ['plan' => $plan, 'period' => 'monthly']) }}" aria-current="{{ $period === 'monthly' ? 'true' : 'false' }}">Mensal</a>
            <a href="{{ route('subscription.checkout', ['plan' => $plan, 'period' => 'yearly']) }}" aria-current="{{ $period === 'yearly' ? 'true' : 'false' }}">Anual</a>
        </nav>
        <ul><li>Prioridades e prazos</li><li>Gestão de membros</li><li>Controle por projeto</li><li>Relatórios simples</li></ul>
        <div class="total"><span>Escopo Pro · {{ $period === 'yearly' ? 'anual' : 'mensal' }}</span><strong>R$ {{ number_format($plan->{'price_'.$period}, 2, ',', '.') }}</strong></div>
        <small>{{ $period === 'yearly' ? 'Pagamento único por 12 meses de acesso.' : 'Pagamento único por 1 mês de acesso.' }} Sem renovação automática.</small>
        <small>Conta: {{ auth()->user()->email }}</small>
    </aside>
    <div class="payment">
        <span class="test-badge">Ambiente de simulação · sem cobrança real</span>
        <div class="feedback" id="payment-feedback" role="alert"></div>
        <section id="payment-intro" aria-labelledby="payment-title">
            <div class="pix-symbol" aria-hidden="true">❖</div><h2 id="payment-title">Tudo pronto? Pague com PIX.</h2>
            <p>Gere seu QR Code para concluir a simulação e começar a usar seu plano.</p>
            <button type="button" class="primary" id="generate-pix">Gerar QR Code PIX</button>
            <p class="intro-note">{{ config('payments.driver') === 'local' ? 'Demonstração local: o QR Code não é aceito por aplicativos de banco. Use “Simular pagamento” para testar.' : 'AbacatePay Dev mode: utilize a simulação de pagamento, sem transferir dinheiro.' }}</p>
        </section>
        <section id="payment-pending" hidden aria-labelledby="pix-title">
            <h2 id="pix-title" tabindex="-1">Seu QR Code está pronto</h2><p>Conclua a simulação para liberar o Escopo Pro.</p>
            <div class="qr-wrap"><canvas id="pix-qr" role="img" aria-label="QR Code demonstrativo do pagamento"></canvas></div>
            <p class="countdown">Válido por <strong id="pix-countdown">--:--</strong></p>
            <label class="copy-label" for="pix-code">Código copia e cola · teste</label><input class="copy-code" id="pix-code" readonly>
            <button type="button" class="secondary" id="copy-pix">Copiar código</button>
            <div class="status-line" role="status">Aguardando confirmação do pagamento</div>
            <button type="button" class="primary" id="simulate-pix">Simular pagamento</button>
            <button type="button" class="secondary" id="check-pix">Consultar confirmação</button>
        </section>
        <section class="complete" id="payment-expired" hidden aria-labelledby="expired-title"><h2 id="expired-title" tabindex="-1">Este QR Code expirou</h2><p>Gere um novo código para continuar sua simulação.</p><button type="button" class="primary" id="retry-pix">Gerar novo QR Code</button></section>
        <section class="complete" id="payment-paid" hidden aria-labelledby="paid-title"><div class="success-icon" aria-hidden="true">✓</div><h2 id="paid-title" tabindex="-1">Seu Pro está liberado!</h2><p>Pagamento simulado confirmado.<br>Agora é hora de colocar seus projetos em movimento.</p><a class="primary" href="{{ route('dashboard') }}">Ir para meus projetos →</a></section>
        <noscript><p>Ative o JavaScript para gerar o QR Code e acompanhar o pagamento.</p></noscript>
    </div>
</main>
<p class="secure">Escopo Fácil · Projeto acadêmico · Pagamento exclusivamente por PIX</p>
</body>
</html>
