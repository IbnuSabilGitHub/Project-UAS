# Changelog

All notable changes to this project will be documented in this file.

## [Security: File Storage Migration] - 2024-11-25

### 🔒 Security Fixes

#### **Critical: Unauthorized File Access Prevention**
- ✅ **SECURITY FIX**: Moved uploaded files from `public/uploads/` to `storage/` directory
- ✅ Implemented authentication-based file viewing system
- ✅ Added role-based access control for leave attachments
- ✅ Prevented unauthorized direct file access via URL

**Security Vulnerability:**
- Files stored in `public/uploads/leave_attachments/` were accessible to anyone with direct URL
- No authentication required to view sensitive leave documents
- Potential data privacy violation for employee leave information

**Solution Implemented:**

1. **New Storage Directory:**
   - Created `storage/leave_attachments/` (outside web root)
   - Added `.gitkeep` for version control tracking
   - Files no longer directly accessible via browser

2. **Secure File Viewing Controller** (`app/Controllers/FileController.php`):
   - **Class**: `FileController` (renamed from DownloadController for clarity)
   - **Method**: `viewLeaveAttachment($leaveId)` - serves files for preview/viewing in browser
   - `ensureAuthenticated()`: Validates user session before file access
   - `canAccessFile()`: Role-based permission checking
     - Admin: Can access all leave attachments
     - Karyawan: Can only access their own attachments
   - HTTP Status Codes:
     - 401: Unauthenticated access
     - 403: Permission denied
     - 404: File not found
     - 400: Invalid request
   - **Note**: Files are displayed inline in browser (not forced download) - hence "file view" not "download"

3. **Updated File Paths:**
   - `app/Controllers/CutiController.php`:
     - `uploadDocument()`: Changed to `storage/leave_attachments/`
     - `deleteDocument()`: Updated path reference
   - `app/Models/LeaveRequest.php`:
     - Constructor `uploadDir`: Set to `storage/leave_attachments/`

4. **Updated File View Links:**
   - `app/Views/cuti/index.php`: Changed to `/file/leave/{id}` endpoint
   - `app/Views/leave/index.php`: Changed to `/file/leave/{id}` endpoint (table & modal)
   - All links now route through authenticated controller
   - Button text: "Lihat File" (View File) - accurately describes the action

5. **Router Configuration** (`app/Core/Router.php`):
   - Added route handler for `/file/leave/{id}` pattern
   - Extracts leave ID from URL and dispatches to FileController


---

## [Feature: Rejection Reason Modal] - 2024-11-25

### ✨ Added

#### **Modal Input Rejection Reason**
- ✅ Menambahkan modal dialog saat admin klik tombol "Reject" pada pengajuan cuti
- ✅ Admin wajib mengisi alasan penolakan sebelum menolak pengajuan cuti
- ✅ Modal menampilkan informasi karyawan dan periode cuti yang akan ditolak
- ✅ Textarea untuk input alasan penolakan (required field)
- ✅ Info tooltip bahwa alasan akan dikirimkan ke karyawan

**Changes in `app/Views/cuti/index.php`:**
- Mengubah button "Reject" dari direct submit menjadi trigger modal
- Menambahkan modal rejection untuk setiap row data pengajuan cuti
- Modal menggunakan komponen Flowbite dengan styling konsisten
- Form di dalam modal mengirim `rejection_reason` ke controller

**Modal Features:**
- Header dengan icon warning dan judul "Tolak Pengajuan Cuti"
- Info panel menampilkan nama karyawan dan periode cuti
- Textarea wajib diisi untuk alasan penolakan
- Helper text menjelaskan alasan akan dikirim ke karyawan
- Button "Tolak Pengajuan" dengan warna danger

**User Experience:**
- Mencegah penolakan tanpa alasan yang jelas
- Meningkatkan transparansi komunikasi admin-karyawan
- Validasi required memastikan alasan selalu terisi
- Modal dapat ditutup dengan tombol X, atau klik di luar modal





