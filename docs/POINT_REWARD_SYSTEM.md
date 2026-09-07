# Modul Point Reward — Spesifikasi & Panduan

Dokumen ini menjelaskan desain modul Point Reward untuk **TemJi** setelah dibangun ulang
mengikuti aturan bisnis terbaru.

---

## 1. Konsep Utama

- **Reward utama: Saldo / Cash Balance** (bukan pulsa/e-wallet otomatis).
- **3 pilihan barang fisik** sebagai opsi reward tambahan.
- Penukaran **saldo otomatis langsung disetujui** dan nilai nominal langsung **dikredit ke
  `users.cash_balance`** tanpa menunggu Admin. Penukaran **barang** tetap perlu persetujuan
  Admin (butuh pengadaan fisik).
- Seluruh penukaran bersifat **simulasi / pencatatan database saja** — belum terintegrasi
  ke payment gateway / e-wallet secara otomatis.

## 2. Tabel Katalog Reward

### 2.1. Jenjang Penukaran Saldo (Saldo / Cash Balance)

Rasio dasar: **20 Poin = Rp5.000** dan **30 Poin = Rp10.000**.

Karena dua titik acuan berbeda, jenjang dibuat **bertingkat progresif** (nilai per poin
semakin baik di nominal besar). Rumus:

```
poin_butuh = (nominal / 5.000) × 10 + 10
```

| Tier | Nominal Saldo | Poin Dibutuhkan | Estimasi Rp/poin |
|---:|---:|---:|---:|
| 1 | Rp5.000 | 20 | Rp250 |
| 2 | Rp10.000 | 30 | Rp333 |
| 3 | Rp15.000 | 40 | Rp375 |
| 4 | Rp20.000 | 50 | Rp400 |
| 5 | Rp25.000 | 60 | Rp417 |
| 6 | Rp30.000 | 70 | Rp429 |
| 7 | Rp35.000 | 80 | Rp438 |
| 8 | Rp40.000 | 90 | Rp444 |
| 9 | Rp45.000 | 100 | Rp450 |
| 10 | Rp50.000 | 110 | Rp455 |
| 11 | Rp55.000 | 120 | Rp458 |
| 12 | Rp60.000 | 130 | Rp462 |
| 13 | Rp65.000 | 140 | Rp464 |
| 14 | Rp70.000 | 150 | Rp467 |
| 15 | Rp75.000 | 160 | Rp469 |
| 16 | Rp80.000 | 170 | Rp471 |
| 17 | Rp85.000 | 180 | Rp472 |
| 18 | Rp90.000 | 190 | Rp474 |
| 19 | Rp95.000 | 200 | Rp475 |
| 20 | Rp100.000 | 210 | Rp476 |

> Bukan pulsa — nilai yang direkam adalah **saldo cash** pada kolom `nominal` reward.

### 2.2. Pilihan Barang Fisik (3 opsi)

Estimasi poin dihitung dengan kurs ±Rp300/poin (memperhitungkan biaya pengadaan & logistik),
sehingga proporsional dengan skala saldo di atas.

| No | Barang | Estimasi Nilai | Poin Dibutuhkan |
|---:|---|---|---:|
| 1 | Tumbler Custom Branded | ±Rp36.000 | 120 |
| 2 | Kaos Eksklusif (Official Merchandise) | ±Rp51.000 | 170 |
| 3 | Power Bank 10.000 mAh | ±Rp150.000 | 500 |

## 3. Logika Pemrosesan & Potong Poin (Business Logic)

Semua alur dipusatkan di `app/Services/PointRewardService.php`:

### 3.1. Alur Penukaran (Resident) — `redeem(User $user, Reward $reward)`

```
1. VALIDASI
   ├─ poin user >= points_required  …. jika kurang → tolak ("Poin kamu tidak cukup…")
   └─ stock reward > 0              …. jika habis  → tolak ("Stok reward ini sudah habis.")

2. TRANSAKSI DB (atomic, DB::transaction)
   ├─ potong poin user            → users.points -= points_required
   ├─ potong stok reward          → rewards.stock -= 1
   ├─ buat PointExchange (Redemption History Log):
   │    user_id, reward_id, reward_type (saldo|barang), reward_name (snapshot),
   │    value (nominal saldo / null untuk barang), points_used,
   │    status = pending UNTUK BARANG, atau approved UNTUK SALDO (otomatis)
   ├─ jika saldo → users.cash_balance += nominal   (kredit langsung, tanpa Admin)
   └─ buat PointHistory(type = redeem, points negatif)
```

