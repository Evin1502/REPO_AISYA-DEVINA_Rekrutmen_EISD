# Struktur Project TemJi

Dokumentasi struktur folder & alur kerja aplikasi **TemJi** — platform bank sampah digital
berbasis Laravel dengan tiga role: **Resident**, **Admin**, dan **Collector**.

---

## 1. Struktur Folder

```
temji/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── NewsController.php
│   │   │   │   ├── PickupRequestController.php
│   │   │   │   ├── PointExchangeController.php
│   │   │   │   ├── RewardController.php
│   │   │   │   ├── UserController.php
│   │   │   │   └── WasteCategoryController.php
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── Collector/
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── PickupRequestController.php
│   │   │   ├── Resident/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── NewsController.php
│   │   │   │   ├── NotificationController.php
│   │   │   │   ├── PickupRequestController.php
│   │   │   │   ├── PointExchangeController.php
│   │   │   │   ├── PointHistoryController.php
│   │   │   │   └── RewardController.php
│   │   │   ├── Controller.php          # base controller
│   │   │   └── HomeController.php      # landing page (/)
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php      # cek role sebelum akses route
│   │   └── Requests/                   # FormRequest = validasi server-side
│   │       ├── Admin/
│   │       │   ├── StoreNewsRequest.php
│   │       │   ├── StoreRewardRequest.php
│   │       │   ├── StoreWasteCategoryRequest.php
│   │       │   ├── UpdateNewsRequest.php
│   │       │   ├── UpdateRewardRequest.php
│   │       │   └── UpdateWasteCategoryRequest.php
│   │       ├── Collector/
│   │       │   └── UpdatePickupRequest.php
│   │       └── Resident/
│   │           └── StorePickupRequestRequest.php
│   ├── Models/
│   │   ├── User.php                    # +role, +points, relasi ke semua entitas
│   │   ├── PickupRequest.php           # inti sistem: pengajuan penjemputan
│   │   ├── WasteCategory.php           # kategori sampah (many-to-many ke PickupRequest)
│   │   ├── PointHistory.php            # riwayat mutasi poin (earn/redeem/refund)
│   │   ├── Reward.php                  # katalog penukaran poin
│   │   ├── PointExchange.php           # transaksi penukaran poin
│   │   ├── News.php                    # berita/info terbaru
│   │   └── AppNotification.php         # notifikasi ke resident
│   ├── Policies/
│   │   ├── AppNotificationPolicy.php    # notifikasi hanya pemiliknya boleh tandai dibaca
│   │   ├── PickupRequestPolicy.php
│   │   └── PointExchangePolicy.php
│   └── Providers/
│       └── AppServiceProvider.php
│
├── database/
│   ├── factories/                      # data dummy untuk testing
│   │   ├── PickupRequestFactory.php
│   │   ├── PointExchangeFactory.php
│   │   ├── RewardFactory.php
│   │   ├── UserFactory.php
│   │   └── WasteCategoryFactory.php
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2024_01_01_000001_add_role_and_profile_to_users_table.php
│   │   ├── 2024_01_01_000002_create_waste_categories_table.php
│   │   ├── 2024_01_01_000003_create_pickup_requests_table.php
│   │   ├── 2024_01_01_000004_create_pickup_request_waste_category_table.php  # PIVOT (many-to-many)
│   │   ├── 2024_01_01_000005_create_point_histories_table.php
│   │   ├── 2024_01_01_000006_create_rewards_table.php
│   │   ├── 2024_01_01_000007_create_point_exchanges_table.php
│   │   ├── 2024_01_01_000008_create_news_table.php
│   │   ├── 2024_01_01_000009_create_app_notifications_table.php
│   │   └── 2025_01_01_000010_add_refund_to_point_histories_type.php
│   └── seeders/
│       ├── AdminSeeder.php             # akun admin default (gak lewat register)
│       ├── CollectorSeeder.php         # akun collector default
│       ├── RewardSeeder.php
│       ├── WasteCategorySeeder.php
│       └── DatabaseSeeder.php          # panggil semua seeder di atas
│
├── resources/
│   ├── css/app.css                     # Tailwind v4 + custom theme (brand/admin/collector color)
│   ├── js/
│   └── views/
│       ├── admin/                      # dashboard + CRUD (news, pickup-requests, point-exchanges, rewards, users, waste-categories)
│       ├── auth/                       # login.blade.php, register.blade.php
│       ├── collector/                  # dashboard, history, pickup-requests
│       ├── components/
│       │   ├── alert.blade.php
│       │   ├── empty-state.blade.php
│       │   ├── status-badge.blade.php          # badge status + dot indikator
│       │   └── pickup-status-stepper.blade.php # progress stepper (baru)
│       ├── layouts/                    # app, admin, auth, collector, landing + partials
│       ├── resident/                   # dashboard, news, notifications, pickup-requests, point-exchanges, point-histories, rewards
│       ├── vendor/pagination/          # override tampilan pagination
│       ├── home.blade.php
│
├── routes/
│   ├── console.php
│   └── web.php                         # semua route: public, auth, resident.*, admin.*, collector.*
│
├── tests/
│   ├── Feature/
│   │   ├── Admin/       (PickupRequestManagementTest, PointExchangeTest)
│   │   ├── Auth/        (RoleAccessTest)
│   │   ├── Collector/   (PickupCollectionTest)
│   │   └── Resident/    (PickupRequestLifecycleTest)
│   └── Unit/
│
├── public/                             # entry point (index.php), asset build
├── config/                             # konfigurasi bawaan Laravel
├── .env / .env.example
├── composer.json / composer.lock
├── package.json / package-lock.json
├── vite.config.js
└── phpunit.xml
```

