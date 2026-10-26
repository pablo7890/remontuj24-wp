"""Builds the clickable prototype from src/ into this folder.

Usage: python3 build.py
Articles in src/articles/*.txt (front matter, "---", then one paragraph per line,
"## " for headings, "> " for the sign-off) become <slug>.html pages, the way
Poradnik posts will work in WordPress. {{card:slug}} and {{featured:slug}} place
an article teaser on any page.
Each page in src/pages/ starts with a front-matter block:
    <!--
    title: Jak działamy
    nav: jak-dzialamy
    -->
The page body is wrapped in the shared head, header, footer and scripts.
index.html is written without <html>/<head> tags because the artifact host
adds its own skeleton for the main page; every other page is a full document.
"""
import html
import re
from pathlib import Path

HERE = Path(__file__).parent
SRC = HERE / "src"
PART = {p.stem: p.read_text(encoding="utf-8") for p in (SRC / "partials").glob("*.html")}

HEAD = """<title>{title}</title>
<meta name="description" content="{description}">
<meta name="claude:viewport-fit-sides" content="cover">
<link rel="icon" href="logo/favicon.svg" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,500&family=Inter:wght@400;500;600&display=swap">
<link rel="stylesheet" href="assets/site.css">"""

FULL_DOC = """<!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
{head}
</head>
<body>
{body}
</body>
</html>
"""

ARROW = '<span class="arrow" aria-hidden="true"><i><svg><use href="#arr"/></svg></i><i><svg><use href="#arr"/></svg></i></span>'
SYGNET = ('<svg class="door draw" viewBox="0 0 48 48" aria-hidden="true"><path class="w" pathLength="1" d="M12 44 H4 V4 H44 V44 H28"/>'
          '<path class="w" pathLength="1" d="M12 44 V28" style="stroke-width:1.2"/><path class="a" pathLength="1" d="M12 28 A16 16 0 0 1 28 44"/></svg>')

SCRIPTS = """<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="assets/site.js"></script>"""


def polish_typography(html):
    """Keeps one-letter words with the next word and dashes with the previous one.

    Only text between tags is touched; <script> and <style> blocks are skipped.
    """
    out, skip = [], False
    for part in re.split(r"(<[^>]+>)", html):
        if part.startswith("<"):
            low = part.lower()
            if low.startswith(("<script", "<style")):
                skip = True
            elif low.startswith(("</script", "</style")):
                skip = False
            out.append(part)
            continue
        if not skip and part.strip():
            part = re.sub(r"(?:(?<=[\s(„])|^)([aiouwzAIOUWZ]) +(?=\S)", r"\1&nbsp;", part)
            part = re.sub(r"(?:(?<=[\s(„])|^)([aiouwzAIOUWZ]) +(?=\S)", r"\1&nbsp;", part)
            part = re.sub(r"(?<=\S) +—", "&nbsp;—", part)
        out.append(part)
    return "".join(out)


def load_articles():
    arts = {}
    for f in sorted((SRC / "articles").glob("*.txt")):
        head, body = f.read_text(encoding="utf-8").split("\n---\n", 1)
        meta = dict(line.split(": ", 1) for line in head.splitlines())
        meta["slug"], meta["lines"] = f.stem, [l for l in body.splitlines() if l.strip()]
        meta["minutes"] = max(1, round(len(body.split()) / 180))
        arts[f.stem] = meta
    return arts


ARTICLES = load_articles()
E = lambda t: html.escape(t, quote=False)


def img_size(name):
    from PIL import Image
    with Image.open(HERE / "img" / name) as im:
        return im.size


def teaser(slug, featured=False):
    a = ARTICLES[slug]
    w, h = img_size(f'{a["card"]}-800.webp')
    img = (f'<img class="lz" src="img/{a["card"]}-800.webp" srcset="img/{a["card"]}-480.webp 480w, img/{a["card"]}-800.webp 800w" '
           f'sizes="(min-width:960px) {"56vw" if featured else "30vw"}, 100vw" alt="{a["alt"]}" loading="lazy" decoding="async" width="{w}" height="{h}">')
    meta = f'<div class="meta"><span>{E(a["category"])}</span><i></i><span>{a["minutes"]} min czytania</span></div>'
    link = '<span class="link">Czytaj {{arrow}}</span>'
    if featured:
        return (f'<a class="featured rv" href="{slug}.html" data-cat-item="{a["category"]}"><div class="ph">{img}</div>'
                f'<div class="copy card">{meta}<h2>{E(a["title"])}</h2><p>{E(a["excerpt"])}</p>{link}</div></a>')
    return (f'<a class="card rv" href="{slug}.html" data-cat-item="{a["category"]}"><div class="ph">{img}</div>'
            f'{meta}<h3>{E(a["title"])}</h3><p>{E(a["excerpt"])}</p>{link}</a>')


