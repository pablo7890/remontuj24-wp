// Exports every logo variant from brand/logo/*.svg to PNG in several sizes,
// plus favicons and social images. Usage: node brand/tools/export_logo_pack.js <out-dir>
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const SRC = path.join(__dirname, '..', 'logo');
const OUT = process.argv[2] || path.join(__dirname, '..', '..', 'dist', 'logo-remontuj24');
const read = f => fs.readFileSync(path.join(SRC, f), 'utf8');
const box = svg => svg.match(/viewBox="0 0 ([\d.]+) ([\d.]+)"/).slice(1).map(Number);

// folder, source files, PNG widths
const GROUPS = [
  ['01-logo-z-haslem', ['logo-poziome.svg', 'logo-poziome-negatyw.svg', 'logo-czarne.svg', 'logo-biale.svg'], [400, 800, 1600, 3200]],
  ['02-logo-bez-hasla', ['logo-poziome-bez-hasla.svg', 'logo-poziome-bez-hasla-negatyw.svg'], [400, 800, 1600, 3200]],
  ['03-logo-pionowe', ['logo-pionowe.svg', 'logo-pionowe-negatyw.svg'], [400, 800, 1600, 3200]],
  ['04-sygnet', ['sygnet.svg', 'sygnet-negatyw.svg'], [64, 128, 256, 512, 1024, 2048]],
  ['05-napis', ['napis.svg'], [400, 800, 1600, 3200]],
];

(async () => {
  fs.rmSync(OUT, { recursive: true, force: true });
  const browser = await chromium.launch();
  const page = await browser.newPage();
  // One render: the SVG scaled to an exact pixel size, optionally on a solid background.
  const render = async (svg, w, h, file, bg = null, pad = 0) => {
    await page.setViewportSize({ width: w, height: h });
    const inner = svg.replace(/<svg /, `<svg style="position:absolute;left:${pad}px;top:${pad}px;width:${w - 2 * pad}px;height:${h - 2 * pad}px" `);
    await page.setContent(`<html><body style="margin:0;background:${bg || 'transparent'}">${inner}</body></html>`);
    fs.mkdirSync(path.dirname(file), { recursive: true });
    await page.screenshot({ path: file, omitBackground: !bg });
  };

  for (const [dir, files, widths] of GROUPS) {
    for (const f of files) {
      const svg = read(f);
      const [vw, vh] = box(svg);
      const name = f.replace('.svg', '');
      fs.mkdirSync(path.join(OUT, dir, 'svg'), { recursive: true });
      fs.copyFileSync(path.join(SRC, f), path.join(OUT, dir, 'svg', f));
      for (const w of widths) {
        await render(svg, w, Math.round(w * vh / vw), path.join(OUT, dir, 'png', `${name}-${w}px.png`));
      }
    }
  }

  // favicons and app icons: light (default) in 06-favicon, dark in 06-favicon/ciemne
  const F = path.join(OUT, '06-favicon');
  for (const [dir, suffix] of [[F, ''], [path.join(F, 'ciemne'), '-ciemna']]) {
    const fav = read(`favicon${suffix}.svg`), app = read(`ikona-aplikacji${suffix}.svg`);
    fs.mkdirSync(dir, { recursive: true });
    fs.copyFileSync(path.join(SRC, `favicon${suffix}.svg`), path.join(dir, 'favicon.svg'));
    for (const s of [16, 32, 48, 64]) await render(fav, s, s, path.join(dir, `favicon-${s}x${s}.png`));
    await render(app, 180, 180, path.join(dir, 'apple-touch-icon.png'));
    for (const s of [192, 512]) await render(app, s, s, path.join(dir, `android-chrome-${s}x${s}.png`));
    fs.copyFileSync(path.join(SRC, `ikona-aplikacji${suffix}.svg`), path.join(dir, 'ikona-aplikacji.svg'));
  }

  // social media: profile picture and link preview
  const S = path.join(OUT, '07-social-media');
  await render(read('ikona-aplikacji.svg'), 1080, 1080, path.join(S, 'avatar-1080x1080.png'));
  await render(read('ikona-aplikacji-ciemna.svg'), 1080, 1080, path.join(S, 'avatar-ciemny-1080x1080.png'));
  const logo = read('logo-poziome.svg');
  await page.setViewportSize({ width: 1200, height: 630 });
  await page.setContent(`<html><body style="margin:0;width:1200px;height:630px;background:#F7F5F1;display:grid;place-items:center">${logo.replace('<svg ', '<svg style="width:720px;height:auto" ')}</body></html>`);
  await page.screenshot({ path: path.join(S, 'udostepnianie-1200x630.png') });
  await browser.close();
  console.log('done', OUT);
})();
