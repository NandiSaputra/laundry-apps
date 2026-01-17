# 📖 Panduan Instalasi LaundryBiz

Dokumentasi lengkap untuk instalasi aplikasi LaundryBiz.

---

## 📋 Persyaratan Sistem

### Server Requirements

| Software | Versi Minimum | Rekomendasi |
| -------- | ------------- | ----------- |
| PHP      | 8.2+          | 8.3         |
| MySQL    | 5.7+          | 8.0         |
| Node.js  | 18.x+         | 20.x        |
| Composer | 2.x           | Latest      |

### PHP Extensions

-   BCMath
-   Ctype
-   cURL
-   DOM
-   Fileinfo
-   JSON
-   Mbstring
-   OpenSSL
-   PDO
-   PDO_MySQL
-   Tokenizer
-   XML

---

## 🚀 Instalasi Step-by-Step

### Step 1: Install PHP Dependencies

```bash
cd laundry-app
composer install
```

### Step 2: Install Node Dependencies

```bash
npm install
```

### Step 3: Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 5: Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_laundry
DB_USERNAME=root
DB_PASSWORD=
```

> **Note:** Buat database `db_laundry` terlebih dahulu di MySQL/phpMyAdmin

### Step 6: Jalankan Migrasi & Seeder

```bash
# Migrasi database
php artisan migrate

# Isi data demo (opsional)
php artisan db:seed

# Atau jalankan keduanya sekaligus
php artisan migrate --seed
```

### Step 7: Build Assets

```bash

# Atau development mode
npm run dev
```

### Step 8: Jalankan Aplikasi

setelah npm run dev buka terminal baru

```bash
php artisan serve
```

Buka browser dan akses: `http://127.0.0.1:8000`

---

## 👤 Akun Demo

| Role     | Email                | Password |
| -------- | -------------------- | -------- |
| Admin    | admin@laundry.com    | password |
| Kasir    | kasir@laundry.com    | password |
| Owner    | owner@laundry.com    | password |
| Produksi | produksi@laundry.com | password |

---

## 🔧 Konfigurasi Tambahan

### Storage Link

```bash
php artisan storage:link
```

### Cache Optimization (Production)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Clear Cache

```bash
php artisan optimize:clear
```

---

## 🌐 Instalasi di cPanel/Shared Hosting

### 1. Upload Files

Upload semua file ke folder `public_html` atau subdomain

### 2. Setup .htaccess

Pastikan file `.htaccess` di folder `public/` sudah benar

### 3. Remote Database

Import file `database.sql` (jika tersedia) atau jalankan migrasi via SSH:

```bash
php artisan migrate --seed
```

### 4. File Permissions

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## ❓ Troubleshooting

### Error: SQLSTATE[HY000] [2002]

Database tidak terkoneksi. Pastikan:

-   MySQL server berjalan
-   Konfigurasi `.env` sudah benar
-   Database sudah dibuat

### Error: 500 Internal Server Error

```bash
php artisan config:clear
php artisan cache:clear
```

### Error: Vite manifest not found

```bash
npm run build
```

---

## 📞 Support

Jika mengalami kesulitan, hubungi:

-   Email: nandysyahputra@gmail.com
-   WhatsApp: 081993520678

---

© 2026 LaundryBiz. All Rights Reserved.
