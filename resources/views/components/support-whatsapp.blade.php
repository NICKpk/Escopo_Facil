@php($whatsappUrl = config('legal.whatsapp_url'))
<a class="support-whatsapp" href="{{ $whatsappUrl ?: '#contato' }}" @if($whatsappUrl) target="_blank" rel="noopener noreferrer" @endif aria-label="{{ $whatsappUrl ? 'Falar com o suporte pelo WhatsApp' : 'Entrar em contato com o suporte — WhatsApp em breve' }}">
    <x-whatsapp-icon />
    <span class="support-whatsapp-tooltip" aria-hidden="true">{{ $whatsappUrl ? 'Fale com o suporte' : 'Fale com a equipe · WhatsApp em breve' }}</span>
</a>
