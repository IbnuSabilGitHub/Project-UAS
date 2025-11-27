# 📄 Dokumentasi Frontend Views - HRIS

> **🔄 Updated:** Struktur telah direfactor menggunakan role-based organization (Opsi 3 - Hybrid)

## 📁 Struktur Views (New Structure)

```
app/Views/
├── admin/              #  Admin-only views
│   ├── dashboard.php
│   ├── attendance/
│   │   └── index.php
│   ├── employees/
│   │   ├── index.php
│   │   └── form.php
│   └── leave/
│       └── index.php
│
├── employee/           # Employee-only views
│   ├── dashboard.php
│   ├── attendance.php
│   └── leave/
│       ├── index.php
│       └── create.php
│
├── auth/              #  Public authentication
│   ├── login.php
│   └── change_password.php
│
├── layouts/           #  Templates & Components
│   ├── header.php
│   ├── footer.php
│   └── components/
│       ├── alerts.php
│       └── pagination.php
│
└── errors/            # Error pages
    ├── 404.php
    └── 403.php
```

---

##  AUTH - Autentikasi

**Lokasi:** `app/Views/auth/`

| File | Fungsi | User |
|------|--------|------|
| `login.php` | Halaman login untuk admin & karyawan | Semua |
| `change_password.php` | Halaman ganti password | Admin & Karyawan |

**Route:**
- `/login` → login.php
- `/change-password` → change_password.php

---

##  ADMIN - Halaman Khusus Admin

**Lokasi:** `app/Views/admin/`

### 1. Dashboard Admin
**File:** `admin/dashboard.php`

**Fungsi:** Dashboard utama admin dengan statistik & overview

**Route:** `/admin/dashboard`

**Controller:** `AuthController::adminDashboard()`

---

### 2. Absensi Admin
**File:** `admin/attendance/index.php`

**Fungsi:** 
- Melihat semua data absensi karyawan
- Filter berdasarkan tanggal, nama, status
- Export data ke CSV
- Statistik kehadiran

**Route:** `/admin/attendance`

**Controller:** `AttendanceController::adminIndex()`

**Fitur:**
- Filter tanggal
- Pencarian nama karyawan
- Filter status (present, late, half_day)
- Pagination
-  Export CSV

---

### 3. Manajemen Karyawan
**Lokasi:** `admin/employees/`

| File | Fungsi | Route | Controller |
|------|--------|-------|------------|
| `index.php` | Daftar semua karyawan | `/admin/karyawan` | `KaryawanController::index()` |
| `form.php` | Form tambah/edit karyawan | `/admin/karyawan/create`<br>`/admin/karyawan/edit/{id}` | `KaryawanController::create()`<br>`KaryawanController::edit()` |

**Fitur:**
-  CRUD Karyawan
-  Upload foto profil
-  Manage data karyawan
-  Set jatah cuti tahunan

---

### 4. Pengajuan Cuti/Izin Admin
**Lokasi:** `admin/leave/`

| File | Fungsi | Route | Controller |
|------|--------|-------|------------|
| `index.php` | Daftar & kelola pengajuan cuti | `/admin/cuti` | `CutiController::index()` |

**Fitur:**
-  Lihat semua pengajuan cuti
-  Approve/Reject pengajuan
-  Download attachment
-  Filter by status, tanggal, nama

---

## 👤 KARYAWAN - Halaman Khusus Karyawan

**Lokasi:** `app/Views/employee/`

### 1. Dashboard Karyawan
**File:** `employee/dashboard.php`

**Fungsi:** Dashboard karyawan dengan ringkasan data pribadi

**Route:** `/karyawan/dashboard`

**Controller:** `AuthController::employeeDashboard()`

---

### 2. Absensi Karyawan
**File:** `employee/attendance.php`

**Fungsi:**
- Check-in harian
- Check-out harian
- Lihat riwayat absensi pribadi
- Statistik kehadiran bulan ini

**Route:** `/karyawan/attendance`

**Controller:** `AttendanceController::index()`

**Fitur:**
-  Tombol Check-in
-  Tombol Check-out
-  Status hari ini (present/late/half_day)
-  Riwayat absensi 30 hari terakhir
-  Statistik bulanan (total, on time, late, half day)

**Logika Status:**
-  **PRESENT**: Check-in ≤ 09:00
-  **HALF_DAY**: Check-in 09:01 - 09:15 (toleransi 15 menit)
-  **LATE**: Check-in > 09:15

---

### 3. Pengajuan Cuti/Izin Karyawan
**Lokasi:** `employee/leave/`

| File | Fungsi | Route | Controller |
|------|--------|-------|------------|
| `index.php` | Daftar pengajuan cuti pribadi | `/karyawan/leave` | `LeaveController::index()` |
| `create.php` | Form ajukan cuti baru | `/karyawan/leave/create` | `LeaveController::create()` |

**Fitur:**
- Ajukan cuti/izin
-  Upload dokumen pendukung
-  Lihat status pengajuan (pending/approved/rejected)
-  Lihat riwayat pengajuan
-  Validasi jatah cuti tahunan
-  Display sisa quota

---

##  LAYOUTS - Template & Components

