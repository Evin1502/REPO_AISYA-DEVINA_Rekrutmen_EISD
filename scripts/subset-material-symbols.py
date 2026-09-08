#!/usr/bin/env python3
"""
Regenerate resources/fonts/material-symbols-outlined.woff2 (subset).

Kenapa perlu ini:
  Material Symbols Outlined dipakai sebagai *ligature font* -- teks
  seperti "recycling" di-substitusi jadi 1 glyph ikon oleh font itu
  sendiri. Font aslinya (paket npm "material-symbols") beratnya ~3.8 MB
  karena isinya SEMUA ikon yang tersedia. Kita subset supaya cuma
  ikon yang benar-benar dipakai di Blade view yang ikut ke-bundle.

Kapan harus dijalankan ulang:
  Setiap kali ada nama ikon BARU dipakai di resources/views/**/*.blade.php
  (mis. `<span class="material-symbols-outlined">some_new_icon</span>`)
  yang belum ada di subset saat ini. Kalau lupa, ikon itu akan tampil
  sebagai teks mentah ("some_new_icon") bukan gambar ikon.

Cara pakai:
    pip install fonttools brotli --break-system-packages   # kalau belum ada
    npm install material-symbols                            # sumber font asli
    python3 scripts/subset-material-symbols.py

Script ini otomatis men-scan semua Blade view untuk menemukan nama ikon
yang dipakai, jadi tidak perlu diedit manual -- cukup dijalankan ulang.
"""
import glob
import re
import subprocess
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
VIEWS_GLOB = str(ROOT / "resources" / "views" / "**" / "*.blade.php")
SOURCE_FONT = ROOT / "node_modules" / "material-symbols" / "material-symbols-outlined.woff2"
OUTPUT_FONT = ROOT / "resources" / "fonts" / "material-symbols-outlined.woff2"

ICON_PATTERN = re.compile(
    r'material-symbols-outlined[^"]*">\s*([a-z0-9_]+)\s*</span>', re.DOTALL
)


def find_used_icons() -> set[str]:
    icons: set[str] = set()
    for path in glob.glob(VIEWS_GLOB, recursive=True):
        content = Path(path).read_text(encoding="utf-8")
        icons.update(ICON_PATTERN.findall(content))
    return icons


def main() -> int:
    if not SOURCE_FONT.exists():
        print(f"ERROR: {SOURCE_FONT} tidak ditemukan.")
        print('Jalankan dulu: npm install material-symbols')
        return 1

    icons = sorted(find_used_icons())
    if not icons:
        print("ERROR: tidak ada ikon material-symbols-outlined ditemukan di Blade views.")
        return 1

    print(f"Ditemukan {len(icons)} ikon unik yang dipakai:")
    for i in icons:
        print(f"  - {i}")

    text_arg = " ".join(icons)
    OUTPUT_FONT.parent.mkdir(parents=True, exist_ok=True)

    cmd = [
        sys.executable, "-m", "fontTools.subset",
        str(SOURCE_FONT),
        f"--text={text_arg}",
        "--layout-features=liga,rlig,ccmp,calt",
        "--no-hinting",
        "--desubroutinize",
        "--flavor=woff2",
        f"--output-file={OUTPUT_FONT}",
    ]
    print("\nMenjalankan fonttools subset...")
    result = subprocess.run(cmd, capture_output=True, text=True)
    if result.returncode != 0:
        print(result.stdout)
        print(result.stderr)
        return 1

    size_kb = OUTPUT_FONT.stat().st_size / 1024
    print(f"\nOK -> {OUTPUT_FONT} ({size_kb:.0f} KB)")
    print("\nJANGAN LUPA: update juga daftar ikon di komentar")
    print("resources/css/material-symbols-subset.css supaya dokumentasinya sinkron,")
    print("lalu jalankan `npm run build` dan cek tampilan tiap ikon secara visual.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
