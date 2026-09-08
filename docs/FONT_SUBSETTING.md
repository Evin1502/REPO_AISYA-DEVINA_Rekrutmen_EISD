# Font Self-Hosting & Icon Subsetting

## Latar belakang masalah

Sebelumnya, semua font (Inter, Plus Jakarta Sans, Material Symbols Outlined)
di-load lewat `<link>` ke `fonts.googleapis.com` / `fonts.gstatic.com` di
`resources/views/layouts/partials/head-assets.blade.php`.

Material Symbols pakai teknik **ligature font**: teks seperti `recycling`
disubstitusi jadi 1 glyph ikon oleh font itu sendiri (bukan SVG atau
`<img>`). Kalau font gagal dimuat -- jaringan lambat, CDN diblokir
firewall/browser privasi/korporat, dll -- yang tampil ke user adalah teks
mentah (`recycling`) alih-alih ikon.

## Solusi: self-host via Vite

Font sekarang di-bundle langsung ke `public/build/assets/` lewat build
Vite, jadi tidak butuh jaringan eksternal sama sekali saat runtime.

- **Inter** & **Plus Jakarta Sans**: dari paket npm `@fontsource/inter` dan
  `@fontsource/plus-jakarta-sans`, di-import di `resources/css/app.css`.
- **Material Symbols Outlined**: di-*subset* (dipangkas) dari paket npm
  `material-symbols`, lihat bagian di bawah.

Konfigurasi ada di:
- `resources/css/app.css` -- daftar `@import`
- `resources/css/material-symbols-subset.css` -- `@font-face` untuk font
  ikon hasil subset
- `resources/fonts/material-symbols-outlined.woff2` -- file font hasil subset

## Kenapa di-subset?

Font asli Material Symbols Outlined berisi **semua** ikon yang tersedia
(~6.600 glyph, ~3.8 MB). Project ini cuma pakai 36 nama ikon, jadi kita
pangkas pakai `fonttools` (`pyftsubset`) supaya cuma glyph & aturan
ligature yang relevan yang ikut ke-bundle (~2.75 MB, turun ~27%).

Reduksinya tidak lebih besar karena struktur GSUB (aturan ligature/
contextual substitution) di font ini saling berkaitan -- `fonttools` harus
tetap mempertahankan cukup banyak glyph pendukung supaya substitusi
ligature tetap valid.

## Cara regenerate subset (kalau ada ikon baru)

Kalau menambah ikon baru di Blade view (`<span class="material-symbols-outlined">nama_ikon</span>`)
yang **belum ada** di subset saat ini, ikon itu akan tampil sebagai teks
mentah karena glyph-nya tidak ada di file subset. Jalankan ulang:

```bash
npm install material-symbols   # kalau belum ada di node_modules
pip install fonttools brotli --break-system-packages
python3 scripts/subset-material-symbols.py
npm run build
```

Script `scripts/subset-material-symbols.py` otomatis men-scan semua
`resources/views/**/*.blade.php`, mendeteksi semua nama ikon yang
dipakai, lalu generate ulang `resources/fonts/material-symbols-outlined.woff2`.

Setelah regenerate, **cek visual** tiap ikon (terutama yang baru) untuk
pastikan tidak ada yang tampil sebagai teks mentah -- subsetting ligature
font itu halus dan bisa gagal diam-diam kalau ada perubahan versi paket
`material-symbols`.

## Daftar ikon saat ini (36)

`account_balance_wallet`, `add_circle`, `arrow_forward`, `calendar_today`,
`campaign`, `check_circle`, `checklist`, `chevron_right`,
`currency_exchange`, `dashboard`, `electric_rickshaw`, `flash_on`, `group`,
`groups`, `history`, `inventory_2`, `local_shipping`, `location_on`,
`logout`, `loyalty`, `map`, `menu`, `notifications`, `payments`, `people`,
`person_add`, `play_arrow`, `print`, `receipt_long`, `recycling`,
`redeem`, `scale`, `schedule`, `trending_up`, `verified`, `verified_user`