## [Fix: Employee Leave Request Date Format] - 2024-11-25

### 🐛 Bug Fixes

#### **Backend Date Handling**
- ✅ Fixed date format conversion from Flowbite datepicker (MM/DD/YYYY) to MySQL format (YYYY-MM-DD)
- ✅ Added `convertDateFormat()` method in `LeaveController.php` to handle multiple date formats
- ✅ Fixed "0000-00-00" dates in database by properly converting datepicker input
- ✅ Added validation to prevent invalid dates from being saved

**Changes in `app/Controllers/LeaveController.php`:**

#### **Total Days Calculation**
- ✅ Fixed total_days not being sent to backend
- ✅ Added hidden input field to store calculated total_days
- ✅ JavaScript now updates both display and hidden input value

**Changes in `app/Views/leave/form.php`:**
- Added `<input type="hidden" name="total_days" id="totalDaysInput" value="0">`
- Updated `calculateDays()` function to populate hidden input
- Added form validation before submit to ensure dates are selected

#### **Display Invalid Dates**
- ✅ Fixed display of invalid dates (0000-00-00) in leave history table
- ✅ Shows "Tanggal tidak valid" message for corrupted date data

**Changes in `app/Views/leave/index.php`:**
- Added validation before displaying dates with `date()` function
- Prevents PHP errors when encountering 0000-00-00 dates


---

## [Refactor: remove CRUD Features in admin] - 2024-11-25

### 🗑️ Removed

#### **Admin Cuti Controller - Fitur CRUD Dihapus**
Menghapus fitur CRUD (Create, Read, Update, Delete) dari admin karena fungsi pengajuan cuti sudah dialihkan sepenuhnya ke karyawan melalui fitur "Ajukan Cuti".

**Removed Methods** (`app/Controllers/CutiController.php`):
- ❌ `create()` - Form tambah pengajuan cuti (sementara untuk demo admin)
- ❌ `store()` - Menyimpan pengajuan cuti baru dengan validasi & upload
- ❌ `edit()` - Form edit pengajuan cuti
- ❌ `update()` - Update data pengajuan cuti & ganti dokumen
- ❌ `uploadDocument()` - Upload & validasi file dokumen (MASIH ADA untuk konsistensi)
- ❌ `deleteDocument()` - Hapus file dari server (MASIH ADA untuk delete action)

**Alasan Penghapusan:**
- Fitur "Ajukan Cuti" untuk karyawan sudah lengkap dan terintegrasi
- Admin tidak perlu membuat pengajuan atas nama karyawan
- Mengurangi duplikasi kode dan kompleksitas
- Fokus admin hanya pada review & approval pengajuan

#### **Admin Cuti Routes - Removed**
**Deleted Routes** (`app/Core/Router.php`):
- ❌ GET `/admin/cuti/create` - Form tambah pengajuan
- ❌ POST `/admin/cuti/store` - Simpan pengajuan baru
- ❌ GET `/admin/cuti/edit` - Form edit pengajuan
- ❌ POST `/admin/cuti/update` - Update pengajuan

**Retained Routes** (Core admin functions):
- ✅ GET `/admin/cuti` - List semua pengajuan cuti
- ✅ POST `/admin/cuti/approve` - Approve pengajuan
- ✅ POST `/admin/cuti/reject` - Reject pengajuan
- ✅ POST `/admin/cuti/delete` - Hapus pengajuan (hard delete)

#### **Admin Cuti Views - Removed**
**Deleted Files**:
- ❌ `app/Views/cuti/form.php` - Form tambah/edit pengajuan cuti
  - Form dengan 185 baris kode (field karyawan, tipe cuti, tanggal, alasan, upload file)
  - Auto-calculate duration dengan JavaScript
  - Preview dokumen existing saat edit
  
