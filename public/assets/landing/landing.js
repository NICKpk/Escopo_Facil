const toggle = document.querySelector('.menu');
const nav = document.querySelector('#navigation');
const contactForm = document.querySelector('[data-contact-form]');
contactForm?.addEventListener('submit', async event => {
    event.preventDefault();
    const button = contactForm.querySelector('[type=submit]');
    const feedback = contactForm.querySelector('.contact-feedback');
    if (button.disabled) return;
    button.disabled = true;
    button.setAttribute('aria-busy', 'true');
    feedback.hidden = true;
    try {
        const response = await fetch(contactForm.action, {
            method: 'POST', credentials: 'same-origin',
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            body: new FormData(contactForm),
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(Object.values(data.errors || {}).flat()[0] || (response.status === 429 ? 'Aguarde um minuto antes de enviar outra mensagem.' : 'Não foi possível enviar. Tente novamente.'));
        feedback.textContent = data.message;
        contactForm.reset();
    } catch (error) {
        feedback.textContent = error instanceof TypeError ? 'Não foi possível conectar. Verifique sua conexão e tente novamente.' : error.message;
    } finally {
        feedback.hidden = false;
        button.disabled = false;
        button.removeAttribute('aria-busy');
    }
});
function closeMenu() { toggle.setAttribute('aria-expanded', 'false'); nav.classList.remove('is-open'); }
toggle.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(open));
    nav.classList.toggle('is-open', open);
});


nav.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));

// Align the section's first content with the contracted header, rather than
// stacking the browser's global scroll padding on top of the section padding.
function alignSection(target, behavior = 'smooth') {
    stopWheelScroll();
    closeMenu();
    const header = document.querySelector('header');
    const firstContent = target.querySelector(':scope > .eyebrow, :scope > .feature-row, :scope > h2') || target;
    let contentTop = 0;
    for (let element = firstContent; element; element = element.offsetParent) contentTop += element.offsetTop;
    const headerTop = parseFloat(getComputedStyle(header).top) || 16;
    const sectionGaps = { features: 16, beneficios: 8, precos: 16, contato: 8 };
    const gap = sectionGaps[target.id] ?? 20;
    const clearance = headerTop + header.getBoundingClientRect().height + gap;
    // Explicit positions avoid scroll-padding being applied a second time.
    window.scrollTo({ top: Math.max(0, contentTop - clearance), behavior });
}
document.addEventListener('click', event => {
    const link = event.target.closest('a[href]');
    if (!link || event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || link.target === '_blank') return;
    const url = new URL(link.href, location.href);
    if (url.origin !== location.origin || url.pathname !== location.pathname || !url.hash) return;
    let id;
    try { id = decodeURIComponent(url.hash.slice(1)); } catch { return; }
    const target = document.getElementById(id);
    if (!target?.matches('section[id]')) return;
    event.preventDefault();
    history.pushState(null, '', url.hash);
    alignSection(target);
});
// Direct links and browser history should use the same calibrated alignment.
window.addEventListener('hashchange', () => {
    const target = document.getElementById(location.hash.slice(1));
    if (target?.matches('section[id]')) alignSection(target);
});
window.addEventListener('load', () => {
    const target = document.getElementById(location.hash.slice(1));
    if (target?.matches('section[id]')) alignSection(target, 'instant');
});
document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') { closeMenu(); toggle.focus(); }
});
document.querySelectorAll('[data-period]').forEach(button => {
    button.addEventListener('click', () => {
        const period = button.dataset.period;
        document.querySelectorAll('[data-checkout-link]').forEach(link => { const url = new URL(link.href); url.searchParams.set('period', period); link.href = url.href; });
        document.querySelectorAll('[data-period]').forEach(option => option.setAttribute('aria-pressed', String(option === button)));
        document.querySelectorAll('[data-yearly]').forEach(price => { price.textContent = `R$ ${price.dataset[period]}`; });
        document.querySelectorAll('[data-price-period]').forEach(label => { label.textContent = period === 'yearly' ? '/ano' : '/mês'; });
        document.querySelectorAll('[data-billing-note]').forEach(label => { label.textContent = period === 'yearly' ? 'Cobrança anual' : 'Cobrança mensal'; });
        document.querySelectorAll('.saving-badge').forEach(badge => { badge.hidden = period !== 'yearly'; });
        document.querySelector('.plan-note').textContent = period === 'yearly' ? 'Exibindo valores anuais.' : 'Exibindo valores mensais.';
    });
});

