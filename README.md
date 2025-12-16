# HRIS (Human Resource Information System)

Aplikasi HRIS sederhana dengan fitur inti: Login berbasis role, Dashboard, Manajemen Karyawan, Absensi Harian (Check-in/Check-out), dan Pengajuan/Persetujuan Cuti.

<div align="center">
  <img src="https://img.shields.io/badge/HTML5-v5-orange?logo=html5" />
  <img src="https://img.shields.io/badge/PHP-8.2-blue?logo=php" />
  <img src="https://img.shields.io/badge/JavaScript-ES6-yellow?logo=javascript" />
  <img src="https://img.shields.io/badge/MySQL-8.0-blue?logo=mysql" />
  <img src="https://img.shields.io/badge/TailwindCSS-4.1-38bdf8?logo=tailwindcss" />
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
- Backend: PHP 8.2+ (Native, no framework)
- Database: MySQL 8.0
- Frontend: HTML5 + Tailwind CSS + Vanilla JavaScript
- Detail arsitektur, ERD, flow & activity diagram: lihat `ARCHITECTURE.md`.

---

## 🚀 Setup

### Prasyarat
Pastikan sudah terinstall:
- **XAMPP** (PHP 8.2+, MySQL 8.0, Apache)
- **Node.js** (untuk Tailwind CSS)
- **Git**

### 1️⃣ Clone Repository
```bash
git clone https://github.com/IbnuSabilGitHub/Project-UAS.git
cd Project-UAS
```

### 2️⃣ Install Dependencies (Tailwind CSS + Flowbite)
```bash
npm install
```

**Apa yang diinstall:**
- **Tailwind CSS 4.1** - Utility-first CSS framework
- **Flowbite 4.0** - Component library berbasis Tailwind CSS (alert, table, form, dll)

### 3️⃣ Setup Database
1. Buka **phpMyAdmin** di browser: `http://localhost/phpmyadmin`
2. Buat database baru bernama `hris_db`
3. Import file SQL:
   ```sql
   -- Import dari file: database/query.sql
   ```
   Atau jalankan query yang ada di folder `database/query.sql`

4. **⚠️ Jika Database Sudah Ada (Migrasi dari Versi Lama)**
   
   Jika Anda sudah punya database `hris_db` dari versi sebelumnya, jalankan migration script untuk mengupdate kolom `position` menjadi ENUM:
   ```sql
   -- Import dari file: database/migration_position_enum.sql
   ```
   
   **Catatan:** Migration ini akan mengubah kolom `position` dari VARCHAR menjadi ENUM. Pastikan data existing sudah sesuai dengan nilai ENUM yang tersedia, atau lakukan update manual terlebih dahulu.

5. **Konfigurasi Koneksi Database**
   
   Project ini sudah menggunakan **environment variables (.env)** untuk konfigurasi database. Anda memiliki **2 cara**:

   #### **Cara 1: Menggunakan File `.env`** (Recommended)
   
   **Keuntungan:**
   - Lebih aman (credential tidak ter-commit ke Git)
   - Best practice untuk aplikasi modern
   
   **Langkah:**
   1. Copy file `.env.example` menjadi `.env`:
      ```bash
      cp .env.example .env
      ```
      Atau di Windows PowerShell:
      ```powershell
      Copy-Item .env.example .env
      ```
   
   2. Edit file `.env` dan sesuaikan dengan konfigurasi database Anda:
      ```env
      DB_HOST=localhost
      DB_NAME=hris_db
      DB_USER=root
      DB_PASS=
      ```
   
   3. File `.env` sudah otomatis di-load oleh `app/config.php` menggunakan class `Env`
   
   ⚠️ **Penting:** File `.env` sudah masuk `.gitignore`, jadi tidak akan ter-commit ke repository (aman!)

   #### **Cara 2: Hard-code di `app/config.php`** (Tidak Recommended)
   
   Jika tidak ingin menggunakan `.env`, Anda bisa langsung edit `app/config.php`:
   
   ```php
   <?php
   // Hapus atau comment baris berikut:
   // require_once __DIR__ . "/Core/Env.php";
   // Env::load(__DIR__ . "/../.env");
   
   // Lalu hard-code langsung:
   define("DB_HOST", "localhost");
   define("DB_NAME", "hris_db");
   define("DB_USER", "root");
   define("DB_PASS", "");
   ```
   
   ⚠️ **Kerugian:** Credential ter-expose di repository, tidak flexibel untuk multi-environment

