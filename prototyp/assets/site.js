(() => {
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const fineMouse = matchMedia('(hover:hover) and (pointer:fine)').matches;
  const root = document.documentElement;
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => [...el.querySelectorAll(s)];
  const onScroll = fn => { addEventListener('scroll', fn, {passive:true}); fn(); };
  if (!reduce) root.classList.add('js');

  /* header: hide on scroll down, show on scroll up; progress line; phone action bar */
  const header = $('.header'), bar = $('#actionbar'), prog = $('#progress');
  let lastY = scrollY;
  onScroll(() => {
    const y = scrollY;
    prog.style.setProperty('--sp', (y / Math.max(1, root.scrollHeight - innerHeight)).toFixed(4));
    header.classList.toggle('scrolled', y > 8);
    header.classList.toggle('hide', y > 240 && y > lastY && !root.classList.contains('menu-open'));
    bar.classList.toggle('show', y > 420);
    lastY = y;
  });

  /* mobile menu */
  const burger = $('#burger');
  const setMenu = open => {
    root.classList.toggle('menu-open', open);
    burger.setAttribute('aria-expanded', open);
    burger.setAttribute('aria-label', open ? 'Zamknij menu' : 'Otwórz menu');
    document.body.style.overflow = open ? 'hidden' : '';
  };
  burger.addEventListener('click', () => setMenu(!root.classList.contains('menu-open')));
  $$('#mmenu a').forEach(a => a.addEventListener('click', () => setMenu(false)));
  addEventListener('keydown', e => { if (e.key === 'Escape') setMenu(false); });

  /* reveal on scroll; siblings in a row arrive one after another */
  if (!reduce) {
    const io = new IntersectionObserver(es => es.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
    }), {rootMargin:'0px 0px -8% 0px'});
    $$('.rv, .mask, .draw').forEach(el => io.observe(el));
    $$('.hero .rv, .hero .mask').forEach((el, i) => { el.style.transitionDelay = (i * 0.09) + 's'; });
    $$('[data-stagger] > *').forEach(el => { el.style.transitionDelay = ([...el.parentNode.children].indexOf(el) % 4 * 0.09) + 's'; });
  }

  /* headline lines rise on load */
  if (!reduce && window.gsap && $('.hero h1 .line')) {
    gsap.from('.hero h1 .line > span', {yPercent:105, duration:1.25, ease:'expo.out', stagger:.12, delay:.15});
  }

  /* before / after sliders */
  const cursor = $('#cursor');
  $$('.ba').forEach((ba, n) => {
    const range = $('input', ba), tagB = $('.tag.before', ba), tagA = $('.tag.after-t', ba);
    const setPos = v => {
      ba.style.setProperty('--pos', v + '%');
      if (tagB) tagB.style.opacity = v < 22 ? 0 : 1;
      if (tagA) tagA.style.opacity = v > 78 ? 0 : 1;
    };
    let intro = null;
    range.addEventListener('input', () => { if (intro) intro.kill(); setPos(range.value); });
    const start = +(ba.dataset.start || 50);
    if (reduce || !window.gsap) { range.value = start; setPos(start); }
    else {
      setPos(100);
      const go = () => { const s = {v:100}; intro = gsap.to(s, {v:start, duration:2.4, ease:'power3.inOut', delay: n === 0 && ba.closest('.hero') ? 1.1 : .2, onUpdate:() => { setPos(s.v); range.value = s.v; }}); };
      new IntersectionObserver((es, o) => es.forEach(e => { if (e.isIntersecting) { go(); o.disconnect(); } }), {threshold:.45}).observe(ba);
    }
    if (!reduce && fineMouse && cursor) {
      ba.classList.add('has-cursor');
      ba.addEventListener('pointerenter', () => cursor.classList.add('on'));
      ba.addEventListener('pointerleave', () => cursor.classList.remove('on'));
      ba.addEventListener('pointermove', e => { cursor.style.left = e.clientX + 'px'; cursor.style.top = e.clientY + 'px'; });
      ba.addEventListener('pointerdown', () => { cursor.textContent = '← →'; });
    }
  });
  addEventListener('pointerup', () => { if (cursor) cursor.textContent = 'Przeciągnij'; });

  /* process: active step, matching photo, gold rail */
  $$('[data-process]').forEach(proc => {
    const steps = $$('.step', proc), imgs = $$('.process-media img', proc);
    const list = $('.steps', proc), rail = $('.rail-fill', proc), no = $('.step-no', proc), name = $('.step-name', proc);
    const activate = i => {
      steps.forEach((s, k) => s.classList.toggle('on', innerWidth < 1000 ? k <= i : k === i));
      imgs.forEach((im, k) => im.classList.toggle('on', k === i));
      if (no) no.textContent = String(i + 1).padStart(2, '0');
      if (name) name.textContent = $('h3', steps[i]).textContent;
    };
    const sio = new IntersectionObserver(es => es.forEach(e => {
      if (e.isIntersecting) { list.classList.remove('idle'); activate(steps.indexOf(e.target)); }
    }), {rootMargin:'-45% 0px -45% 0px'});
    steps.forEach(s => sio.observe(s));
    onScroll(() => {
      const r = list.getBoundingClientRect();
      rail.style.setProperty('--p', Math.min(1, Math.max(0, (innerHeight * .55 - r.top) / r.height)).toFixed(3));
    });
  });

  /* gentle parallax on wide images */
  if (!reduce) {
    const paras = $$('.para');
    if (paras.length) onScroll(() => paras.forEach(img => {
      const r = img.parentElement.getBoundingClientRect();
      if (r.bottom < 0 || r.top > innerHeight) return;
      img.style.transform = `translateY(${((r.top + r.height / 2 - innerHeight / 2) / innerHeight * -6).toFixed(2)}%)`;
    }));
  }

  /* lazy images fade in once loaded */
  $$('img.lz').forEach(img => {
    const ok = () => img.classList.add('ok');
    img.complete && img.naturalWidth ? ok() : img.addEventListener('load', ok, {once:true});
  });

  /* home page: menu follows the section in view */
  const navLinks = $$('.nav a[href^="#"]');
  if (navLinks.length) {
    const spy = new IntersectionObserver(es => es.forEach(e => {
      if (e.isIntersecting) navLinks.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + e.target.id));
    }), {rootMargin:'-50% 0px -50% 0px'});
    $$('main [id]').forEach(sec => spy.observe(sec));
  }

  /* horizontal carousels on phones: gold position line */
  $$('[data-carousel]').forEach(sc => {
    const line = $('#' + sc.dataset.carousel);
    sc.addEventListener('scroll', () => line.style.setProperty('--sx', (sc.scrollLeft / Math.max(1, sc.scrollWidth - sc.clientWidth)).toFixed(3)), {passive:true});
  });

  /* line drawings (door swing) draw when seen */
  $$('.plan-art').forEach(art => new IntersectionObserver((es, o) => es.forEach(e => {
    if (e.isIntersecting) { art.classList.add('in'); o.disconnect(); }
  }), {threshold:.3}).observe(art));

  const top = $('#totop');
  if (top) top.addEventListener('click', () => scrollTo({top:0, behavior: reduce ? 'auto' : 'smooth'}));

  /* Poradnik: category filter */
  const chips = $$('.chips button');
  chips.forEach(ch => ch.addEventListener('click', () => {
    chips.forEach(c => c.setAttribute('aria-pressed', c === ch));
    const cat = ch.dataset.cat;
    $$('[data-cat-item]').forEach(it => {
      it.hidden = cat !== 'all' && it.dataset.catItem !== cat;
      if (!it.hidden) it.classList.add('in');
    });
  }));

  /* article: table of contents follows the reader */
  const article = $('.prose[data-article]');
  if (article) {
    const toc = $$('.toc a');
    const tio = new IntersectionObserver(es => es.forEach(e => {
      if (e.isIntersecting) toc.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + e.target.id));
    }), {rootMargin:'-20% 0px -70% 0px'});
    $$('h2[id]', article).forEach(h => tio.observe(h));
    const share = $('[data-copy]');
    if (share) share.addEventListener('click', () => {
      navigator.clipboard?.writeText(location.href).then(() => { share.textContent = 'Skopiowano'; setTimeout(() => { share.textContent = 'Kopiuj link'; }, 2000); }, () => {});
    });
  }

  /* forms (prototype: nothing is sent) */
  $$('form[data-news]').forEach(f => f.addEventListener('submit', e => {
    e.preventDefault();
    const ok = /.+@.+\..+/.test($('input[type=email]', f).value.trim()), consent = $('input[type=checkbox]', f).checked;
    $('.msg', f).textContent = !ok ? 'Wpisz poprawny adres e-mail.' : !consent ? 'Zaznacz zgodę, aby się zapisać.' : 'Dziękujemy. Potwierdzenie zapisu przyjdzie na podany adres.';
  }));

  const form = $('#contact-form');
  if (form) form.addEventListener('submit', e => {
    e.preventDefault();
    let first = null;
    $$('[data-req]', form).forEach(f => {
      const input = $('input, textarea', f), err = $('.err', f);
      const v = input.value.trim();
      const bad = input.type === 'checkbox' ? !input.checked
        : input.name === 'contact' ? !(/.+@.+\..+/.test(v) || v.replace(/\D/g, '').length >= 9)
        : v.length < 2;
      f.classList.toggle('invalid', bad);
      input.setAttribute('aria-invalid', bad);
      if (err) err.hidden = !bad;
      if (bad && !first) first = input;
    });
    if (first) { first.focus(); return; }
    form.classList.add('sent');
    $('#form-done').hidden = false;
    $('#form-done').focus();
  });
})();
