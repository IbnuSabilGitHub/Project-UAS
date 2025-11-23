# Changelog

All notable changes to this project will be documented in this file.

## [add employee management & enhanced authentication] - 2024-11-23

### ✨ Added

#### **Manajemen Karyawan (Admin)**
- **KaryawanController.php** - Controller lengkap untuk CRUD karyawan
  - `index()` - Menampilkan daftar semua karyawan dengan info akun
  - `create()` - Form tambah karyawan baru
  - `create_account()` - Membuat karyawan + akun (opsional) dengan temp password
  - `edit()` - Form edit karyawan
  - `update()` - Update data karyawan
  - `delete()` - Hard delete karyawan & akun (super_admin only)
  - `deactivate()` - Soft delete (nonaktifkan karyawan & akun)
  - `activateAccount()` - Aktivasi akun untuk karyawan yang belum punya akun

- **Karyawan Model** (`app/Models/Karyawan.php`)
  - CRUD operations untuk tabel `karyawan`
  - Relasi dengan tabel `users` (karyawan_id)
  - Method `allWithUser()` - Join karyawan dengan info akun
  - Method `createAccount()` - Generate akun dengan temp password
  - Method `getUserByKaryawanId()` - Ambil info akun karyawan
  - Method `deactivate()` - Nonaktifkan karyawan (soft delete)
  - Method `deleteWithUser()` - Hapus permanen karyawan + akun

- **Views Karyawan**
  - `app/Views/karyawan/index.php` - List karyawan dengan status akun, employment, dan aksi
  - `app/Views/karyawan/form.php` - Form tambah/edit karyawan dengan opsi "Buat Akun Sekarang"

#### **Dashboard Berbasis Role**
- `app/Views/dashboard/admin.php` - Dashboard khusus admin dengan menu manajemen
- `app/Views/dashboard/employee.php` - Dashboard khusus karyawan (placeholder)
- Separated dashboard routes:
  - `/admin/dashboard` - Admin dashboard
  - `/karyawan/dashboard` - Employee dashboard

#### **Fitur Keamanan & Authentication**
- **Change Password Page** (`app/Views/auth/change_password.php`)
  - Wajib ganti password saat pertama login (flag `must_change_password`)
  - Validasi minimal 8 karakter
  - Konfirmasi password matching
  
- **Account Status Validation**
  - Cek status akun saat login (active/disabled/locked)
  - Auto-redirect ke change password jika `must_change_password = 1`
  - Session protection untuk halaman yang memerlukan authentication

#### **Database Schema Updates**
- Tabel `karyawan`:
  - Field baru: `employment_status` (active/resigned/terminated)
  - Unique constraint pada `nik` dan `email`
  
- Tabel `users`:
  - Role baru: `super_admin` (bisa hard delete)
  - Field baru: `status` (active/disabled/locked)
  - Field baru: `must_change_password` (TINYINT)
  - Field baru: `password_last_changed` (DATETIME)
  - Foreign key `karyawan_id` → `karyawan(id)` dengan ON DELETE SET NULL
  - Unique constraint pada `karyawan_id`

#### **Routing Updates**
- `/` dan `/login` (GET) - Login page dengan auto-redirect jika sudah login
- `/admin/karyawan/*` - CRUD routes untuk manajemen karyawan
- `/change-password` - GET & POST untuk ganti password

#### **UI/UX Improvements**
- Integrasi **Flowbite 4.0** (Tailwind CSS component library)
  - Enterprise theme dengan custom colors
  - Alert components (success/error messages)
  - Table styling untuk list karyawan
  - Form components dengan validation states
  
- Badge indicators untuk:
  - Status akun (Aktif/Belum Ada/Disabled)
  - Employment status (Active/Resigned/Terminated)
  - Must change password flag

### 🔄 Changed

#### **AuthController**
- Refactored `loginPage()`:
  - Auto-redirect ke dashboard jika sudah login
  - Prevent double login
  