### 4️⃣ Build Tailwind CSS
Jalankan perintah berikut untuk compile Tailwind CSS:
```bash
npm run dev
```
Atau jika ingin watch mode (auto-compile saat ada perubahan):

### 5️⃣ Running the Application

**📌 PENTING: Multi-Environment Support**

Aplikasi HRIS mendukung berbagai environment deployment dengan **automatic path detection**:

#### **Option 1: XAMPP/WAMP (Subfolder htdocs)** ✅ Default
```
URL: http://localhost/HRIS/
Document Root: htdocs/HRIS/
```
Cara menjalankan:
1. Copy project ke `C:/xampp/htdocs/HRIS/`
2. Start Apache dan MySQL di XAMPP Control Panel
3. Akses: `http://localhost/HRIS/`

#### **Option 2: PHP Built-in Server** 🚀 Recommended untuk Development
```bash
# Document root di folder public/ (Recommended)
php -S localhost:8000 -t public

# Atau document root di root project
php -S localhost:8000
```
URL: `http://localhost:8000`

**Keuntungan:**
- Tidak perlu XAMPP/Apache
- Cepat untuk testing
- Port bisa diganti (8080, 3000, dll)

#### **Option 3: Apache Virtual Host** 🎯 Recommended untuk Production
Setup virtual host dengan document root di `public/`:
```apache
<VirtualHost *:80>
    ServerName hris.local
    DocumentRoot "C:/xampp/htdocs/HRIS/public"
    <Directory "C:/xampp/htdocs/HRIS/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
URL: `http://hris.local`

#### **Option 4: Nginx**
```nginx
server {
    listen 80;
    server_name hris.local;
    root /var/www/hris/public;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        include fastcgi_params;
    }
}
```

**📖 Dokumentasi Lengkap:**
Untuk panduan deployment detail di berbagai environment, lihat: **`docs/DEPLOYMENT.md`**

**🧪 Testing Path Detection:**
Jalankan file test untuk memverifikasi path bekerja dengan benar:
```
http://localhost/HRIS/test-path.php      (XAMPP)
http://localhost:8000/test-path.php      (PHP Server)
http://hris.local/test-path.php          (Virtual Host)
```

### 6️⃣ Create Admin Account
```bash
npx @tailwindcss/cli -i ./public/assets/css/input.css -o ./public/assets/css/output.css --watch
```

---



## 🌐 Menjalankan Project di Localhost

>⚠️ PENTING: Rename Folder Project
>
>Setelah clone repository, **nama folder otomatis adalah `Project-UAS`** (sesuai nama repository di >GitHub). **Untuk menghindari kebingungan**, sangat disarankan untuk **rename folder** menjadi >`HRIS`agar sesuai dengan dokumentasi ini:
>

Anda bisa memilih salah satu dari **2 opsi** berikut:

### **Opsi 1: Menggunakan Folder `htdocs` XAMPP** ✅ (Cara Paling Mudah)

1. **Pindahkan folder project** ke dalam folder `htdocs` XAMPP:
   ```
   C:\xampp\htdocs\HRIS
   ```

2. **Start Apache & MySQL** di XAMPP Control Panel

3. **Akses aplikasi** di browser:
   ```
   http://localhost/HRIS/public
   ```

