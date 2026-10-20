import React, { useEffect, useRef, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { animate, motion, MotionConfig, useMotionValueEvent, useScroll, useSpring, useTransform } from 'motion/react';

// Parameters read from the published reference, rather than guessed durations.
const dashboardSpring = { type: 'spring', stiffness: 400, damping: 100, mass: 1, restDelta: .0001 };
const imageSpring = { type: 'spring', stiffness: 150, damping: 37, mass: 1, restDelta: .0001 };
const entranceSpring = { type: 'spring', stiffness: 150, damping: 30, mass: 1 };
const slideSpring = { type: 'spring', bounce: 0, duration: .6 };

function HeroWords({ lines, delay = 0 }) {
    const reduced = false;
    let index = 0;
    return lines.map((line, lineIndex) => <React.Fragment key={lineIndex}>
        {lineIndex > 0 && <br />}
        {line.trim().split(/\s+/).map((word, wordIndex) => {
            const order = index++;
            return <React.Fragment key={wordIndex}>
                {wordIndex > 0 && ' '}
                <motion.span className="hero-word" initial={reduced ? false : { opacity: .001, y: 10, filter: 'blur(10px)' }} animate={{ opacity: 1, y: 0, filter: 'blur(0px)' }} transition={reduced ? { duration: 0 } : { type: 'spring', stiffness: 163, damping: 40, mass: 1, delay: delay + order * .05 }}>{word}</motion.span>
            </React.Fragment>;
        })}
    </React.Fragment>);
}

function LandingMotion({ host, mobileImage, webImage }) {
    const reduced = false;
    const [device, setDevice] = useState('mobile');
    const [mobile, setMobile] = useState(window.innerWidth <= 760);
    const dashboardRef = useRef(document.querySelector('.dashboard-stage'));
    const deviceRef = useRef(host);
    const { scrollY } = useScroll();

    // Start when the top meets the viewport bottom; finish when its bottom does.
    // At 1440 × 900 this reproduces ~.892 scale at y=0 and full scale at y≈380.
    const { scrollYProgress: dashboardProgress } = useScroll({ target: dashboardRef, offset: ['start end', 'end end'] });
    const { scrollYProgress: deviceProgress } = useScroll({ target: deviceRef, offset: ['start end', 'end end'] });
    const dashboardPosition = useSpring(dashboardProgress, dashboardSpring);
    const imagePosition = useSpring(deviceProgress, imageSpring);
    const imageScale = useTransform(imagePosition, [0, 1], [1.3, 1]);

    function paintDashboard(value) {
        const element = dashboardRef.current.querySelector('.workspace');
        if (reduced) { element.style.removeProperty('transform'); return; }
        const p = Math.max(0, Math.min(1, value));
        const scale = mobile ? 1 : .8 + .2 * p;
        element.style.transform = `perspective(1000px) translateY(${(mobile ? -32 : -80) * (1 - p)}px) scale(${scale}) rotateX(${20 * (1 - p)}deg)`;
    }
    useMotionValueEvent(dashboardPosition, 'change', paintDashboard);
    useMotionValueEvent(scrollY, 'change', value => {
        document.querySelector('header').classList.toggle('is-scrolled', value > 24);
    });

    useEffect(() => {
        document.documentElement.classList.add('react-motion');
        const resize = () => setMobile(window.innerWidth <= 760);
        window.addEventListener('resize', resize);
        const header = document.querySelector('header');
        header.classList.toggle('is-scrolled', window.scrollY > 24);
        const actions = document.querySelector('.hero-copy .actions');
        const actionAnimation = reduced ? null : animate(actions, { opacity: [0, 1] }, { duration: .7, delay: 1.25, ease: [.44, 0, .56, 1] });
        return () => {
            actionAnimation?.stop();
            actions.style.removeProperty('opacity');
            window.removeEventListener('resize', resize);
            document.documentElement.classList.remove('react-motion');
        };
    }, [reduced]);

    useEffect(() => {
        paintDashboard(dashboardPosition.get());
    }, [reduced, mobile]);

    // The video uses one entrance for each section, not repeated opacity changes
    // on its heading and an extra scale applied to the whole phone photograph.
    useEffect(() => {
        const sections = [...document.querySelectorAll('.benefits, .pricing, .footer-card')];
        const animations = new Map();
        if (reduced) {
            sections.forEach(element => { element.style.removeProperty('opacity'); element.style.removeProperty('transform'); });
            return;
        }
        const observer = new IntersectionObserver(entries => entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const element = entry.target;
            animations.set(element, animate(element, { opacity: [0, 1], y: [mobile ? 24 : 64, 0] }, entranceSpring));
            observer.unobserve(element);
        }), { threshold: 0 });
        sections.forEach(element => {
            element.style.opacity = '0';
            observer.observe(element);
        });
        return () => {
            observer.disconnect();
            animations.forEach(animation => animation.stop());
            sections.forEach(element => { element.style.removeProperty('opacity'); element.style.removeProperty('transform'); });
        };
    }, [reduced, mobile]);

    const transition = reduced ? { duration: 0 } : slideSpring;
    return <>
        <motion.div className="device-images" style={{ scale: reduced ? 1 : imageScale }}>
            <motion.img className="mobile-photo device-photo" src={mobileImage} alt="Escopo Fácil no celular" width="1417" height="941" initial={false} animate={{ x: device === 'mobile' ? '0%' : '-100%' }} transition={transition} aria-hidden={device !== 'mobile'} />
            <motion.img className="web-photo device-photo" src={webImage} alt="Escopo Fácil no tablet" width="1672" height="941" initial={false} animate={{ x: device === 'web' ? '0%' : '100%' }} transition={transition} aria-hidden={device !== 'web'} />
        </motion.div>
        <div className="web-cta device-switch" role="group" aria-label="Visualização da plataforma">
            {[['mobile', 'App Mobile'], ['web', 'Versão Web']].map(([value, label]) => <button key={value} type="button" data-device-option={value} aria-pressed={device === value} onClick={() => setDevice(value)} onFocus={() => setDevice(value)} onPointerEnter={event => { if (event.pointerType === 'mouse') setDevice(value); }}>
                {device === value && <motion.span className="device-active-pill" layoutId="device-active-pill" transition={transition} style={{ background: value === 'mobile' ? '#8b0836' : '#292625' }} />}
                <span className="device-option-label">{label}</span>
            </button>)}
        </div>
    </>;
}

const host = document.querySelector('.mobile-scene');
if (host) {
    const mobileImage = host.querySelector('.mobile-photo').src;
    const webImage = host.querySelector('.web-photo').src;
    document.documentElement.classList.add('react-motion');
    for (const [selector, delay] of [['.hero-copy h1', 0], ['.hero-copy > p', .1]]) {
        const element = document.querySelector(selector);
        const lines = [''];
        element.childNodes.forEach(node => {
            if (node.nodeName === 'BR') lines.push('');
            else lines[lines.length - 1] += node.textContent;
        });
        createRoot(element).render(<HeroWords lines={lines} delay={delay} />);
    }
    createRoot(host).render(<MotionConfig reducedMotion="never"><LandingMotion host={host} mobileImage={mobileImage} webImage={webImage} /></MotionConfig>);
}