// Ease mouse-wheel scrolling without replacing native touch, keyboard or zoom.
let wheelFrame = null;
let wheelTarget = window.scrollY;
let wheelTime = 0;
let wheelDirection = 0;
function stopWheelScroll() {
    if (wheelFrame !== null) window.cancelAnimationFrame(wheelFrame);
    wheelFrame = null;
    wheelDirection = 0;
}
function animateWheelScroll(time) {
    const elapsed = Math.min(64, time - wheelTime);
    wheelTime = time;
    const maxScroll = Math.max(0, document.documentElement.scrollHeight - window.innerHeight);
    wheelTarget = Math.max(0, Math.min(maxScroll, wheelTarget));
    const distance = wheelTarget - window.scrollY;
    if (Math.abs(distance) < .75) {
        window.scrollTo({ top: wheelTarget, behavior: 'instant' });
        wheelFrame = null;
        wheelDirection = 0;
        return;
    }
    window.scrollTo({ top: window.scrollY + distance * (1 - Math.exp(-elapsed / 130)), behavior: 'instant' });
    wheelFrame = window.requestAnimationFrame(animateWheelScroll);
}
window.addEventListener('wheel', event => {
    if (document.querySelector('#login-modal[open], #checkout-modal[open], #legal-modal[open]')) { stopWheelScroll(); return; }
    if (event.ctrlKey || event.metaKey || event.shiftKey ||
        !event.cancelable || Math.abs(event.deltaX) > Math.abs(event.deltaY) || !event.deltaY) {
        stopWheelScroll();
        return;
    }
    // Let independently scrollable panels keep their own native wheel behavior.
    const nestedScroll = event.composedPath().some(element => {
        if (!(element instanceof HTMLElement) || element === document.body || element === document.documentElement) return false;
        const scrollable = /auto|scroll/.test(window.getComputedStyle(element).overflowY);
        return scrollable && element.scrollHeight > element.clientHeight &&
            (event.deltaY < 0 ? element.scrollTop > 0 : element.scrollTop + element.clientHeight < element.scrollHeight - 1);
    });
    if (nestedScroll) { stopWheelScroll(); return; }
    const maxScroll = Math.max(0, document.documentElement.scrollHeight - window.innerHeight);
    if ((window.scrollY <= 0 && event.deltaY < 0) || (window.scrollY >= maxScroll && event.deltaY > 0)) {
        stopWheelScroll();
        return;
    }
    event.preventDefault();
    const direction = Math.sign(event.deltaY);
    if (wheelFrame === null || direction !== wheelDirection) wheelTarget = window.scrollY;
    wheelDirection = direction;
    const unit = event.deltaMode === 1 ? 18 : event.deltaMode === 2 ? window.innerHeight : 1;
    wheelTarget = Math.max(0, Math.min(maxScroll, wheelTarget + event.deltaY * unit * .75));
    if (wheelFrame === null) {
        wheelTime = performance.now();
        wheelFrame = window.requestAnimationFrame(animateWheelScroll);
    }
}, { passive: false });
window.addEventListener('pointerdown', stopWheelScroll, { passive: true });
window.addEventListener('touchstart', stopWheelScroll, { passive: true });
window.addEventListener('keydown', event => {
    if (['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Home', 'End', ' ', 'Escape'].includes(event.key)) stopWheelScroll();
});
window.addEventListener('click', stopWheelScroll, { passive: true });
window.addEventListener('blur', stopWheelScroll);