4. **Login dengan akun testing:**
   > ⚠️ Anda perlu membuat akun admin terlebih dahulu menggunakan script `register.php` (lihat bagian [Membuat Akun Admin](#membuat-akun-admin-development-only))
   
   Setelah membuat akun admin, login dengan kredensial yang Anda buat di script `register.php`

---

### **Opsi 2: Menggunakan Virtual Host** 🔧 (Recommended untuk Development)

**Keuntungan:**
- Akses dengan URL yang lebih clean: `http://hris.local`
- Project bisa di-clone di lokasi manapun (tidak harus di `htdocs`)

#### **Langkah-langkah:**

#### **A. Edit File `hosts`**
1. Buka **Notepad as Administrator**
2. Buka file: `C:\Windows\System32\drivers\etc\hosts`
3. Secara default, Notepad hanya menampilkan file teks (*.txt). Untuk melihat file hosts, Anda harus:
Lihat di pojok kanan bawah jendela Open, ubah dropdown dari Text Documents (*.txt) menjadi All Files(*.*).
4. Tambahkan baris berikut di akhir file:
   ```
   127.0.0.1    hris.local
   ```
5. Save file

#### **B. Konfigurasi Virtual Host di Apache**
1. Buka file konfigurasi Virtual Host XAMPP:
   ```
   C:\xampp\apache\conf\extra\httpd-vhosts.conf
   ```

2. Tambahkan konfigurasi berikut di akhir file:
   ```apache
    <VirtualHost *:80>
        DocumentRoot "C:/Path/Ke/Folder/Proyek/Anda"
        ServerName project-a.test
        <Directory "C:/Path/Ke/Folder/Proyek/Anda">
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>

    # (Opsional tapi Direkomendasikan: Biarkan htdocs tetap berfungsi)
    <VirtualHost *:80>
        DocumentRoot "C:/xampp/htdocs"
        ServerName localhost
    </VirtualHost>
   ```
   
>⚠️ **Catatan:** Sesuaikan path `DocumentRoot` dengan lokasi folder project Anda!

3. Pastikan file `httpd.conf` sudah meng-include virtual host:
   - Buka: `C:\xampp\apache\conf\httpd.conf`
   - Cari baris berikut dan pastikan **tidak ada tanda `#`** di depannya:
     ```apache
     Include conf/extra/httpd-vhosts.conf
     ```

4. **Restart Apache** di XAMPP Control Panel (Stop lalu Start lagi).

5. **Akses aplikasi** di browser:
   ```
   http://hris.local
   ```

6. **Login dengan akun yang telah dibuat** (lihat bagian [Membuat Akun Admin](#membuat-akun-admin-development-only))

---

## 📝 Testing Aplikasi

### Membuat Akun Admin (Development Only)
> ⚠️ **Perubahan Penting:** Script `register.php` sekarang **HANYA bisa membuat akun admin**, tidak bisa membuat akun karyawan lagi.

**Mengapa?** Karena akun karyawan sekarang wajib terkait dengan data karyawan yang dibuat melalui fitur **Manajemen Karyawan** di dashboard admin.

**Cara membuat akun admin:**
1. Buka folder `scripts/`
2. Edit file `register.php`:
   - Ubah variabel `$email` (contoh: `'admin@hris.local'`)
   - Ubah variabel `$password` (contoh: `'admin123'`)
   - Pastikan `$role = 'admin'`
3. Jalankan di CLI (Command Prompt/Terminal):
   ```bash
   cd scripts
   php -d extension=mysqli register.php
   ```
4. Cek di database `hris_db` tabel `users` untuk memastikan akun admin sudah terbuat

**Cara membuat akun karyawan:**
1. Login sebagai admin
2. Buka menu **Manajemen Karyawan** (`/admin/employees`)
3. Klik **Tambah Karyawan**
4. Isi data karyawan (NIK 16 digit, Nama, Email, Posisi, Tanggal Bergabung, dll)
5. **Centang opsi "Buat Akun Sekarang?"** jika ingin langsung membuat akun login
6. Sistem akan:
   - Membuat data karyawan
   - Generate username otomatis dari email (misal: `john.doe@company.com` → `john.doe`)
   - Generate temporary password acak 12 karakter
   - Menampilkan kredensial login (catat untuk diberikan ke karyawan)
   - User wajib ganti password saat login pertama kali


---

## 🛠️ Development

### Watch Mode Tailwind CSS
Untuk development, jalankan Tailwind dalam watch mode agar otomatis compile saat ada perubahan:
```bash
npm run dev
```

### Struktur Project Saat Ini
```
HRIS/
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
│   │   ├── Attendance.php              # Model absensi
│   │   ├── Karyawan.php                # Model karyawan
│   │   ├── LeaveRequest.php            # Model leave requests
│   │   └── PengajuanCuti.php           # Model pengajuan cuti (admin view)
│   ├── Views/                          # Template HTML (role-based structure)
│   │   ├── admin/                      # Admin-only views
│   │   │   ├── dashboard.php           # Dashboard admin
│   │   │   ├── attendance/
│   │   │   │   └── index.php           # Manajemen absensi
│   │   │   ├── employees/
│   │   │   │   ├── index.php           # List karyawan
│   │   │   │   └── form.php            # Form tambah/edit karyawan
│   │   │   └── leave/
│   │   │       └── index.php           # Manajemen pengajuan cuti
│   │   ├── employee/                   # Employee-only views
│   │   │   ├── dashboard.php           # Dashboard karyawan
│   │   │   ├── attendance.php          # Absensi karyawan
│   │   │   └── leave/
│   │   │       ├── index.php           # Riwayat cuti
│   │   │       └── create.php          # Form pengajuan cuti
│   │   ├── auth/                       # Public authentication
│   │   │   ├── login.php               # Landing page login
│   │   │   ├── login-admin.php         # Login admin
│   │   │   ├── login-karyawan.php      # Login karyawan
│   │   │   └── change-password.php     # Ganti password
│   │   ├── layouts/                    # Templates & Components
│   │   │   ├── header.php
│   │   │   ├── footer.php
│   │   │   ├── sidebar.php
│   │   │   ├── sidebar-admin.php
│   │   │   ├── sidebar-karyawan.php
│   │   │   └── components/
│   │   │       ├── alerts.php          # Reusable alert component
│   │   │       └── pagination.php      # Reusable pagination
│   │   └── errors/                     # Error pages
│   │       ├── 404.php
│   │       └── 403.php
│   ├── Core/                           # Router, Database, Helper
│   │   ├── Database.php                # Koneksi database
│   │   ├── Router.php                  # Routing system
│   │   ├── Env.php                     # Environment loader (.env)
│   │   └── Helpers.php                 # Helper functions
│   └── config.php                      # Konfigurasi aplikasi
├── public/                             # Document root
│   ├── index.php                       # Front controller
│   └── assets/                         # CSS, JS, images
│       ├── css/
│       │   ├── input.css               # Tailwind input
│       │   └── output.css              # Compiled CSS
│       └── js/
│           ├── theme.js                # Theme switcher (light/dark)
│           └── apply-theme.js          # Apply theme on load
├── storage/                            # File storage (outside web root)
│   └── leave_attachments/              # Leave request attachments
├── scripts/
│   └── register.php                    # Script buat akun admin (dev only)
├── database/
│   └── query.sql                       # Database schema
├── docs/                               # Documentation
│   ├── FRONTEND_DASHBOARD_ADMIN.md
│   ├── FRONTEND_DASHBOARD_KARYAWAN.md
│   └── SEPARATED_LOGIN.md
├── .env.example                        # Environment template
├── .gitignore
├── ARCHITECTURE.md                     # Architecture documentation
├── CHANGELOG.md                        # Version history
├── package.json                        # Node dependencies
├── tailwind.config.js                  # Tailwind configuration
└── README.md
```

---

## 🔒 Security Features

### Secure File Storage
- **File Upload Protection**
  - Uploaded files disimpan di `storage/` (outside web root)
  - Tidak bisa diakses langsung via URL
  - Authentication required untuk view/download files
  
- **File Access Control**
  - Role-based access control untuk leave attachments
  - Admin: Akses semua file pengajuan cuti
  - Karyawan: Hanya file milik sendiri
  - File viewing melalui controller endpoint: `/file/leave/{id}`

- **File Validation**
  - Supported formats: PDF, JPG, PNG
  - Maximum file size: 10MB
  - File type validation (MIME type check)
  - Secure filename sanitization

### Database Security
- **Email-based Authentication**
  - Login menggunakan email (lebih aman dari username)
  - Kolom `users.email` VARCHAR(150) UNIQUE
  - Password hashing dengan `password_hash()` (bcrypt)
  
- **NIK Standardization**
  - NIK field: CHAR(16) sesuai standar KTP Indonesia
  - Input validation untuk 16 digit

- **Prepared Statements**
  - Semua query menggunakan prepared statements
  - Protection dari SQL injection
  - Type binding untuk parameter ('s', 'i', 'd')

### Session Security
- Session-based authentication
- Auto-logout untuk inactive sessions
- CSRF protection (recommended untuk production)

---

## ✨ Key Improvements

### Performance Optimization
- **Search by Name Instead of Dropdown**
  - Text input search untuk filter karyawan (scalable)
  - MySQL LIKE query dengan wildcard
  - Lebih efisien bandwidth dibanding dropdown dengan ratusan options
  - Recommended: Add database index `CREATE INDEX idx_name ON karyawan(name)`

### User Experience
- **Real-time Statistics**
  - Dashboard cards dengan data live dari database
  - Auto-update tanpa reload (untuk beberapa fitur)
  - Color-coded badges untuk status visualization
  
- **Advanced Filtering**
  - Multiple filter kombinasi (status + date range + search)
  - Filter state preserved setelah reload
  - Export CSV dengan filter yang diterapkan
  
- **Responsive Design**
  - Mobile-friendly dengan Tailwind CSS
  - Flowbite components untuk konsistensi UI
  - Dropdown actions untuk space efficiency

### Developer Experience
- **Environment Variables**
  - `.env` file untuk database credentials
  - `.env.example` sebagai template
  - `.gitignore` untuk security
  
- **MVC Pattern**
  - Clear separation of concerns
  - Role-based view structure
  - Reusable components (alerts, pagination)
  
- **Code Organization**
  - Consistent naming conventions
  - Helper functions di `Core/Helpers.php`
  - Base controller untuk shared functionality

---

## 📋 Database Schema Highlights

### Tabel Users
- `email` VARCHAR(150) UNIQUE - Email untuk login
- `password` VARCHAR(255) - Hashed password (bcrypt)
- `role` ENUM('admin','karyawan') - User role
- `status` ENUM('active','disabled') - Account status
- `must_change_password` BOOLEAN - Force password change flag

### Tabel Karyawan
- `nik` CHAR(16) UNIQUE - NIK sesuai KTP (16 digit)
- `annual_leave_quota` INT DEFAULT 12 - Jatah cuti tahunan
- `employment_status` ENUM('active','on_leave','resigned')
- `user_id` INT NULLABLE - Foreign key ke users (opsional)

### Tabel Leave Requests
- `leave_type` ENUM('annual','sick','emergency','unpaid')
- `total_days` INT - Auto-calculated (include weekends)
- `attachment_file` VARCHAR(255) - Path to uploaded file
- `approved_by` INT - Admin user ID yang approve
- `rejection_reason` TEXT - Alasan penolakan

### Tabel Attendance
- `check_in` TIME - Jam masuk
- `check_out` TIME - Jam keluar
- `status` ENUM('present','late','half_day') - Status kehadiran
- Unique constraint: 1 record per karyawan per hari

---

## 🐛 Troubleshooting

**Problem:** Halaman 404 Not Found
- **Solusi:** Pastikan Apache `mod_rewrite` sudah aktif di `httpd.conf`

**Problem:** CSS tidak muncul
- **Solusi:** Jalankan `npm run dev` untuk build Tailwind CSS

**Problem:** Database connection error
- **Solusi:** Cek konfigurasi di `app/config.php`, pastikan MySQL sudah running
- **Solusi:** Pastikan file `.env` sudah dibuat dan diisi dengan benar

**Problem:** Virtual Host tidak berfungsi
- **Solusi:** Pastikan sudah restart Apache setelah edit config, dan cek file `hosts` sudah benar

**Problem:** File upload tidak bisa diakses
- **Solusi:** Pastikan folder `storage/leave_attachments/` sudah dibuat dengan permission yang benar
- **Solusi:** Akses file melalui endpoint `/file/leave/{id}`, bukan direct URL

**Problem:** Login dengan email tidak berhasil
- **Solusi:** Pastikan sudah update database schema (tabel `users` menggunakan `email`, bukan `username`)
- **Solusi:** Jalankan migration database dari `database/query.sql`

---

## 📚 Dokumentasi Lengkap
- **`ARCHITECTURE.md`** - Detail arsitektur, ERD, dan flowchart aplikasi
- **`CHANGELOG.md`** - Riwayat perubahan dan feature updates
- **`docs/SEPARATED_LOGIN.md`** - Dokumentasi fitur separated login
- **`docs/FRONTEND_DASHBOARD_ADMIN.md`** - Dokumentasi dashboard admin
- **`docs/FRONTEND_DASHBOARD_KARYAWAN.md`** - Dokumentasi dashboard karyawan

---

## 📄 License
Project ini dibuat untuk keperluan pembelajaran dan tugas akhir semester.

---

## 👥 Team
Developed by **kELOMPOK 8**.


