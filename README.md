# HRIS (Human Resource Information System)

Aplikasi HRIS sederhana dengan fitur inti: Login berbasis role, Dashboard, Manajemen Karyawan, Absensi Harian (Check-in/Check-out), dan Pengajuan/Persetujuan Cuti.

<div align="center">
  <img src="https://img.shields.io/badge/HTML5-v5-orange?logo=html5" />
  <img src="https://img.shields.io/badge/PHP-8.3-blue?logo=php" />
  <img src="https://img.shields.io/badge/JavaScript-ES6-yellow?logo=javascript" />
  <img src="https://img.shields.io/badge/MySQL-8.0-blue?logo=mysql" />
  <img src="https://img.shields.io/badge/TailwindCSS-4.1-38bdf8?logo=tailwindcss" />
  <img src="https://img.shields.io/badge/WSL2-Ubuntu-e95420?logo=ubuntu" />
</div>


## Fitur Utama

### 🔐 Authentication & Authorization
- **Separated Login Pages**
  - Landing page dengan pilihan login Admin atau Karyawan
  - Login Admin: `/admin/login`
  - Login Karyawan: `/karyawan/login`
  - Validasi role-based access
- **Session Management**
  - Login berbasis email
  - Validasi status akun (active/disabled)
  - Wajib ganti password pertama kali login
  - Redirect otomatis ke dashboard sesuai role

### 📊 Dashboard Berbasis Role
- **Dashboard Admin**
  - Statistik karyawan (total, by status, by posisi)
  - Statistik pengajuan cuti keseluruhan
  - Statistik absensi keseluruhan
  - Akses ke semua fitur manajemen
- **Dashboard Karyawan**
  - Statistik cuti personal (terpakai, tersisa, pending)
  - Statistik absensi personal (bulan ini & keseluruhan)
  - Riwayat 7 hari terakhir
  - Quick access ke fitur absensi & pengajuan cuti

### 👥 Manajemen Karyawan (Admin)
- **CRUD Data Karyawan**
  - NIK (16 karakter sesuai KTP), nama, email, posisi, tanggal bergabung
  - **Posisi karyawan menggunakan ENUM** dengan pilihan:
    - Backend Developer
    - Frontend Developer
    - Fullstack Developer
    - DevOps / Cloud Engineer
    - QA / Software Tester
  - Filter karyawan berdasarkan status (Aktif, Cuti, Resign) dan posisi
  - Pencarian karyawan by nama atau NIK
  - Statistik (total karyawan, by status, karyawan baru)
  - **Pagination (Pembagian Halaman)** demi performa scroll yang super ringan dan optimal
- **Manajemen Akun**
  - Buat akun otomatis saat tambah karyawan (opsional)
  - Generate temporary password acak
  - Aktivasi/Nonaktifkan akun karyawan
  - Soft delete (nonaktifkan) & Hard delete (hapus permanen - admin only)
- **User Interface**
  - Dropdown actions untuk aksi per karyawan
  - Status badge dengan color coding
  - Responsive table dengan Flowbite components

### ⏰ Manajemen Absensi
- **Fitur Karyawan**
  - Check-in/Check-out harian (1x per hari)
  - Validasi jam kerja (terlambat, half day)
  - Riwayat absensi personal
  - Aturan untuk menentukan status kehadiran:
     - Jam kerja valid: 06:00 - 23:59 (di luar jam ini = terlambat)
     - Hadir tepat waktu (present): Check-in jam 06:00 - 09:00
     - Half day: Check-in antara jam 09:01 sampai 09:15 (toleransi 15 menit)
     - Terlambat (late): Check-in setelah jam 09:15 atau sebelum jam 06:00
- **Fitur Admin**
  - View semua absensi karyawan
  - Filter by periode (Hari Ini, Minggu Ini, Bulan Ini, Semua Data)
  - Filter by status (Hadir, Terlambat, Half Day)
  - Pencarian karyawan by nama
  - Export data ke CSV dengan filter yang diterapkan
  - Statistik real-time (total, tepat waktu, terlambat, half day, belum checkout)

### 🏖️ Manajemen Cuti
- **Fitur Karyawan**
  - Ajukan cuti dengan 4 jenis: Annual, Sick, Emergency, Unpaid
  - Upload dokumen pendukung (PDF/JPG/PNG, max 10MB)
  - Perhitungan otomatis total hari cuti
  - Tracking status: Pending, Approved, Rejected
  - View riwayat pengajuan dengan statistik personal
- **Fitur Admin**
  - View semua pengajuan cuti
  - Filter by status (Approved, Pending, Rejected)
  - Filter by periode (Hari Ini, Minggu Ini, Bulan Ini, Semua Data)
  - Pencarian karyawan by nama
  - Approve/Reject pengajuan dengan alasan
  - View & download dokumen pendukung
  - Export data ke CSV
  - Statistik pengajuan (total, pending, approved, rejected)
  - Badge notifikasi dinamis untuk pending requests