**Updated Views** (`app/Views/cuti/index.php`):
- ❌ Removed: Tombol "Tambah Pengajuan Cuti" di header
- ❌ Removed: Link "Edit" di kolom aksi tabel
- ❌ Removed: Header flex layout (karena tidak ada tombol lagi)
- ✅ Simplified: Header langsung dengan `<h1>` tanpa wrapper flex
- ✅ Retained: Tombol Approve/Reject untuk pengajuan pending
- ✅ Retained: Tombol Delete untuk semua pengajuan
- ✅ Retained: Statistik cards (Total, Pending, Approved, Rejected)
- ✅ Retained: Search & filter functionality
- ✅ Retained: Data table dengan semua informasi pengajuan

### 🔄 Changed

#### **Admin Role - Responsibility Shift**
**Sebelumnya (Before)**:
- Admin bisa membuat pengajuan cuti atas nama karyawan
- Admin bisa edit detail pengajuan cuti
- Admin bisa approve/reject/delete pengajuan
- Fitur ini bersifat sementara untuk demo

**Sekarang (After)**:
- Karyawan mengajukan cuti sendiri melalui `/karyawan/leave`
- Admin hanya review & approve/reject pengajuan dari karyawan
- Admin bisa delete pengajuan jika diperlukan
- Workflow lebih sesuai dengan business process yang sebenarnya


**What Admin Can NO Longer Do**:
- ❌ Create pengajuan cuti atas nama karyawan
- ❌ Edit pengajuan cuti yang sudah dibuat
- ❌ Upload dokumen untuk karyawan

---

## [Attendance Management for Admin] - 2024-11-25

### ✨ Added

#### **Manajemen Absensi untuk Admin**
- **AttendanceController.php** - Extended dengan fitur admin
  - `adminIndex()` - Halaman manajemen absensi untuk admin dengan filter & pagination
  - `export()` - Export data absensi ke format CSV
  - `ensureAdmin()` - Middleware untuk validasi akses admin only

- **Attendance Model** (`app/Models/Attendance.php`) - Extended
  - `getAllWithFilter()` - Query data absensi dengan filter dinamis (tanggal, **search nama**, status)
  - `countAllWithFilter()` - Hitung total record untuk sistem pagination
  - `getAdminStats()` - Statistik ringkas (total absensi, tepat waktu, terlambat, half day, belum checkout)

- **Views Absensi Admin** (`app/Views/attendance/admin.php`)
  - **Dashboard Statistik** dengan 5 metrics cards:
    - Total Absensi
    - Tepat Waktu
    - Terlambat
    - Half Day
    - Belum Checkout
  
  - **Filter Section** dengan 3 filter:
    - Filter tanggal (date picker)
    - **Search nama karyawan** (text input dengan LIKE query) - Scalable untuk banyak karyawan
    - Filter status (dropdown: Hadir/Terlambat/Half Day)
    - Tombol Filter dan Export CSV
    - Reset Filter link (muncul saat filter aktif)
  
  - **Data Table** dengan kolom:
    - Tanggal, NIK, Nama Karyawan, Jabatan
    - Check-in, Check-out, Durasi (jam & menit)
    - Status (badge dengan warna sesuai kondisi)
    - Catatan karyawan
  
  - **Pagination** dengan navigasi:
    - Info halaman saat ini & total halaman
    - Tombol Previous/Next
    - Nomor halaman (max 5 tombol visible)
    - 20 record per halaman
  
  - **Export CSV** mencakup semua field dengan filter yang dipilih

#### **Routing Updates**
- `/admin/attendance` (GET) - Halaman manajemen absensi admin
- `/admin/attendance/export` (GET) - Download data absensi dalam format CSV
  
### 🔄 Changed

#### **Dashboard Admin**
- Updated menu "Absensi" dari coming soon menjadi aktif
- Link ke `/admin/attendance`
- Deskripsi updated: "Lihat, filter, dan export data absensi karyawan"