def article_page(a):
    """Renders one Poradnik article as a page source with front matter."""
    toc, out, n = [], [], 0
    for i, line in enumerate(a["lines"]):
        if line.startswith("## "):
            text = line[3:]
            m = re.match(r"(\d+)\.\s+(.*)", text)
            n += 1
            hid = f"s{n}"
            if m:
                out.append(f'<h2 id="{hid}"><span class="num">{int(m.group(1)):02d}</span><span>{E(m.group(2))}</span></h2>')
                toc.append(f'<a href="#{hid}"><b>{int(m.group(1)):02d}</b><span>{E(m.group(2))}</span></a>')
            else:
                out.append(f'<h2 id="{hid}" class="plain">{E(text)}</h2>')
                toc.append(f'<a href="#{hid}"><b>·</b><span>{E(text)}</span></a>')
        elif line.startswith("> "):
            out.append(f'<p class="signoff">{E(line[2:])}</p>')
        else:
            out.append(f'<p{" class=\"lead\"" if i == 0 else ""}>{E(line)}</p>')
    others = [s for s in ARTICLES if s != a["slug"]]
    img = a["image"]
    return f"""<!--
title: Poradnik
nav: poradnik
doctitle: {a["title"]} · Remontuj24
description: {a["description"]}
-->
<article>
  <header class="article-head hero">
    <div class="wrap">
      <nav class="crumbs rv" aria-label="Okruszki"><a href="index.html">Strona główna</a><span aria-hidden="true">/</span><a href="poradnik.html">Poradnik</a><span aria-hidden="true">/</span><span>{E(a["category"])}</span></nav>
      <div class="meta rv"><span>{E(a["category"])}</span><i></i><span>{a["minutes"]} min czytania</span><i></i><span>{a["date"]}</span></div>
      <h1 class="rv">{E(a["title"])}</h1>
    </div>
  </header>
  <div class="wrap"><figure class="article-cover mask"><img src="img/{img}-1600.webp" srcset="img/{img}-640.webp 640w, img/{img}-1024.webp 1024w, img/{img}-1600.webp 1600w" sizes="(min-width:1320px) 1240px, 100vw" alt="{a["alt"]}" width="1600" height="900" fetchpriority="high"></figure></div>
  <div class="wrap article-body">
    <nav class="toc" aria-label="Spis treści"><h2>W artykule</h2>{"".join(toc)}</nav>
    <div class="prose" data-article>
      {"".join(out)}
      <div class="cta-box">
        <span class="eyebrow">Remontuj24 · Trójmiasto</span>
        <h3>Planujesz wykończenie mieszkania?</h3>
        <p>Krótka rozmowa pozwala ustalić, czego wymaga mieszkanie i w jaki sposób najlepiej poprowadzić kolejne etapy.</p>
        <a class="btn" href="kontakt.html">{E(a.get("cta", "Porozmawiajmy o mieszkaniu"))} {{{{arrow}}}}</a>
      </div>
      <div class="share"><span>Udostępnij</span><button type="button" data-copy>Kopiuj link</button></div>
    </div>
    <aside class="aside-note"><b>Jeden kontakt. Jeden proces.</b><span>Organizujemy i koordynujemy wykończenie mieszkań w Gdańsku, Gdyni i Sopocie, również dla właścicieli spoza regionu.</span><a class="link" href="jak-dzialamy.html">Zobacz, jak działamy {{{{arrow}}}}</a></aside>
  </div>
</article>

<section class="section related" aria-labelledby="h-rel" style="padding-top:0">
  <div class="wrap">
    <div class="section-head split">
      <div style="display:grid;gap:22px">
        <span class="eyebrow rv">Poradnik</span>
        <h2 id="h-rel" class="rv">Czytaj dalej<span class="dot">.</span></h2>
      </div>
      <a class="link rv" href="poradnik.html" style="justify-self:start;align-self:end">Wszystkie artykuły {{{{arrow}}}}</a>
    </div>
    <div class="guide-grid two" data-stagger>{"".join("{{card:%s}}" % s for s in others)}</div>
  </div>
</section>
"""


def fix_dimensions(page_html):
    """Sets width/height on every local <img> from the file itself, so layout never shifts."""
    def fix(m):
        tag = m.group(0)
        src = re.search(r'src="img/([^"]+)"', tag)
        if not src:
            return tag
        w, h = img_size(src.group(1))
        tag = re.sub(r'\s(width|height)="\d+"', "", tag)
        return tag[:-1] + f' width="{w}" height="{h}">'
    return re.sub(r"<img\b[^>]*>", fix, page_html)


def render(name, raw):
    meta_block = re.match(r"\s*<!--(.*?)-->", raw, re.S)
    meta = dict(re.findall(r"^\s*(\w+):\s*(.+?)\s*$", meta_block.group(1), re.M))
    body = raw[meta_block.end():]
    body = re.sub(r"\{\{featured:([\w-]+)\}\}", lambda m: teaser(m.group(1), True), body)
    body = re.sub(r"\{\{card:([\w-]+)\}\}", lambda m: teaser(m.group(1)), body)
    body = body.replace("{{arrow}}", ARROW).replace("{{sygnet}}", SYGNET)

    header = PART["header"]
    for key in re.findall(r"\{\{a:([\w-]+)\}\}", header):
        mark = ' class="active" aria-current="page"' if key == meta.get("nav") else ""
        header = header.replace("{{a:%s}}" % key, mark)

    ribbon = f'<div class="proto"><div class="wrap"><b>Prototyp</b> {meta["title"]}<span class="hint"> · wszystkie podstrony są klikalne</span></div></div>'
    page_html = "\n".join([PART["sprite"], ribbon, header, "<main>", body.strip(), "</main>", PART["footer"], SCRIPTS])
    page_html = fix_dimensions(polish_typography(page_html))
    head = HEAD.format(title=meta["doctitle"] if "doctitle" in meta else f'{meta["title"]} · Remontuj24',
                       description=meta.get("description", ""))
    if name == "index.html":
        return head + "\n" + page_html + "\n"
    return FULL_DOC.format(head=head, body=page_html)


pages = {p.name: p.read_text(encoding="utf-8") for p in sorted((SRC / "pages").glob("*.html"))}
pages.update({f"{slug}.html": article_page(a) for slug, a in ARTICLES.items()})
for name, raw in pages.items():
    (HERE / name).write_text(render(name, raw), encoding="utf-8")
    print("built", name)
