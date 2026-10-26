"""Generates the Remontuj24 logo set as SVG with outlined type.

Usage: python3 build_logo.py <fonts_dir> <out_dir>
Fonts: Cormorant Garamond 500 and Inter 500 static TTFs (Google Fonts, OFL).
"""
import sys
from pathlib import Path
from fontTools.ttLib import TTFont
from fontTools.pens.svgPathPen import SVGPathPen
from fontTools.pens.transformPen import TransformPen

FONTS, OUT = Path(sys.argv[1]), Path(sys.argv[2])
OUT.mkdir(parents=True, exist_ok=True)

INK, GOLD, PAPER, NIGHT = "#171717", "#B38A50", "#F7F5F1", "#111111"
SERIF = TTFont(FONTS / "Cormorant_Garamond_wght_500.ttf")
SANS = TTFont(FONTS / "Inter_wght_500.ttf")


def run(font, text, size, tracking=0.0, x=0.0, baseline=0.0):
    """Outline `text`; returns (path data, advance width in px)."""
    upm = font["head"].unitsPerEm
    cmap, gs, hmtx = font.getBestCmap(), font.getGlyphSet(), font["hmtx"]
    s = size / upm
    d, pen_x = [], x
    for i, ch in enumerate(text):
        name = cmap[ord(ch)]
        if ch.isdigit() and f"{name}.lf" in gs:
            name = f"{name}.lf"  # lining figures sit on the caps baseline
        pen = SVGPathPen(gs)
        gs[name].draw(TransformPen(pen, (s, 0, 0, -s, pen_x, baseline)))
        d.append(pen.getCommands())
        pen_x += hmtx[name][0] * s
        if i < len(text) - 1:
            pen_x += tracking * size
    return " ".join(p for p in d if p), pen_x - x


def mark(x, y, k, wall, leaf, arc, weight=1.0):
    """Room plan with a door swing on a 48-unit grid, scaled by k."""
    sw = 2.6 * weight * k
    p = lambda a, b: f"{x + a * k:.2f} {y + b * k:.2f}"
    return (
        f'<path d="M{p(12, 44)} H{x + 4 * k:.2f} V{y + 4 * k:.2f} H{x + 44 * k:.2f} V{y + 44 * k:.2f} H{x + 28 * k:.2f}" '
        f'fill="none" stroke="{wall}" stroke-width="{sw:.2f}" stroke-linecap="square" stroke-linejoin="miter"/>'
        f'<path d="M{p(12, 44)} V{y + 28 * k:.2f}" stroke="{leaf}" stroke-width="{1.6 * weight * k:.2f}" stroke-linecap="square"/>'
        f'<path d="M{p(12, 28)} A{16 * k:.2f} {16 * k:.2f} 0 0 1 {p(28, 44)}" fill="none" stroke="{arc}" stroke-width="{1.4 * weight * k:.2f}"/>'
    )


def svg(w, h, body, bg=None, title="Remontuj24"):
    rect = f'<rect width="{w:.0f}" height="{h:.0f}" fill="{bg}"/>' if bg else ""
    return (f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w:.0f} {h:.0f}" width="{w:.0f}" height="{h:.0f}" '
            f'role="img" aria-label="{title}"><title>{title}</title>{rect}{body}</svg>\n')


def wordmark(x, baseline, ink, gold, size=34):
    d1, w1 = run(SERIF, "REMONTUJ", size, 0.06, x, baseline)
    d2, w2 = run(SERIF, "24", size, 0.02, x + w1 + 0.1 * size, baseline)
    return f'<path d="{d1}" fill="{ink}"/><path d="{d2}" fill="{gold}"/>', w1 + 0.1 * size + w2


TAGLINE = "OD KLUCZY DO GOTOWEGO WNĘTRZA"


def horizontal(ink, gold, bg=None, tagline=True):
    pad, m = 8, 56
    word, ww = wordmark(pad + m + 18, pad + (34 if tagline else 39), ink, gold)
    body = mark(pad, pad, m / 48, ink, ink, gold) + word
    w = pad + m + 18 + ww
    if tagline:
        td, tw = run(SANS, TAGLINE, 7.6, 0.28, pad + m + 19, pad + 52)
        body += f'<path d="{td}" fill="{ink}" fill-opacity="0.72"/>'
        w = max(w, pad + m + 19 + tw)
    return svg(w + pad, m + 2 * pad, body, bg)


def stacked(ink, gold, bg=None):
    word, ww = wordmark(0, 0, ink, gold, size=40)
    td, tw = run(SANS, TAGLINE, 8.4, 0.3, 0, 0)
    W = max(ww, tw) + 48
    m = 72
    body = mark((W - m) / 2, 24, m / 48, ink, ink, gold)
    word, _ = wordmark((W - ww) / 2, 24 + m + 52, ink, gold, size=40)
    td, _ = run(SANS, TAGLINE, 8.4, 0.3, (W - tw) / 2, 24 + m + 76)
    body += word + f'<path d="{td}" fill="{ink}" fill-opacity="0.72"/>'
    return svg(W, 24 + m + 76 + 24, body, bg)


def symbol(wall, gold, bg=None, size=48, weight=1.0, inset=0):
    k = (size - 2 * inset) / 48
    return svg(size, size, mark(inset, inset, k, wall, wall, gold, weight), bg)


def only_wordmark(ink, gold):
    word, ww = wordmark(4, 32, ink, gold)
    return svg(ww + 8, 42, word)


files = {
    "logo-poziome.svg": horizontal(INK, GOLD),
    "logo-poziome-negatyw.svg": horizontal(PAPER, GOLD, NIGHT),
    "logo-poziome-bez-hasla.svg": horizontal(INK, GOLD, tagline=False),
    "logo-poziome-bez-hasla-negatyw.svg": horizontal(PAPER, GOLD, NIGHT, tagline=False),
    "logo-pionowe.svg": stacked(INK, GOLD),
    "logo-pionowe-negatyw.svg": stacked(PAPER, GOLD, NIGHT),
    "logo-czarne.svg": horizontal(INK, INK),
    "logo-biale.svg": horizontal("#FFFFFF", "#FFFFFF"),
    "napis.svg": only_wordmark(INK, GOLD),
    "sygnet.svg": symbol(INK, GOLD),
    "sygnet-negatyw.svg": symbol(PAPER, GOLD, NIGHT, inset=6),
    "favicon.svg": symbol(PAPER, GOLD, NIGHT, size=48, weight=1.6, inset=7),
    "ikona-aplikacji.svg": symbol(PAPER, GOLD, NIGHT, size=512, weight=1.2, inset=120),
}
for name, data in files.items():
    (OUT / name).write_text(data, encoding="utf-8")
print("\n".join(files))