#### **Filter Karyawan - Performance Optimization**
- **Dari**: Dropdown select dengan semua karyawan (tidak efektif untuk data banyak)
- **Ke**: Text input search by name dengan LIKE query (scalable & user-friendly)
- **Alasan**: 
  - Dropdown select dengan ratusan karyawan tidak praktis dan lambat
  - Search input lebih cepat dan intuitif
  - Partial match dengan LIKE memudahkan pencarian
  - Tidak perlu load semua karyawan di view
  - Bandwidth efficient - hanya kirim string search, bukan ratusan options HTML

### 📝 Features Detail

#### Search by Name Feature
- Input type: Text dengan placeholder "Cari nama karyawan..."
- Query: MySQL LIKE dengan wildcard `%nama%`
- Case-insensitive search
- Partial match support (cukup ketik sebagian nama)
- Performance: Menggunakan prepared statements untuk keamanan
- Scalability: Efektif untuk database dengan ratusan atau ribuan karyawan

### ⚡ Performance Improvements

**Search by Name vs Dropdown Select**:
- ✅ **Search input**: O(log n) dengan database index, minimal bandwidth
- ❌ **Dropdown select**: O(n) - load semua karyawan di HTML, bandwidth besar
- ✅ **UX**: Lebih cepat untuk user menemukan karyawan spesifik
- ✅ **Scalability**: Tetap cepat meski karyawan mencapai ribuan

**Recommended Database Index** (untuk optimal performance):
```sql
ALTER TABLE karyawan ADD INDEX idx_name (name);
```
## [Integration with Employee Leave Request] - 2024-11-24

### 🔄 Changed - **Penyesuaian Kompatibilitas dengan Fitur Ajukan Cuti**

**Alasan Perubahan:**  
Fitur "Ajukan Cuti" untuk karyawan sudah dirilis di branch lain menggunakan tabel `leave_requests` dengan struktur berbeda. Untuk menghindari konflik merge dan memastikan integrasi yang mulus, fitur admin disesuaikan untuk menggunakan skema database yang sama.

#### **Database Migration**
- ❌ **Removed**: Tabel `pengajuan_cuti` (skema lama)
- ✅ **Migrated to**: Tabel `leave_requests` (skema baru)
- **New Fields Added**:
  - `leave_type` ENUM('annual','sick','emergency','unpaid') - Jenis cuti (tahunan/sakit/darurat/tanpa gaji)
  - `total_days` INT - Durasi cuti dalam hari (dihitung otomatis)
  - `attachment_file` VARCHAR(255) - Nama file lampiran (ganti `document_path`)
  - `approved_by` INT - ID user yang menyetujui (foreign key ke `users.id`)
  - `approved_at` DATETIME - Waktu persetujuan
  - `rejection_reason` TEXT - Alasan penolakan cuti

#### **Model Changes** (`app/Models/PengajuanCuti.php`)
- Updated table reference: `pengajuan_cuti` → `leave_requests`
- `create()` - Added `leave_type` and `total_days` parameters
- `updateStatus()` - Added `approved_by` and `rejection_reason` support
- `calculateDays()` - Automatically calculates `total_days` including weekends

#### **Controller Changes** (`app/Controllers/CutiController.php`)
- `store()` - Now includes `leave_type` validation and `total_days` calculation
- `approve()` - Records `approved_by` (admin user ID) and `approved_at` timestamp
- `reject()` - Records `rejection_reason` when rejecting leave requests
- **File Upload Path Changed**: 
  - Old: `public/uploads/cuti/`
  - New: `public/uploads/leave_attachments/` (consistent with employee feature)
- **File Types Expanded**: PDF + Images (JPG/PNG) - max 5MB

#### **View Changes**
- `app/Views/cuti/form.php`:
  - Added `leave_type` dropdown (Annual/Sick/Emergency/Unpaid Leave)
  - Updated file input: `accept=".pdf,.jpg,.jpeg,.png"` (was `.pdf` only)
  - Changed file reference: `document_path` → `attachment_file`
  
- `app/Views/cuti/index.php`:
  - Display `leave_type` badge with color coding
  - Show `approved_by` admin name in approved requests
  - Show `rejection_reason` in rejected requests tooltip

