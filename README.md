# BarberKu — Premium Barbershop Booking System

Sistem booking barbershop berbasis **Laravel 11** dengan fitur QRIS Payment Gateway (Pak Kasir), notifikasi WhatsApp (Fonnte), dan admin dashboard lengkap.

## ✨ Fitur Utama

- **Booking Wizard** — Pelanggan memilih layanan, barber, tanggal, dan slot waktu.
- **QRIS Payment** — Pembayaran otomatis via Pak Kasir Payment Gateway.
- **Admin Dashboard** — Kelola layanan, staf, jadwal, dan booking dari satu panel.
- **CRUD Services & Staff** — Tambah, edit, hapus layanan dan karyawan via modal.
- **Staff Scheduling** — Atur jam kerja setiap staf per hari.
- **Customer Profile** — Riwayat booking dan tombol bayar langsung.
- **WhatsApp Notification** — Konfirmasi booking otomatis via Fonnte API.
- **Auth System** — Login/Register untuk customer. Admin bisa membuat akun Staff/Admin.

## 🛠️ Tech Stack

| Layer       | Technology                    |
| ----------- | ----------------------------- |
| Backend     | Laravel 11                    |
| Frontend    | Blade Templates + Tailwind CSS |
| Database    | MySQL                         |
| Payment     | Pak Kasir (QRIS)              |
| Notification| Fonnte (WhatsApp API)         |
| Build Tool  | Vite                          |

## 🚀 Instalasi & Setup Lokal

### Prasyarat

- **PHP** >= 8.2
- **Composer**
- **Node.js** >= 18 & **npm**
- **MySQL** (via Laragon, XAMPP, dll)

### Langkah-langkah

```bash
# 1. Clone repository
git clone https://github.com/YOUR_USERNAME/final-project.git
cd final-project

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate
```

### Konfigurasi Database

Edit file `.env` dan sesuaikan dengan database lokal Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fproject
DB_USERNAME=root
DB_PASSWORD=
```

> **Pastikan** database `fproject` sudah dibuat di MySQL sebelum menjalankan migration.

### Konfigurasi Payment & Notifikasi (Opsional)

```env
# Pak Kasir Payment Gateway
PAKKASIR_API_KEY=your_api_key_here
PAKKASIR_PROJECT=your_project_name_here

# Fonnte WhatsApp Notification
FONNTE_TOKEN=your_fonnte_token_here
```

> Jika dikosongkan, aplikasi tetap berjalan dengan **QR Demo** untuk simulasi pembayaran.

### Jalankan Migration & Seeder

```bash
# 6. Jalankan migration (buat tabel)
php artisan migrate

# 7. Jalankan seeder (isi data contoh)
php artisan db:seed
```

Data yang di-seed:
| Data           | Detail                                          |
| -------------- | ----------------------------------------------- |
| **Admin**      | `admin@barberku.com` / `password`               |
| **Customer**   | `john@example.com` / `password`                 |
| **Services**   | 6 layanan (Haircut, Beard, Treatment)            |
| **Staff**      | 3 barber (Mike, David, Alex)                     |
| **Schedules**  | Senin-Sabtu 09:00-21:00, Minggu libur            |

### Build & Jalankan

```bash
# 8. Build frontend assets
npm run build

# 9. Jalankan server development
php artisan serve
```

Buka browser ke **http://localhost:8000** 🎉

## 📁 Struktur Aplikasi

```
app/
├── Http/Controllers/
│   ├── AdminController.php     # CRUD admin (services, staff, bookings)
│   ├── AuthController.php      # Login, Register, Admin Register
│   ├── BookingController.php   # Wizard booking & slot API
│   ├── PaymentController.php   # Proses pembayaran & webhook
│   └── UserController.php      # Profile & booking history
├── Models/                     # Eloquent models
├── Services/
│   ├── PakKasirService.php     # Integrasi payment gateway
│   └── FonnteService.php       # Integrasi WhatsApp
resources/views/
├── layouts/
│   ├── app.blade.php           # Layout publik (navbar, footer)
│   └── admin.blade.php         # Layout admin (sidebar)
├── auth/                       # Login & Register
├── admin/                      # Dashboard, Services, Staff, Bookings, Users
├── bookings/                   # Wizard & Payment
└── user/                       # Customer profile
```

## 🔐 Akun Default

| Role     | Email                 | Password   |
| -------- | --------------------- | ---------- |
| Admin    | admin@barberku.com    | password   |
| Customer | john@example.com      | password   |


