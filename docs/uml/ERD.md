# Entity Relationship Diagram — TemJi

Diagram ini merepresentasikan **persis** struktur tabel di `database/migrations/`.
Dibuat dalam sintaks [Mermaid](https://mermaid.js.org/), otomatis di-render sebagai
diagram visual oleh GitHub saat file ini dibuka di browser.

## 1. Diagram Relasi Entitas

```mermaid
erDiagram
    USERS ||--o{ PICKUP_REQUESTS : "mengajukan (user_id)"
    USERS ||--o{ PICKUP_REQUESTS : "menangani sbg collector (collector_id)"
    USERS ||--o{ POINT_HISTORIES : "memiliki"
    USERS ||--o{ POINT_EXCHANGES : "menukar"
    USERS ||--o{ NEWS : "menulis sbg admin (author_id)"
    USERS ||--o{ APP_NOTIFICATIONS : "menerima"

    PICKUP_REQUESTS ||--o{ POINT_HISTORIES : "menghasilkan"
    PICKUP_REQUESTS }o--o{ WASTE_CATEGORIES : "pickup_request_waste_category"

    REWARDS ||--o{ POINT_EXCHANGES : "ditukar"

    USERS {
        bigint id PK
        string name
        string email UK
        string password
        enum role "resident | admin | collector"
        string phone
        string address
        int points "default 0"
        decimal cash_balance "default 0, akumulasi saldo cash"
    }

    WASTE_CATEGORIES {
        bigint id PK
        string name
        text description
        int points_per_kg
    }

    PICKUP_REQUESTS {
        bigint id PK
        bigint user_id FK
        bigint collector_id FK "nullable"
        text address
        enum status "pending|approved|scheduled|collected|rejected"
        datetime scheduled_at
        decimal total_weight
        int total_points
        text notes
    }

    PICKUP_REQUEST_WASTE_CATEGORY {
        bigint id PK
        bigint pickup_request_id FK
        bigint waste_category_id FK
        decimal estimated_weight
        decimal actual_weight
    }

    POINT_HISTORIES {
        bigint id PK
        bigint user_id FK
        bigint pickup_request_id FK "nullable"
        int points "+ earn / - redeem"
        enum type "earn|redeem|refund"
        string description
    }

    REWARDS {
        bigint id PK
        string name
        enum category "saldo | barang"
        decimal nominal "hanya untuk saldo, nullable"
        text description
        int points_required
        int stock
        string image
    }

    POINT_EXCHANGES {
        bigint id PK
        bigint user_id FK
        bigint reward_id FK
        enum reward_type "saldo | barang"
        string reward_name "snapshot"
        decimal value "nominal saldo, nullable untuk barang"
        int points_used
        enum status "pending|approved|rejected"
    }

    NEWS {
        bigint id PK
        bigint author_id FK
        string title
        text content
        string image
    }

    APP_NOTIFICATIONS {
        bigint id PK
        bigint user_id FK
        string title
        text message
        boolean is_read
    }
```

## 2. Pemetaan Migration ↔ Use Case

| File Migration | Tabel | Use Case Terkait (dari diagram use case TemJi) |
|---|---|---|
| `0001_01_01_000000_create_users_table.php` | `users` | dasar autentikasi semua actor |
| `2024_01_01_000001_add_role_and_profile_to_users_table.php` | `users` (+kolom) | pembeda role: Resident / Admin / Collector |
| `2024_01_01_000002_create_waste_categories_table.php` | `waste_categories` | "Mengelola kategori sampah" (Admin) |
| `2024_01_01_000003_create_pickup_requests_table.php` | `pickup_requests` | "Mengajukan pengambilan sampah" (Resident), "Mengelola data pengajuan sampah" (Admin), "Melihat antrean penjemputan" (Collector) |
| `2024_01_01_000004_create_pickup_request_waste_category_table.php` | `pickup_request_waste_category` (**pivot, Many-to-Many**) | "Memilih kategori sampah" `<<Include>>` |
| `2024_01_01_000005_create_point_histories_table.php` | `point_histories` | "Melihat riwayat poin" (Resident) |
| `2024_01_01_000006_create_rewards_table.php` | `rewards` | "Mengelola penukaran poin" (Admin) — sisi katalog |
| `2024_01_01_000007_create_point_exchanges_table.php` | `point_exchanges` | "Melakukan penukaran poin" (Resident) `<<Include>>` "Mengelola penukaran poin" (Admin) |
| `2024_01_01_000008_create_news_table.php` | `news` | "Melihat info berita terbaru" (Resident) |
| `2024_01_01_000009_create_app_notifications_table.php` | `app_notifications` | "Menerima notifikasi atau berita terbaru" (Resident) |
| `2025_01_01_000010_add_refund_to_point_histories_type.php` | `point_histories` (+enum value) | pengembalian poin saat Admin menolak penukaran |
| `2025_01_02_000011_add_category_and_nominal_to_rewards_table.php` | `rewards` (+category saldo/barang, nominal) | pembeda reward saldo & barang pada katalog |
| `2026_09_07_000012_add_cash_balance_to_users_table.php` | `users` (+cash_balance) | akumulasi saldo cash reward yang disetujui |
| `2026_09_07_000013_add_snapshot_columns_to_point_exchanges_table.php` | `point_exchanges` (+reward_type, reward_name, value) | Redemption History Log mandiri (snapshot katalog) |

## 3. Relasi Wajib (sesuai requirement)

| Requirement | Implementasi |
|---|---|
| **1-to-Many** | `User → PickupRequest` (sbg resident & sbg collector, dua foreign key berbeda), `User → PointHistory`, `User → PointExchange`, `User → News`, `User → AppNotification`, `Reward → PointExchange` |
| **Many-to-Many (wajib pivot)** | `PickupRequest ↔ WasteCategory` lewat tabel `pickup_request_waste_category`, dengan kolom tambahan `estimated_weight` & `actual_weight` di pivot |