#### **Routes** (No changes - backward compatible)
- All existing routes `/admin/cuti/*` remain functional

---

## [add leave request management for admin] - 2024-11-24

### ✨ Added

#### **Pengajuan Cuti (Admin) - Initial Implementation**
- **CutiController.php** - Controller lengkap untuk manajemen pengajuan cuti
  - `index()` - Menampilkan daftar pengajuan cuti dengan statistik
  - `create()` - Form tambah pengajuan cuti atas nama karyawan
  - `store()` - Menyimpan pengajuan cuti baru dengan validasi & upload dokumen
  - `edit()` - Form edit pengajuan cuti
  - `update()` - Update data pengajuan cuti & ganti dokumen
  - `approve()` - Menyetujui pengajuan cuti (status: approved)
  - `reject()` - Menolak pengajuan cuti (status: rejected)
  - `delete()` - Menghapus pengajuan cuti & hapus file
  - `uploadDocument()` - Upload & validasi file (PDF/JPG/PNG, maks 5MB)
  - `deleteDocument()` - Hapus file dari server

- **PengajuanCuti Model** (`app/Models/PengajuanCuti.php`)
  - CRUD operations untuk tabel `leave_requests`
  - `allWithKaryawan()` - Join dengan data karyawan
  - `find()` - Cari pengajuan berdasarkan ID
  - `getByKaryawan()` - Ambil riwayat cuti per karyawan
  - `updateStatus()` - Update status (pending/approved/rejected) + approved_by
  - `calculateDays()` - Hitung durasi cuti otomatis
  - `getStatistics()` - Statistik pengajuan (total, pending, approved, rejected)

- **Database Schema**
  - Tabel `pengajuan_cuti` dengan foreign key ke `karyawan`
  - Field: id, karyawan_id, start_date, end_date, reason, **document_path**, status, created_at, updated_at
  - Status ENUM: pending, approved, rejected
  - **document_path** (VARCHAR 255, nullable) - Path ke file PDF dokumen pendukung

- **Upload File PDF**
  - Folder `public/uploads/cuti/` untuk menyimpan dokumen
  - Validasi: hanya PDF, maksimal 5MB
  - Auto-generate nama file unik: `cuti_{timestamp}_{uniqid}.pdf`
  - Auto-delete file saat pengajuan dihapus atau diganti

- **Views Pengajuan Cuti**
  - `app/Views/cuti/index.php` - Daftar pengajuan dengan statistik cards & **kolom dokumen dengan link download PDF**
  - `app/Views/cuti/form.php` - Form tambah/edit dengan **input file upload** & preview dokumen existing

- **Routes Pengajuan Cuti**
  - GET `/admin/cuti` - List pengajuan cuti
  - GET `/admin/cuti/create` - Form tambah
  - POST `/admin/cuti/store` - Simpan pengajuan
  - GET `/admin/cuti/edit` - Form edit
  - POST `/admin/cuti/update` - Update pengajuan
  - POST `/admin/cuti/approve` - Approve pengajuan
  - POST `/admin/cuti/reject` - Reject pengajuan
  - POST `/admin/cuti/delete` - Hapus pengajuan

#### **Dashboard Update**
- Menu "Pengajuan Cuti" di admin dashboard sudah aktif (bukan lagi "Coming Soon")
- Link langsung ke `/admin/cuti` untuk kelola pengajuan cuti

### 🔧 Enhanced
- Validasi tanggal cuti (tanggal selesai tidak boleh lebih awal dari tanggal mulai)
- Auto-calculate durasi cuti (inclusive start dan end date)
- Statistics cards untuk monitoring pengajuan cuti (total, pending, approved, rejected)
- Status badge dengan color coding (yellow: pending, green: approved, red: rejected)

### 📝 Notes
- Fitur "Ajukan Cuti" untuk role karyawan belum diimplementasikan (sedang develop tim lain)
- Admin dapat membuat pengajuan cuti atas nama karyawan
- Admin dapat approve/reject langsung dari halaman index

---

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
