# 🛒 POS System

A complete **Point of Sale (POS)** web application built with **Laravel**, designed for small to medium-sized retail businesses. Features a guided installer, cashier interface, inventory management, promo/discount engine, purchase orders, and financial reporting.

---

## ✨ Features

| Module                         | Description                                                                                                      |
| ------------------------------ | ---------------------------------------------------------------------------------------------------------------- |
| 🖥️ **Cashier (POS)**           | Fast transaction UI with real-time stock checks, promo codes, tax calculation, and change display                |
| 📦 **Product Management**      | Add/edit/delete products with images, categories, and supplier links                                             |
| 🏷️ **Category Management**     | Organize products; protected deletion if products are still assigned                                             |
| 🤝 **Supplier Management**     | Track suppliers linked to products and purchase orders                                                           |
| 🛒 **Purchase Orders**         | Create purchase orders, receive stock, and auto-update inventory                                                 |
| 🎟️ **Promo / Discount Engine** | Create percentage or fixed-amount promo codes with expiry dates, usage limits, and minimum purchase requirements |
| 👤 **User Management**         | Manage admin, cashier, and owner accounts with profile photos and active/inactive status                         |
| 💸 **Expense Tracking**        | Log business expenses with categories and auto-generated reference numbers                                       |
| 📊 **Dashboard**               | Real-time revenue, expense, and net profit summary with 7-day sales chart and low-stock alerts                   |
| 🔧 **Web Installer**           | Multi-step GUI installer — no terminal needed for initial setup                                                  |

---

## 🛠️ Tech Stack

- **Framework:** Laravel 12
- **Language:** PHP 8.1+
- **Database:** MySQL / PostgreSQL / SQLite / SQL Server
- **Frontend:** Blade templating, Tailwind CSS v4, JavaScript (Fetch API for POS)
- **Build Tool:** Vite
- **Storage:** Laravel Filesystem (local/public disk for product & profile images)

---

## ⚙️ Requirements

- PHP >= 8.1
- Composer
- Node.js >= 16 & NPM >= 8 (untuk Vite + Tailwind CSS)
- A supported database (MySQL recommended)
- Web server: Apache / Nginx (atau `php artisan serve` untuk lokal)

---

## 🚀 Setup dari Awal (Full Guide)

### Prasyarat

Pastikan semua tools berikut sudah terinstall di komputer kamu sebelum mulai:

| Tool     | Versi Minimum | Cek Versi         |
| -------- | ------------- | ----------------- |
| PHP      | 8.1           | `php -v`          |
| Composer | 2.x           | `composer -V`     |
| Node.js  | 16.x          | `node -v`         |
| NPM      | 8.x           | `npm -v`          |
| MySQL    | 5.7 / 8.x     | `mysql --version` |
| Git      | any           | `git --version`   |

