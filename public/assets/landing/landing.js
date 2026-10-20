const toggle = document.querySelector('.menu');
const nav = document.querySelector('#navigation');
function closeMenu() { toggle.setAttribute('aria-expanded', 'false'); nav.classList.remove('is-open'); }
toggle.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(open));
    nav.classList.toggle('is-open', open);
});


nav.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') { closeMenu(); toggle.focus(); }
});
document.querySelectorAll('[data-period]').forEach(button => {
    button.addEventListener('click', () => {
        const period = button.dataset.period;
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
    if (document.querySelector('#login-modal[open]')) { stopWheelScroll(); return; }
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