**Lokasi:** `app/Views/layouts/`

### Template Files

| File | Fungsi |
|------|--------|
| `header.php` | Header global (navbar, meta tags) |
| `footer.php` | Footer global (scripts, copyright) |

**Cara Pakai:**
```php
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- Konten halaman -->

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
```

### Reusable Components

**Lokasi:** `layouts/components/`

#### 1. Alerts Component
**File:** `components/alerts.php`

**Fungsi:** Tampilkan alert messages (success, error, warning, info)

**Cara Pakai:**
```php
<?php 
$success = "Data berhasil disimpan!";
$error = "Terjadi kesalahan!";
require_once __DIR__ . '/components/alerts.php'; 
?>
```

**Supported Variables:**
- `$success` - Green success alert
- `$error` - Red error alert  
- `$warning` - Yellow warning alert
- `$info` - Blue info alert

---

#### 2. Pagination Component
**File:** `components/pagination.php`

**Fungsi:** Tampilkan pagination controls

**Cara Pakai:**
```php
<?php 
$pagination = [
    'current' => 2,
    'total' => 10,
    'totalRecords' => 150,
    'baseUrl' => '/admin/attendance'
];
require_once __DIR__ . '/components/pagination.php'; 
?>
```

**Required Fields:**
- `current` - Current page number
- `total` - Total pages
- `baseUrl` - Base URL for pagination links

**Optional Fields:**
- `totalRecords` - Total number of records
- `prevUrl` - Custom previous page URL
- `nextUrl` - Custom next page URL
- `pageUrl` - Array of custom page URLs

---

##  ERROR PAGES

**Lokasi:** `app/Views/errors/`

| File | Status Code | Fungsi |
|------|-------------|--------|
| `404.php` | 404 Not Found | Page not found error |
| `403.php` | 403 Forbidden | Access denied error |

**Features:**
- User-friendly design
- Navigation options (Back, Home)
- Consistent styling with app theme

---

##  Ringkasan Per Role

###  ADMIN
```
Dashboard     → admin/dashboard.php
Absensi       → admin/attendance/index.php
Karyawan      → admin/employees/index.php
              → admin/employees/form.php
Pengajuan     → admin/leave/index.php
```

###  KARYAWAN
```
Dashboard     → employee/dashboard.php
Absensi       → employee/attendance.php
Pengajuan     → employee/leave/index.php
              → employee/leave/create.php
```

###  PUBLIK
```
Login         → auth/login.php
Ganti Pass    → auth/change_password.php
```

---

##  Quick Reference Table

| Fitur | File Admin | File Karyawan | Controller |
|-------|-----------|---------------|------------|
| **Dashboard** | `admin/dashboard.php` | `employee/dashboard.php` | `AuthController` |
| **Absensi** | `admin/attendance/index.php` | `employee/attendance.php` | `AttendanceController` |
| **Pengajuan Cuti** | `admin/leave/index.php` | `employee/leave/index.php`<br>`employee/leave/create.php` | `CutiController`<br>`LeaveController` |
| **Manajemen Karyawan** | `admin/employees/index.php`<br>`admin/employees/form.php` | - | `KaryawanController` |

---

## Migration Guide (Old → New)

| Old Path | New Path | Role |
|----------|----------|------|
| `dashboard/admin.php` | `admin/dashboard.php` | Admin |
| `dashboard/employee.php` | `employee/dashboard.php` | Employee |
| `attendance/admin.php` | `admin/attendance/index.php` | Admin |
| `attendance/index.php` | `employee/attendance.php` | Employee |
| `karyawan/index.php` | `admin/employees/index.php` | Admin |
| `karyawan/form.php` | `admin/employees/form.php` | Admin |
| `cuti/index.php` | `admin/leave/index.php` | Admin |
| `leave/index.php` | `employee/leave/index.php` | Employee |
| `leave/form.php` | `employee/leave/create.php` | Employee |

---

## Naming Conventions

| Type | Naming | Example |
|------|--------|---------|
| List/Index | `index.php` | `admin/employees/index.php` |
| Create Form | `create.php` | `employee/leave/create.php` |
| Edit Form | `edit.php` atau `form.php` | `admin/employees/form.php` |
| Detail View | `detail.php` atau `show.php` | `admin/leave/detail.php` |
| Single Page Feature | `{feature}.php` | `employee/attendance.php` |
| Dashboard | `dashboard.php` | `admin/dashboard.php` |

---

##  Best Practices

1. **Use Reusable Components**
   ```php
   // Instead of duplicating alert code
   require_once __DIR__ . '/layouts/components/alerts.php';
   ```

2. **Consistent Path Structure**
   ```php
   // Always use relative paths from file location
   require_once __DIR__ . '/../layouts/header.php';
   ```

3. **Follow Naming Conventions**
   - Index pages: `index.php`
   - Create forms: `create.php`
   - Single feature: `{feature}.php`

4. **Role Separation**
   - Admin views → `admin/` folder
   - Employee views → `employee/` folder
   - Shared views → `layouts/` or `auth/`

---

**Last Updated:** 2024-11-27  
**Version:** 2.0 (Post-Refactoring)