### 3.2. Alur Persetujuan Admin — `approve(PointExchange $exchange)` (khusus barang)

Penukaran **saldo tidak pernah masuk antrean Admin** (sudah `approved` otomatis saat redeem).
Persetujuan Admin hanya relevan untuk penukaran **barang**:

```
1. status harus 'pending' → else tolak (422)
2. TRANSAKSI DB
   ├─ status -> approved
   └─ jika reward_type = saldo → users.cash_balance += value  (jalur defensif)
```

Tidak ada perubahan poin/stok saat approve — poin & stok sudah dipotong saat pengajuan dibuat.

### 3.3. Alur Penolakan Admin — `reject(PointExchange $exchange)` (khusus barang)

```
1. status harus 'pending' → else tolak (422)
2. TRANSAKSI DB (refund)
   ├─ status -> rejected
   ├─ kembalikan poin user            → users.points += points_used
   ├─ pulihkan stok reward            → rewards.stock += 1
   └─ buat PointHistory(type = refund, points positif)
```

Saldo cash **tidak pernah** dikredit pada penolakan — hanya dikredit saat status `approved`
(untuk saldo otomatis pada saat redeem, untuk barang saat Admin menyetujui secara defensif).

### 3.4. Status Transaksi

| Status | Label | Keterangan |
|---|---|---|
| `pending` | Menunggu | Penukaran barang diajukan, menunggu persetujuan Admin |
| `approved` | Disetujui (Success) | Saldo: otomatis seketika saat redeem. Barang: setelah Admin menyetujui |
| `rejected` | Ditolak | Barang ditolak Admin; poin di-refund & stok dipulihkan |

> **Catatan:** penukaran **saldo langsung berstatus `approved` saat diklik**, dan
> `cash_balance` langsung bertambah — tidak ada tombol setujui/tolak untuk saldo di Admin.

## 4. Rancangan Struktur Database / Skema Data

Tabel transaksi utama **`point_exchanges`** (Redemption History Log):

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint PK | |
| `user_id` | bigint FK → users | User yang menukar (User ID) |
| `reward_id` | bigint FK → rewards | Referensi katalog (snapshot tetap tersimpan) |
| `reward_type` | enum `saldo`,`barang` | Reward Type |
| `reward_name` | string | Snapshot nama reward (riwayat tetap utuh walau reward diubah/dihapus) |
| `value` | decimal(12,2) nullable | Nominal saldo (Rp); null untuk barang |
| `points_used` | unsigned int | Points Deducted |
| `status` | enum `pending`,`approved`,`rejected` | Status transaksi |
| `created_at` | timestamp | Timestamp pengajuan |
| `updated_at` | timestamp | Timestamp proses approve/reject |

Kolom pendukung lain yang ditambahkan/diubah:

- `users.cash_balance` decimal(12,2) default 0 — akumulasi saldo cash hasil penukaran yang disetujui.
- `rewards.category` enum `saldo`,`barang` — pembeda jenis reward (menggantikan `saldo_pulsa`).
- `rewards.nominal` decimal(12,2) nullable — nominal saldo (hanya untuk kategori `saldo`).

Riwayat mutasi poin tetap di `point_histories` (`type`: `earn` / `redeem` / `refund`).

## 5. File Terkait

| Area | Lokasi |
|---|---|
| Business logic | `app/Services/PointRewardService.php` |
| Exception | `app/Exceptions/PointExchangeException.php` |
| Controller (Resident) | `app/Http/Controllers/Resident/PointExchangeController.php` |
| Controller (Admin) | `app/Http/Controllers/Admin/PointExchangeController.php` |
| Katalog seeder | `database/seeders/RewardSeeder.php` |
| Migration reward | `database/migrations/2025_01_02_000011_...` |
| Migration cash balance | `database/migrations/2026_09_07_000012_add_cash_balance_to_users_table.php` |
| Migration snapshot log | `database/migrations/2026_09_07_000013_add_snapshot_columns_to_point_exchanges_table.php` |
| Migration konversi kategori | `database/migrations/2026_09_07_000014_change_rewards_category_to_saldo.php` |
| Test | `tests/Feature/Resident/PointExchangeTest.php`, `tests/Feature/Admin/PointExchangeTest.php`, `tests/Feature/RewardCatalogTest.php` |