- Enhanced `login()` method:
  - Validasi status akun (hanya `active` yang bisa login)
  - Auto-redirect ke `/change-password` jika `must_change_password = 1`
  - Role-based redirect (admin → `/admin/dashboard`, karyawan → `/karyawan/dashboard`)
  
- Deprecated `dashboard()` method:
  - Sekarang hanya redirect ke role-specific dashboard
  - Backward compatibility maintained

#### **Router**
- Refactored route structure:
  - Separated GET dan POST untuk `/login`
  - Added role-specific dashboard routes
  - Added CRUD routes untuk karyawan management
  - Added change password routes

#### **register.php Script**
- **Breaking Change:** Sekarang HANYA bisa membuat akun admin
- Untuk akun karyawan, wajib dibuat melalui dashboard admin
- Alasan: Enforce relasi `users.karyawan_id` → `karyawan.id`
- CLI-only access (reject web access)

#### **Frontend Dependencies**
- Added Flowbite 4.0.1
- Updated Tailwind CSS configuration
- Added Google Fonts: Shantell Sans

### 🗑️ Deprecated

- `app/Views/dashboard/index.php` - Replaced by role-specific dashboards
  - Masih ada untuk backward compatibility
  - Akan dihapus di versi berikutnya

### 📝 Documentation

- Updated `README.md`:
  - New section: "Membuat Akun Admin" (development only)
  - Updated "Fitur Utama" dengan status implementasi (✅ / 🚧)
  - Updated login instructions (tidak ada default test account)
  - Updated project structure tree
  
- Updated `ARCHITECTURE.md`:
  - Flowchart update: Admin/HR dashboard routes
  - Added API specification section
  - Added route list untuk admin & employee

### 🐛 Bug Fixes

- Fixed session management di semua controller methods
- Fixed password validation (min 8 characters)
- Fixed SQL injection vulnerability dengan prepared statements
- Fixed XSS vulnerability dengan `htmlspecialchars()` di semua output

### 🔒 Security

- Enforce `must_change_password` untuk akun baru
- Temporary password auto-generated (10 karakter random)
- Status validation saat login (prevent disabled account login)
- CLI-only access untuk register script
- Foreign key constraints untuk data integrity

---

## Cara Testing Fitur Baru

### 1. Membuat Akun Admin
```bash
cd scripts
php -d extension=mysqli register.php
```

### 2. Login sebagai Admin
- Buka `http://localhost/HRIS/public`
- Login dengan kredensial yang dibuat di step 1

### 3. Test Manajemen Karyawan
1. Klik menu "Manajemen Karyawan"
2. Klik "Tambah Karyawan"
3. Isi form:
   - NIK: `12345`
   - Nama: `John Doe`
   - Email: `john@example.com`
   - Posisi: `Developer`
   - **Centang "Buat Akun Sekarang?"**
4. Submit → Akan muncul kredensial login
5. Logout dan login sebagai `john@example.com` dengan temp password
6. Sistem akan redirect ke `/change-password`
7. Ganti password (min 8 karakter)
8. Akan redirect ke dashboard karyawan

---

## Breaking Changes ⚠️

1. **register.php tidak bisa membuat akun karyawan lagi**
   - Solution: Gunakan dashboard admin → Manajemen Karyawan
   
2. **Database schema update diperlukan**
   - Jalankan ulang `database/query.sql` atau migrate manual:
   ```sql
   ALTER TABLE karyawan ADD COLUMN employment_status ENUM('active','resigned','terminated') DEFAULT 'active';
   ALTER TABLE users ADD COLUMN status ENUM('active','disabled','locked') DEFAULT 'active';
   ALTER TABLE users ADD COLUMN must_change_password TINYINT(1) DEFAULT 0;
   ALTER TABLE users ADD COLUMN password_last_changed DATETIME NULL;
   ALTER TABLE users MODIFY role ENUM('super_admin','admin','karyawan');
   ```

3. **Dashboard route berubah**
   - Old: `/dashboard` (generic)
   - New: `/admin/dashboard` atau `/karyawan/dashboard` (role-specific)
   - `/dashboard` masih berfungsi tapi redirect ke role-specific dashboard