---

## 2. Alur MVC

```
Request masuk (browser)
        │
        ▼
routes/web.php  ──────────► tentukan URL cocok ke controller yang mana
        │
        ▼
Middleware  ───────────────► auth (harus login?) + role:admin/resident/collector
        │
        ▼
FormRequest (kalau ada) ───► validasi input server-side, gagal = balik + flash error
        │
        ▼
Controller  ────────────────► app/Http/Controllers/{Role}/...
        │
        ▼
Model (Eloquent)  ──────────► query/insert/update ke database
        │
        ▼
View (Blade)  ───────────────► resources/views/{role}/...
        │
        ▼
Response HTML ke browser
```

### Contoh konkret: alur "Ajukan Pengambilan Sampah"

```
POST /resident/pickup-requests
    │
    ▼
StorePickupRequestRequest          → validasi address, categories[], estimated_weight[]
    │
    ▼
Resident\PickupRequestController@store
    │
    ├─► PickupRequest::create([...])                       (1-to-Many: User → PickupRequest)
    └─► $pickupRequest->wasteCategories()->attach([...])    (Many-to-Many: PickupRequest ↔ WasteCategory)
    │
    ▼
redirect()->route('resident.pickup-requests.show', $pickupRequest)
    │
    ▼
resources/views/resident/pickup-requests/show.blade.php
    → menampilkan <x-pickup-status-stepper> + <x-status-badge>
```

---

## 3. Relasi Database (ringkas)

| Relasi | Tipe | Keterangan |
|---|---|---|
| `User` → `PickupRequest` (sebagai resident) | 1–N | `hasMany(PickupRequest::class, 'user_id')` |
| `User` → `PickupRequest` (sebagai collector) | 1–N | `hasMany(PickupRequest::class, 'collector_id')` |
| `PickupRequest` ↔ `WasteCategory` | **N–N (pivot)** | tabel `pickup_request_waste_category`, simpan `estimated_weight` & `actual_weight` |
| `User` → `PointHistory` | 1–N | riwayat mutasi poin (earn/redeem/refund) |
| `User` → `PointExchange` | 1–N | transaksi tukar poin ke reward |
| `Reward` → `PointExchange` | 1–N | satu reward bisa ditukar banyak kali |
| `User` (admin) → `News` | 1–N | berita ditulis admin |
| `User` → `AppNotification` | 1–N | notifikasi personal |

---

## 4. Role & Hak Akses

| Role | Prefix Route | Middleware | Fitur Utama |
|---|---|---|---|
| **Resident** | `/resident/*` | `auth`, `role:resident` | Ajukan pickup, pilih kategori sampah, lihat riwayat, tukar poin, baca berita, notifikasi |
| **Admin** | `/admin/*` | `auth`, `role:admin` | CRUD kategori sampah, approve/reject pickup, kelola reward & penukaran poin, kelola berita, lihat user |
| **Collector** | `/collector/*` | `auth`, `role:collector` | Lihat antrean, jadwalkan (`approved→scheduled`), input berat riil & selesaikan (`scheduled→collected`), poin otomatis masuk ke resident |

Registrasi publik (`/register`) **hanya** membuat akun Resident. Admin & Collector dibuat lewat `AdminSeeder` / `CollectorSeeder`.

---

## 5. Catatan / Hal yang Perlu Diperhatikan

- Otorisasi sekarang konsisten pakai **Laravel Authorization** lewat `$this->authorize()` + Policy, menggantikan `abort_unless()` manual untuk cek identitas (pemilik/collector/role). Guard status yang perlu respons spesifik (422 saat status bukan `pending`, flash error saat membatalkan pengajuan non-pending) tetap di controller. Policy di-auto-discovery (`App\Policies\{Model}Policy`), jadi tidak perlu didaftarkan manual di provider.
- Migration **wajib dijalankan urut** — `0001_01_01_000000_create_users_table.php` harus jalan duluan sebelum migration yang meng-`alter` tabel `users`.

---

## 6. Riwayat Perubahan (dari sesi pengembangan ini)

| Tahap | Isi |
|---|---|
| 1 | Migration + Model dasar (users+role, waste_categories, pickup_requests, pivot, point_histories, rewards, point_exchanges, news, app_notifications) |
| 2 | Middleware Role, Auth manual (Login/Register), Seeder Admin & Collector, layout + view dasar |
| 3 | Polish UI (progress stepper, status badge + dot, kartu kategori full-clickable, feedback submit) menggunakan skill `ui-ux-pro-max` |