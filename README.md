# 🧺 LaundryBiz - Sistem Manajemen Laundry Professional

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Aplikasi manajemen laundry lengkap dengan fitur POS, multi-role, tracking real-time, dan laporan keuangan.

![Dashboard Preview](screenshots/dashboard-admin.png)

---

## ✨ Fitur Utama

### 🎯 Multi-Role System

-   **Admin** - Full akses ke semua fitur
-   **Kasir** - POS, transaksi, pelanggan
-   **Owner** - Laporan & monitoring keuangan
-   **Produksi** - Update status cucian

### 📋 Manajemen Transaksi

-   ✅ Point of Sale (POS) modern
-   ✅ Multi-item per transaksi
-   ✅ Diskon & potongan harga
-   ✅ Estimasi waktu otomatis
-   ✅ QR Code untuk tracking
-   ✅ Cetak struk & label

### 📊 Workflow Status Lengkap

```
pending → proses → cuci → setrika → packing → selesai → diambil
                                                    ↘ batal
```

### 💰 Keuangan & Laporan

-   ✅ Laporan pemasukan harian/bulanan
-   ✅ Tracking pengeluaran
-   ✅ Net profit calculation
-   ✅ Export Excel & Print PDF
-   ✅ Chart & grafik interaktif

### 🔒 Keamanan

-   ✅ Role-based access control
-   ✅ Rate limiting login
-   ✅ Input validation
-   ✅ CSRF protection
-   ✅ SQL injection prevention

---

## 📸 Screenshots

<details>
<summary>Klik untuk melihat screenshots</summary>

| Dashboard Admin                               | POS / Transaksi Baru        |
| --------------------------------------------- | --------------------------- |
| ![Dashboard](screenshots/dashboard-admin.png) | ![POS](screenshots/pos.png) |

| Daftar Transaksi                              | Struk / Receipt                     |
| --------------------------------------------- | ----------------------------------- |
| ![Transactions](screenshots/transactions.png) | ![Receipt](screenshots/receipt.png) |

| Laporan                             | Public Tracking                              |
| ----------------------------------- | -------------------------------------------- |
| ![Reports](screenshots/reports.png) | ![Tracking](screenshots/public-tracking.png) |

</details>

---

## 🚀 Instalasi

### Persyaratan Sistem

-   PHP >= 8.2
-   Composer
-   MySQL >= 5.7 atau MariaDB
-   Node.js >= 18.x (untuk asset build)

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/username/laundry-app.git
cd laundry-app

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
# DB_DATABASE=db_laundry
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Jalankan migrasi & seeder
php artisan migrate --seed

# 6. Build assets
npm run build

# 7. Jalankan server
php artisan serve
```

### Akun Demo

| Role     | Email                | Password |
| -------- | -------------------- | -------- |
| Admin    | admin@laundry.com    | password |
| Kasir    | kasir@laundry.com    | password |
| Owner    | owner@laundry.com    | password |
| Produksi | produksi@laundry.com | password |

---

## 📁 Struktur Folder

```
laundry-app/
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Models/               # Eloquent Models
│   ├── Services/             # Business Logic
│   └── Http/Requests/        # Form Validation
├── database/
│   ├── migrations/           # Database Schema
│   └── seeders/              # Demo Data
├── resources/views/          # Blade Templates
│   ├── admin/                # Admin Views
│   ├── kasir/                # Kasir Views
│   ├── dashboards/           # Dashboard Views
│   └── layouts/              # Layout Templates
└── routes/web.php            # Route Definitions
```

---

## 🛠️ Tech Stack

| Technology | Purpose              |
| ---------- | -------------------- |
| Laravel 12 | Backend Framework    |
| MySQL      | Database             |
| Blade      | Template Engine      |
| Vite       | Asset Bundling       |
| Chart.js   | Charts & Graphs      |
| QR Code    | Transaction Tracking |

---

## 📝 Changelog

### v1.0.0 (2026-01-16)

-   Initial release
-   Multi-role system (Admin, Kasir, Owner, Produksi)
-   Complete transaction workflow
-   Financial reports
-   QR code tracking
-   Receipt & label printing

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 💬 Support

Untuk pertanyaan atau support, silakan hubungi:

-   Email: support@laundry.com
-   WhatsApp: +62xxx

---

Made with ❤️ in Indonesia
