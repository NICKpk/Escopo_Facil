@props(['id', 'name', 'label', 'type' => 'text', 'placeholder' => '', 'autocomplete' => null, 'value' => ''])
@php($value = is_scalar($value) ? $value : '')
<div class="login-modal-field {{ $type === 'password' ? 'login-password-field' : '' }}">
    <label for="{{ $id }}">{{ $label }}<span aria-hidden="true">*</span></label>
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" placeholder="{{ $placeholder }}" @if($type !== 'password') value="{{ $value }}" @endif @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif aria-describedby="{{ $id }}-error" {{ $attributes }} required>
    @if($type === 'password')
        <button class="login-password-toggle" type="button" aria-label="Mostrar senha" aria-pressed="false" aria-controls="{{ $id }}" data-password-toggle>
            <svg viewBox="0 0 24 24" width="23" height="23" fill="none" aria-hidden="true"><path d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.2A10.8 10.8 0 0112 5c5.5 0 9 7 9 7a17.4 17.4 0 01-3.1 4M6.4 6.4A20.4 20.4 0 003 12s3.5 7 9 7a10.7 10.7 0 005.6-1.6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    @endif
</div>
<p class="auth-field-error" id="{{ $id }}-error" data-error-for="{{ $name }}" hidden></p>
