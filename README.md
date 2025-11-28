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
- **Login/Logout + Session** (role: `admin`, `karyawan`)
  - Validasi status akun (active/disabled)
  - Wajib ganti password pertama kali login
  - Redirect otomatis ke dashboard sesuai role
- **Dashboard Berbasis Role**
  - Dashboard Admin: Akses manajemen karyawan
  - Dashboard Karyawan: Akses fitur personal (absensi & cuti)
- **Manajemen Karyawan (Admin)**
  - CRUD data karyawan (NIK, nama, email, posisi, dll)
  - Aktivasi/Nonaktifkan akun karyawan
  - Generate temporary password otomatis
  - Soft delete (nonaktifkan) & Hard delete (hapus permanen - super_admin only)
- **Absensi Karyawan** (check-in/check-out, 1x per hari)
- **Pengajuan Cuti** (karyawan) + Approve/Reject (admin)
- super_admin role feature (masih di pertimbangkan)

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

### 1️⃣ Clone Repository (branch: feat/dashboard-admin)
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

4. **Konfigurasi Koneksi Database**
   
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
   - Ubah variabel `$username` (contoh: `'admin'`)
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
2. Buka menu **Manajemen Karyawan** (`/admin/karyawan`)
3. Klik **Tambah Karyawan**
4. Isi data karyawan (NIK, Nama, Email, dll)
5. **Centang opsi "Buat Akun Sekarang?"** jika ingin langsung membuat akun login
6. Sistem akan:
   - Membuat data karyawan
   - Generate username otomatis (dari NIK atau email)
   - Generate temporary password acak
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
│   ├── Controllers/             # Logic aplikasi
│   │   ├── AuthController.php   # Login, logout, change password
│   │   └── KaryawanController.php # CRUD karyawan (admin)
│   ├── Models/                  # Data access layer
│   │   └── Karyawan.php         # Model karyawan
│   ├── Views/                   # Template HTML
│   │   ├── layouts/             # Header & Footer
│   │   ├── auth/                # Login, change password
│   │   │   ├── login.php
│   │   │   └── change_password.php
│   │   ├── dashboard/           # Dashboard berbasis role
│   │   │   ├── admin.php
│   │   │   └── employee.php
│   │   └── karyawan/            # Manajemen karyawan (admin)
│   │       ├── index.php        # List karyawan
│   │       └── form.php         # Form tambah/edit
│   ├── Core/                    # Router, Database, Helper
│   │   ├── Database.php         # Koneksi database
│   │   ├── Router.php           # Routing
│   │   ├── Env.php              # Environment loader
│   │   └── Helpers.php          # Helper functions
│   └── config.php               # Konfigurasi
├── public/                      # Document root
│   ├── index.php                # Front controller
│   └── assets/                  # CSS, JS, images
│       └── css/
│           ├── input.css        # Tailwind input
│           └── output.css       # Compiled CSS
├── scripts/
│   └── register.php             # Script buat akun admin (dev only)
├── database/
│   └── query.sql                # Database schema
└── README.md
```

---

## 🐛 Troubleshooting

**Problem:** Halaman 404 Not Found
- **Solusi:** Pastikan Apache `mod_rewrite` sudah aktif di `httpd.conf`

**Problem:** CSS tidak muncul
- **Solusi:** Jalankan `npm run dev` untuk build Tailwind CSS

**Problem:** Database connection error
- **Solusi:** Cek konfigurasi di `app/config.php`, pastikan MySQL sudah running

**Problem:** Virtual Host tidak berfungsi
- **Solusi:** Pastikan sudah restart Apache setelah edit config, dan cek file `hosts` sudah benar

---

## 📚 Dokumentasi Lengkap
Lihat `ARCHITECTURE.md` untuk detail arsitektur, ERD, dan flowchart aplikasi.