## Arsitektur
- Pola: **MVC Pattern** (Model-View-Controller)
- Backend: PHP 8.3+ (Native, no framework)
- Database: MySQL 8.0
- Frontend: HTML5 + Tailwind CSS + Vanilla JavaScript
- Detail arsitektur, ERD, flow & activity diagram: lihat `ARCHITECTURE.md`.

---

## 🚀 Setup (WSL2 / Linux)

> Panduan ini ditujukan untuk pengguna **WSL2** (Windows Subsystem for Linux) dengan distro Ubuntu,
> atau Linux native. Semua perintah dijalankan di terminal WSL/Linux.

### Prasyarat

Pastikan sudah terinstall di WSL:

```bash
# Cek versi
php --version      # PHP 8.2+
mysql --version    # MySQL 8.0+
node --version     # Node.js 18+
npm --version
```

Jika belum terinstall:

```bash
# Update package list
sudo apt update

# Install PHP + ekstensi yang dibutuhkan
sudo apt install -y php php-mysqli php-mbstring php-xml

# Install MySQL
sudo apt install -y mysql-server

# Install Node.js (via NodeSource, agar versi terbaru)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

---

### 1️⃣ Clone Repository

```bash
git clone https://github.com/IbnuSabilGitHub/Project-UAS.git HRIS-APP
cd HRIS-APP
```

---

### 2️⃣ Install Dependencies (Tailwind CSS + Flowbite)

```bash
npm install
```

---

### 3️⃣ Setup Database

#### A. Pastikan MySQL berjalan

```bash
sudo systemctl start mysql
sudo systemctl enable mysql   # agar otomatis start saat boot WSL
```

#### B. Konfigurasi autentikasi MySQL root

Di Ubuntu, user `root` MySQL defaultnya menggunakan `auth_socket` — PHP tidak bisa konek tanpa password. Ubah dulu:

```bash
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password_anda'; FLUSH PRIVILEGES;"
```

> Ganti `password_anda` dengan password yang Anda inginkan.

#### C. Import schema database

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS hris_db;"
mysql -u root -p hris_db < database/query.sql
```

---

### 4️⃣ Konfigurasi Environment

Copy file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Edit file `.env` sesuaikan dengan kredensial MySQL Anda:

```env
DB_HOST=localhost
DB_NAME=hris_db
DB_USER=root
DB_PASS=password_anda
```

---

### 5️⃣ Build Tailwind CSS

```bash
npx @tailwindcss/cli -i ./public/assets/css/input.css -o ./public/assets/css/output.css
```

Untuk **watch mode** saat development (auto-compile saat ada perubahan file):

```bash
npx @tailwindcss/cli -i ./public/assets/css/input.css -o ./public/assets/css/output.css --watch
```

---

### 6️⃣ Buat Folder Storage

```bash
mkdir -p storage/leave_attachments
chmod 755 storage/leave_attachments
```

---

### 7️⃣ Buat Akun Admin

Jalankan perintah berikut di terminal. Skrip ini bersifat interaktif dan akan menanyakan email serta password admin yang ingin Anda buat (atau tekan **Enter** langsung untuk menggunakan nilai default):

```bash
cd scripts
php register.php
cd ..
```

* **Default Email**: `admin@hris.local`
* **Default Password**: `admin123`

---

### 8️⃣ Suntik Data Karyawan Tiruan (Mock Data)

Aplikasi menyediakan data tiruan sebanyak 1.000 data karyawan untuk pengujian performa skala besar. Anda dapat menyuntikkan data ini ke database dengan menjalankan perintah:

```bash
php scripts/inject_karyawan.php
```

---

### 9️⃣ Jalankan Aplikasi

```bash
php -S localhost:8000 -t public
```

Buka browser di Windows dan akses: **`http://localhost:8000`**

> Karena WSL2 mem-forward port ke Windows secara otomatis, `localhost:8000` di browser Windows akan langsung tersambung ke server PHP di WSL.

---

## 📝 Login Pertama

Setelah setup selesai, login dengan akun admin yang dibuat di langkah 7:

| Role  | URL                                   |
|-------|---------------------------------------|
| Admin | `http://localhost:8000/admin/login`    |
| Karyawan | `http://localhost:8000/karyawan/login` |

Akun karyawan dibuat melalui menu **Manajemen Karyawan** di dashboard admin (centang opsi "Buat Akun Sekarang?" saat tambah karyawan).

---

## 🛠️ Development

### Watch Mode Tailwind CSS

Buka **dua terminal WSL**:

- Terminal 1 — PHP server:
  ```bash
  php -S localhost:8000 -t public
  ```
- Terminal 2 — Tailwind watch:
  ```bash
  npx @tailwindcss/cli -i ./public/assets/css/input.css -o ./public/assets/css/output.css --watch
  ```

