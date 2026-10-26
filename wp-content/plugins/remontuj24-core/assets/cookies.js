/* Cookie consent: stores the choice for 6 months and loads analytics only after "yes". */
(() => {
  const bar = document.getElementById('cookie-bar');
  if (!bar) return;
  const cfg = window.r24Consent || {};
  const KEY = 'r24_consent';

  const read = () => {
    const m = document.cookie.match(/(?:^|; )r24_consent=([^;]*)/);
    if (!m) return null;
    try { return JSON.parse(decodeURIComponent(m[1])); } catch (e) { return null; }
  };
  const write = c => {
    document.cookie = `${KEY}=${encodeURIComponent(JSON.stringify(c))}; max-age=${60 * 60 * 24 * 182}; path=/; SameSite=Lax`;
  };

  let loaded = {};
  const apply = c => {
    if (c.analytics && !loaded.analytics) {
      loaded.analytics = true;
      if (cfg.ga4) {
        const s = document.createElement('script');
        s.async = true;
        s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(cfg.ga4);
        document.head.appendChild(s);
        window.dataLayer = window.dataLayer || [];
        window.gtag = function () { dataLayer.push(arguments); };
        gtag('js', new Date());
        gtag('config', cfg.ga4, { anonymize_ip: true });
      }
      const tpl = document.getElementById('r24-consent-code');
      if (tpl) {
        // Scripts from a <template> do not run when cloned, so re-create them.
        [...tpl.content.childNodes].forEach(node => {
          if (node.nodeName === 'SCRIPT') {
            const s = document.createElement('script');
            [...node.attributes].forEach(a => s.setAttribute(a.name, a.value));
            s.textContent = node.textContent;
            document.head.appendChild(s);
          } else {
            document.head.appendChild(node.cloneNode(true));
          }
        });
      }
    }
    if (c.marketing && !loaded.marketing && cfg.pixel) {
      loaded.marketing = true;
      /* Meta Pixel base code */
      !function (f, b, e, v, n, t, s) { if (f.fbq) return; n = f.fbq = function () { n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments); }; if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0'; n.queue = []; t = b.createElement(e); t.async = !0; t.src = v; s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s); }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', cfg.pixel);
      fbq('track', 'PageView');
    }
  };

  const options = bar.querySelector('.cookie-options');
  const show = () => {
    const c = read() || {};
    bar.querySelectorAll('.cookie-options input[name]').forEach(i => { i.checked = !!c[i.name]; });
    bar.hidden = false;
    requestAnimationFrame(() => bar.classList.add('on'));
  };
  const hide = () => { bar.classList.remove('on'); setTimeout(() => { bar.hidden = true; }, 400); };
  const decide = c => { write(c); apply(c); hide(); };

  bar.addEventListener('click', e => {
    const b = e.target.closest('[data-consent]');
    if (!b) return;
    const what = b.dataset.consent;
    if (what === 'all') decide({ necessary: true, analytics: true, marketing: true });
    else if (what === 'necessary') decide({ necessary: true, analytics: false, marketing: false });
    else if (what === 'choose') {
      options.hidden = false;
      b.hidden = true;
      bar.querySelector('[data-consent="save"]').hidden = false;
    } else if (what === 'save') {
      const c = { necessary: true };
      bar.querySelectorAll('.cookie-options input[name]').forEach(i => { c[i.name] = i.checked; });
      decide(c);
    }
  });

  document.addEventListener('click', e => {
    const a = e.target.closest('a[href$="#cookie-settings"], [data-cookie-settings]');
    if (!a) return;
    e.preventDefault();
    show();
  });

  const saved = read();
  if (saved) apply(saved);
  else show();
})();
