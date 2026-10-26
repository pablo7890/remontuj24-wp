"""Crops and exports the client's photos as responsive WebP sets.

Usage: python3 prepare_images.py <source_dir> <out_dir>
Each entry: output name, source file, crop box as fractions (left, top, right, bottom)
of the source, and target widths. Widths above 1.35x the cropped source are skipped
so we never ship a blurry upscale; the largest kept size is the fallback.
"""
import sys
from pathlib import Path
from PIL import Image, ImageFilter

SRC, OUT = Path(sys.argv[1]), Path(sys.argv[2])
OUT.mkdir(parents=True, exist_ok=True)

BA = "53346207-CC81-4F12-ADA4-0E38DB34C1CD.png"  # 2x2 before/after grid
JOBS = [
    # hero before/after (same viewpoint), landscape for desktop, portrait for phones
    ("hero-przed", BA, (0, 0, .5, .5), (640, 1024)),
    ("hero-po", BA, (.5, 0, 1, .5), (640, 1024)),
    ("hero-przed-m", BA, (.09, 0, .425, .5), (480, 640)),
    ("hero-po-m", BA, (.575, 0, .91, .5), (480, 640)),
    ("lazienka-przed", BA, (0, .5, .5, 1), (640, 1024)),
    ("lazienka-po", BA, (.5, .5, 1, 1), (640, 1024)),
    # process story
    ("proces-1", "991F93F2-4012-4922-9EEF-379DCE21EB13.png", (0, 0, 1, 1), (640, 1024, 1600)),
    ("proces-2", "Inspiracje grfika na samym dole.png", (0, 0, 1, 1), (640, 1024, 1600)),
    ("proces-3", "A30C3496-B1AC-49D5-BA58-46A6F86A7BA7.png", (0, 0, 1, 1), (640, 1024, 1600)),
    ("proces-4", "7C83EAF6-1C93-4119-A68E-1276A1982B1B.png", (0, 0, 1, 1), (640, 1024, 1600)),
    ("proces-5", "DA667AF4-81F5-44E2-8F30-D5B549E06B52.png", (0, 0, 1, 1), (640, 1024, 1600)),
    ("proces-6", "691B7951-57B7-4923-A836-A1AF8D3DF4CB.png", (0, 0, 1, 1), (640, 1024, 1600)),
    # styles, portrait 4:5 crops
    ("styl-japandi", "inspiracje japandi.png", (.2, 0, .8, 1), (480, 800)),
    ("styl-modern-classic", "inspiracje modern classic.png", (.2, 0, .8, 1), (480, 800)),
    ("styl-soft-minimal", "inspiracje soft minimal (Canva).jpg", (.1, 0, .633, 1), (480, 800)),
    ("styl-elegant-natural", "inspiracje elegant natural.png", (.1, 0, .7, 1), (480, 800)),
    # Trójmiasto + remote coordination
    ("gdansk", "86A6722B-C621-422B-BB18-E428242C404C.png", (0, 0, 1, 1), (640, 1024, 1600)),
    ("zdalnie", "60BB81A8-28AF-42B5-94C2-AB819E81631F.png", (0, 0, 1, 1), (640, 1024, 1600)),
    # Poradnik cards
    ("poradnik-1", "991F93F2-4012-4922-9EEF-379DCE21EB13.png", (0, 0, 1, 1), (480, 800)),
    ("poradnik-2", "A30C3496-B1AC-49D5-BA58-46A6F86A7BA7.png", (.15, 0, .85, 1), (480, 800)),
    ("poradnik-3", "7C83EAF6-1C93-4119-A68E-1276A1982B1B.png", (.3, 0, 1, 1), (480, 800)),
    # closing band
    ("panorama", "89513458-60A5-4C65-AE8B-496FEA130DF4.png", (0, 0, 1, 1), (1024, 1600, 1940)),
]


def crop(im, box):
    w, h = im.size
    return im.crop((round(box[0] * w), round(box[1] * h), round(box[2] * w), round(box[3] * h)))


for name, src, box, widths in JOBS:
    im = crop(Image.open(SRC / src).convert("RGB"), box)
    kept = [w for w in widths if w <= im.width * 1.35] or [im.width]
    for w in kept:
        h = round(im.height * w / im.width)
        out = im.resize((w, h), Image.LANCZOS)
        if w > im.width:
            out = out.filter(ImageFilter.UnsharpMask(radius=1.2, percent=60, threshold=2))
        out.save(OUT / f"{name}-{w}.webp", "WEBP", quality=80, method=6)
    print(f"{name}: {im.width}x{im.height} -> {kept}")