---

### Struktur Project

```
HRIS-APP/
├── app/
│   ├── Controllers/                    # Logic aplikasi
│   │   ├── AuthController.php          # Login, logout, change password
│   │   ├── AttendanceController.php    # Manajemen absensi (admin & karyawan)
│   │   ├── BaseController.php          # Base controller dengan helper methods
│   │   ├── CutiController.php          # Manajemen pengajuan cuti (admin)
│   │   ├── FileController.php          # Secure file viewing
│   │   ├── KaryawanController.php      # CRUD karyawan (admin)
│   │   └── LeaveController.php         # Pengajuan cuti (karyawan)
│   ├── Models/                         # Data access layer
│   │   ├── Attendance.php
│   │   ├── Karyawan.php
│   │   ├── LeaveRequest.php
│   │   └── PengajuanCuti.php
│   ├── Views/                          # Template HTML (role-based)
│   │   ├── admin/
│   │   ├── employee/
│   │   ├── auth/
│   │   ├── layouts/
│   │   └── errors/
│   ├── Core/
│   │   ├── Database.php
│   │   ├── Router.php
│   │   ├── Env.php
│   │   └── Helpers.php
│   └── config.php
├── public/                             # Document root
│   ├── index.php
│   └── assets/
│       ├── css/
│       │   ├── input.css               # Tailwind source
│       │   └── output.css              # Compiled CSS
│       └── js/
├── storage/
│   └── leave_attachments/              # Upload files (outside web root)
├── scripts/
│   └── register.php                    # Buat akun admin (dev only)
├── database/
│   └── query.sql                       # Schema database
├── .env.example
├── .env                                # Konfigurasi lokal (tidak di-commit)
├── package.json
└── README.md
```

---

## 🐛 Troubleshooting (WSL)

**Problem:** `ERROR 1698 (28000): Access denied for user 'root'@'localhost'`
- MySQL root di Ubuntu menggunakan `auth_socket` secara default.
- Jalankan perintah di langkah 3B untuk mengubahnya ke password auth.

**Problem:** MySQL tidak bisa start
- Di WSL, systemd mungkin tidak aktif di versi lama. Coba: `sudo service mysql start`
- Atau aktifkan systemd di WSL2: tambahkan `[boot] systemd=true` di `/etc/wsl.conf`, lalu restart WSL.

**Problem:** `localhost:8000` tidak bisa diakses dari browser Windows
- Pastikan PHP server berjalan (`php -S localhost:8000 -t public`)
- WSL2 secara otomatis mem-forward port, tidak perlu konfigurasi tambahan.
- Jika tetap tidak bisa, coba akses via IP WSL: jalankan `hostname -I` di WSL untuk mendapatkan IP-nya.

**Problem:** CSS tidak muncul / tampilan berantakan
- Pastikan sudah menjalankan build Tailwind (langkah 5).
- File `public/assets/css/output.css` harus ada.

**Problem:** `storage/leave_attachments/` tidak ada
- Jalankan: `mkdir -p storage/leave_attachments && chmod 755 storage/leave_attachments`

**Problem:** Permission denied saat upload file
- Pastikan folder `storage/leave_attachments/` bisa ditulis oleh user yang menjalankan PHP:
  ```bash
  chmod 775 storage/leave_attachments
  ```

---

## 🔒 Security Features

- File upload disimpan di `storage/` (outside web root), tidak bisa diakses langsung via URL
- Semua query menggunakan prepared statements (anti SQL injection)
- Password di-hash dengan bcrypt (`password_hash()`)
- Role-based access control untuk semua endpoint
- File access melalui controller endpoint: `/file/leave/{id}`

---

## 📋 Database Schema Highlights

| Tabel | Kolom Penting |
|-------|--------------|
| `users` | `email` UNIQUE, `password_hash` (bcrypt), `role` ENUM, `status` ENUM, `must_change_password` |
| `karyawan` | `nik` CHAR(16) UNIQUE, `position` ENUM, `employment_status` ENUM |
| `leave_requests` | `leave_type` ENUM, `total_days`, `attachment_file`, `approved_by` |
| `attendance` | `check_in` DATETIME, `check_out` DATETIME, `status` ENUM |

---

## 📚 Dokumentasi Lengkap
- **`ARCHITECTURE.md`** - Detail arsitektur, ERD, dan flowchart
- **`CHANGELOG.md`** - Riwayat perubahan
- **`docs/SEPARATED_LOGIN.md`** - Fitur separated login
- **`docs/FRONTEND_DASHBOARD_ADMIN.md`** - Dashboard admin
- **`docs/FRONTEND_DASHBOARD_KARYAWAN.md`** - Dashboard karyawan

---

## 📄 License
Project ini dibuat untuk keperluan pembelajaran dan tugas akhir semester.

## 👥 Team
Developed by **KELOMPOK 3**.
