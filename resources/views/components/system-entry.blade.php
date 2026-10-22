<dialog id="system-entry" class="system-entry" aria-label="Carregando o Escopo Fácil" data-dashboard="{{ route('dashboard') }}">
    <div class="system-entry-stage">
        <video class="system-entry-video" muted playsinline preload="none" aria-hidden="true" data-entry-video>
            <source src="{{ asset('assets/system/login-transition.webm') }}" type="video/webm">
        </video>
        <div class="system-entry-fallback" data-entry-fallback hidden>
            <img src="{{ asset('assets/landing/logo-original.svg') }}" alt="Escopo Fácil" width="220" height="63">
            <span class="system-entry-spinner" aria-hidden="true"></span>
        </div>
    </div>
</dialog>
