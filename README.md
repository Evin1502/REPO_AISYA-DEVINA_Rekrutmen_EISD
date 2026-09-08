# TemJi — Platform Bank Sampah Digital

TemJi membantu warga mengubah sampah rumah tangga menjadi poin yang bisa ditukar reward,
sekaligus membantu pengelola kota memantau dan mengelola alur pengumpulan sampah secara
terstruktur — dari pengajuan penjemputan, penimbangan di lapangan, hingga distribusi poin.

## Keselarasan dengan Sustainable Development Goals (SDG)

Proyek ini secara eksplisit dirancang untuk mendukung:

- **SDG 11 — Sustainable Cities and Communities**, poin 11.6: mengurangi dampak
  lingkungan perkotaan lewat pengelolaan sampah kota yang lebih baik dan terlacak.


Implementasi konkretnya:

| Fitur di TemJi | Kontribusi terhadap SDG |
|---|---|
| Kategori sampah dengan poin berbeda (semakin sulit didaur ulang, semakin tinggi poinnya) | Mendorong warga memilah sampah sejak dari rumah (SDG 12.5) |
| Sistem poin & reward | Insentif perilaku berkelanjutan jangka panjang, bukan sekali jalan (SDG 12.5) |
| Field `area` (wilayah) terstruktur pada tiap pengajuan + breakdown volume sampah per wilayah di dashboard admin | Data pengelolaan sampah **lintas wilayah kota** yang terlacak & bisa diagregasi (SDG 11.6) |
| Metrik ketepatan waktu penjemputan (% selesai ≤ 60 menit dari jadwal) di dashboard admin | Mengukur keandalan operasional pengelolaan sampah kota, bukan cuma volumenya (SDG 11.6) |
| Dashboard statistik (total kg terkumpul, jumlah warga & kolektor aktif) | Transparansi dampak kolektif ke komunitas |

> **Catatan jujur soal skala:** cakupan wilayah saat ini masih berupa daftar tetap
> (lihat `config/temji.php`) yang merepresentasikan level kelurahan/kecamatan dalam
> satu kota, bukan multi-kota. Untuk skala yang lebih besar, `service_areas` di
> config tinggal ditambah — arsitektur agregasinya (`Admin\DashboardController`)
> sudah generik dan tidak perlu diubah.

## Tumpukan Teknologi

- **Laravel 12** (pure Laravel MVC — tanpa package admin generator seperti Filament/Nova)
- **Blade** templating, auth (login/register) ditulis manual
- **Tailwind CSS v4** untuk styling
- **MySQL** sebagai database utama

## Role & Hak Akses

| Role | Fitur Utama |
|---|---|
| **Resident** (warga) | Ajukan pengambilan sampah + pilih kategori, lihat riwayat & status, tukar poin ke reward, baca berita, notifikasi |
| **Admin** | Kelola kategori sampah, approve/reject pengajuan, kelola reward & penukaran poin, kelola berita, lihat data pengguna |
| **Collector** (kolektor) | Lihat antrean penjemputan, jadwalkan penjemputan, input berat riil sampah → poin otomatis masuk ke resident |

Registrasi publik (`/register`) hanya membuat akun **Resident**. Akun Admin & Collector
dibuat lewat seeder (`database/seeders/AdminSeeder.php` & `CollectorSeeder.php`), tidak
bisa didaftarkan lewat form publik — sesuai batasan akses pada studi kasus.

## Instalasi

```bash
composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate

# sesuaikan DB_* di .env, lalu:
php artisan migrate --seed
php artisan serve
```

Akun demo setelah `--seed`:

| Role | Email | Password |
|---|---|---|
| Admin | admin@temji.test | admin12345 |
| Collector | collector1@temji.test | collector12345 |

## Struktur Database & Dokumentasi

Lihat [`docs/uml/ERD.md`](docs/uml/ERD.md) untuk diagram relasi entitas (ERD) lengkap
beserta pemetaan tiap tabel migration ke use case pada studi kasus, dan
[`docs/PROJECT_STRUCTURE.md`](docs/PROJECT_STRUCTURE.md) untuk penjelasan struktur folder
dan alur MVC. Spesifikasi lengkap modul **Point Reward** (katalog jenjang saldo, logika
penukaran/refund, dan skema transaksi) ada di [`docs/POINT_REWARD_SYSTEM.md`](docs/POINT_REWARD_SYSTEM.md).

## Menjalankan Test

```bash
php artisan test
```