> **Rekomendasi lokal**: Gunakan [Laragon](https://laragon.org/) (Windows) atau [Herd](https://herd.laravel.com/) (Mac) agar PHP, MySQL, dan virtual host sudah tersedia otomatis.

---

### Langkah 1 — Clone & Masuk ke Folder Project

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo
```

---

### Langkah 2 — Install Dependensi PHP

```bash
composer install
```

> Jika muncul error `PHP extension not found`, pastikan ekstensi berikut aktif di `php.ini`:
> `ext-pdo`, `ext-mbstring`, `ext-openssl`, `ext-tokenizer`, `ext-xml`, `ext-fileinfo`, `ext-gd`

---

### Langkah 3 — Install Dependensi Frontend & Setup Tailwind CSS

**Install semua package NPM:**

```bash
npm install
```

**Install Tailwind CSS v4:**

```bash
npm install -D tailwindcss @tailwindcss/vite
```

**Tambahkan plugin Tailwind ke `vite.config.js`:**

```js
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

**Ganti isi `resources/css/app.css` dengan:**

```css
@import "tailwindcss";
```

> Di Tailwind v4, tidak perlu file `tailwind.config.js` maupun directive `@tailwind base/components/utilities`. Satu baris import ini sudah cukup — content scanning dilakukan otomatis oleh plugin Vite.

**Pastikan layout utama kamu memuat asset via `@vite` di `<head>`:**

```html
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```

> Jika ada beberapa file layout (misal layout terpisah untuk installer), tambahkan `@vite` ke masing-masing file tersebut.

**Build assets:**

```bash
# Development (dengan hot-reload)
npm run dev

# Production
npm run build
```

> Untuk mode development, jalankan `npm run dev` di terminal terpisah sambil `php artisan serve` berjalan.

---

### Langkah 4 — Buat File Environment

```bash
cp .env.example .env
php artisan key:generate
```

File `.env` yang baru dibuat akan berisi `APP_KEY` yang sudah terisi otomatis. Kamu **tidak perlu** mengisi `DB_*` secara manual jika menggunakan Web Installer (Langkah 6).

---

### Langkah 5 — Atur Permission & Storage Link

```bash
# Linux / Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # sesuaikan user web server kamu

# Buat symlink storage agar file upload bisa diakses publik
php artisan storage:link
```

> **Windows (Laragon/XAMPP):** Permission tidak perlu diubah. Cukup jalankan `php artisan storage:link` saja.

---

### Langkah 6 — Setup Database

#### Opsi A — Web Installer ✅ (Direkomendasikan)

Tidak perlu sentuh database manual. Jalankan server dulu:

```bash
php artisan serve
```

Lalu buka browser dan akses:

```text
http://localhost:8000/install
```

Installer akan memandu kamu melalui 4 langkah:

```text
[Step 1] Koneksi Database
         → Masukkan host, port, nama DB, username, password
         → Database akan dibuat otomatis jika belum ada (MySQL)
         ↓
[Step 2] Informasi Toko
         → Nama toko, alamat, nomor telepon
         ↓
[Step 3] Akun Admin
         → Nama, email, dan password untuk login pertama kali
         ↓
[Step 4] Proses Instalasi
         → Migrasi database berjalan otomatis
         → Data toko & akun admin tersimpan
         → File .env diperbarui permanen
         → Installer dikunci (tidak bisa diakses lagi)
```

Setelah selesai, kamu akan diarahkan ke halaman login.

#### Opsi B — Manual via Terminal

1. Buat database kosong di MySQL:

    ```sql
    CREATE DATABASE nama_database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    ```

2. Isi konfigurasi database di file `.env`:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database
    DB_USERNAME=root
    DB_PASSWORD=your_password

    APP_NAME="Laravel POS"
    APP_URL=http://localhost:8000
    ```

3. Jalankan migrasi:

    ```bash
    php artisan migrate
    ```

4. Buat akun admin pertama (jika tidak ada seeder):

    ```bash
    php artisan tinker
    ```

    ```php
    \App\Models\User::create([
        'name'      => 'Admin',
        'email'     => 'admin@example.com',
        'password'  => \Illuminate\Support\Facades\Hash::make('password123'),
        'role'      => 'admin',
        'is_active' => true,
    ]);
    ```

5. Buat lock file installer agar middleware tidak memblokir akses:

    ```bash
    echo "installed" > storage/installed
    ```

6. Jalankan server:

    ```bash
    php artisan serve
    ```

---

### Langkah 7 — Buka Aplikasi

```text
http://localhost:8000/login
```

Login dengan akun admin yang dibuat di langkah sebelumnya, lalu mulai setup:

1. **Settings** → Isi nama toko, alamat, dan nomor telepon
2. **Categories** → Tambah kategori produk
3. **Suppliers** → Tambah data supplier
4. **Products** → Tambah produk dengan harga dan stok awal
5. **Users** → Tambah akun kasir jika diperlukan
6. **Promos** _(opsional)_ → Buat kode diskon untuk promosi

Setelah itu, akses halaman **Kasir** (`/sales/create`) untuk mulai bertransaksi.

---

### Troubleshooting Umum

| Error                                              | Solusi                                                                                                     |
| -------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `No application encryption key has been specified` | Jalankan `php artisan key:generate`                                                                        |
| `SQLSTATE: Access denied`                          | Periksa `DB_USERNAME` dan `DB_PASSWORD` di `.env`                                                          |
| `Target class [installed] does not exist`          | Daftarkan middleware di `bootstrap/app.php` (Laravel 12)                                                   |
| Foto produk tidak muncul                           | Jalankan `php artisan storage:link`                                                                        |
| Halaman installer terus muncul                     | Buat file `storage/installed` secara manual                                                                |
| `composer install` gagal                           | Pastikan PHP >= 8.1 dan ekstensi yang dibutuhkan aktif                                                     |
| `npm run build` gagal                              | Pastikan Node.js >= 16, hapus `node_modules` lalu `npm install` ulang                                      |
| Class Tailwind tidak muncul                        | Pastikan `@vite` ada di `<head>` layout dan sudah jalankan `npm run dev` atau `npm run build`              |
| Class dinamis Tailwind tidak ter-generate          | Tulis class secara eksplisit — Tailwind v4 tidak bisa scan string template seperti `` `bg-${color}-500` `` |

---

## 👤 Default Roles

| Role      | Access                                            |
| --------- | ------------------------------------------------- |
| `owner`   | Full access including reports and settings        |
| `admin`   | Full access including user and product management |
| `cashier` | POS screen and own transaction history            |

The first admin account is created during installation.

---

## 📁 Key Controllers

```text
app/Http/Controllers/
├── InstallerController.php   # Multi-step web installer
├── DashboardController.php   # Stats, charts, and low-stock alerts
├── SalesController.php       # POS transactions with promo & tax logic
├── PromoController.php       # Promo CRUD + real-time code validation API
├── ProductsController.php    # Product CRUD with image upload
├── CategoryController.php    # Category management (with deletion guard)
├── SupplierController.php    # Supplier CRUD with product-link guard
├── UserController.php        # User management with profile photos
├── PurchaseController.php    # Purchase orders and stock receiving
├── ExpensesController.php    # Expense logging and financial summary
└── SettingsController.php    # Store name, address, phone settings

app/Http/Middleware/
├── CheckInstalled.php        # Redirects to installer if not yet set up; blocks re-install
└── AutoMigrate.php           # Runs pending migrations automatically on each request
```

---

## 🧾 POS Transaction Flow

```text
Cashier scans/selects products
        ↓
Subtotal calculated client-side
        ↓
Optional: apply promo code (validated via API)
        ↓
Tax calculated on (Subtotal − Discount)
        ↓
Customer pays → change displayed
        ↓
Server validates → stock decremented → invoice saved
```

All final calculations are **re-validated server-side** in `SalesController@store` to prevent client-side manipulation.

---

## 🎟️ Promo Code Validation

The `POST /promos/check` endpoint validates a promo code in real-time. It checks:

1. Code existence
2. Active status (`is_active = true`)
3. Date range (`start_date` ≤ today ≤ `end_date`)
4. Usage quota (`used_count < usage_limit`)
5. Minimum purchase (`subtotal >= min_purchase`)

If valid, it returns the computed `discount_value` for the given subtotal.

---

## 📊 Dashboard Metrics

- **Total products** and **active promos**
- **Monthly revenue**, **monthly expenses**, and **net profit**
- **7-day sales chart** (daily aggregation)
- **Low stock alert** — products with stock ≤ 5

---

## 🗂️ Database Overview

| Table            | Purpose                            |
| ---------------- | ---------------------------------- |
| `users`          | Authentication and role management |
| `products`       | Product catalog                    |
| `categories`     | Product categories                 |
| `suppliers`      | Supplier records                   |
| `sales`          | Transaction headers                |
| `sales_items`    | Transaction line items             |
| `promos`         | Discount / promo code definitions  |
| `purchases`      | Purchase order headers             |
| `purchase_items` | Purchase order line items          |
| `expenses`       | Expense records                    |
| `settings`       | Store name, address, phone         |

---

## 🗺️ Route Overview

| Group                                    | Middleware                          | Description                               |
| ---------------------------------------- | ----------------------------------- | ----------------------------------------- |
| `/`                                      | —                                   | Welcome / landing page                    |
| `/login`, `POST /login`                  | `installed`                         | Guest authentication                      |
| `/logout`                                | —                                   | Session logout                            |
| `/install/*`                             | `CheckInstalled`                    | Web installer steps (blocked after setup) |
| `/dashboard`                             | `auth`, `installed`, `auto-migrate` | Main app entry point                      |
| `/products`, `/categories`, `/suppliers` | `auth`                              | Inventory management                      |
| `/purchases`, `/sales`, `/expenses`      | `auth`                              | Operations & transactions                 |
| `/promos`, `/users`                      | `auth`                              | Marketing & access control                |
| `POST /promos/check`                     | `auth`                              | Real-time promo code validation (AJAX)    |
| `/settings`                              | `auth`                              | Store settings (GET + POST)               |

---

## 🔒 Security Notes

- Passwords are hashed using Laravel's `Hash::make()` (bcrypt)
- CSRF protection enabled on all forms
- Promo codes and stock are **re-validated server-side** on every transaction
- The installer is automatically disabled after successful setup (`storage/installed` lock file)
- `CheckInstalled` middleware prevents re-running the installer once the lock file exists
- `AutoMigrate` middleware applies any pending migrations on startup — useful after deployments
- Supplier deletion is guarded: suppliers linked to products cannot be deleted
- Category deletion is guarded: categories linked to products cannot be deleted
- Sensitive database credentials are written to `.env` during installation and not re-exposed in subsequent requests

---

## 📜 License

This project is open-source under the [MIT License](LICENSE).

---

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you'd like to change.

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Commit your changes: `git commit -m 'Add your feature'`
4. Push: `git push origin feature/your-feature`
5. Open a Pull Request
