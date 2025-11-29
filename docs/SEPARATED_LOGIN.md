# Dokumentasi - Pemisahan Login Admin dan Karyawan

## Deskripsi
Implementasi pemisahan halaman login untuk Admin dan Karyawan dengan tujuan meningkatkan UX (User Experience) dan keamanan aplikasi.

## Flow Aplikasi Baru

### 1. Landing Page (`/`)
User yang belum login akan diarahkan ke halaman index yang menampilkan 2 pilihan:
- **Login sebagai Admin** - Mengarah ke `/admin/login`
- **Login sebagai Karyawan** - Mengarah ke `/karyawan/login`

Jika user sudah memiliki session aktif, langsung redirect ke dashboard sesuai role.

### 2. Login Admin (`/admin/login`)
- Form login khusus untuk administrator
- Validasi role: hanya `admin` dan `super_admin` yang bisa login
- Jika login dengan akun karyawan, akan muncul error: "Anda tidak memiliki akses admin"
- Setelah login sukses, redirect ke `/admin/dashboard`

### 3. Login Karyawan (`/karyawan/login`)
- Form login khusus untuk karyawan
- Validasi role: hanya `karyawan` yang bisa login
- Jika login dengan akun admin, akan muncul error: "Gunakan halaman login admin untuk akun administrator"
- Setelah login sukses, redirect ke `/karyawan/dashboard`

## Struktur File Baru

```
app/
  Views/
    index.php                    # Landing page dengan pilihan login
    auth/
      login.php                  # Login lama (backward compatibility)
      login-admin.php            # Login khusus admin (NEW)
      login-karyawan.php         # Login khusus karyawan (NEW)
  Controllers/
    AuthController.php           # Updated dengan method baru
  Core/
    Router.php                   # Updated dengan route baru
```

## Routes

| Route | Method | Handler | Deskripsi |
|-------|--------|---------|-----------|
| `/` | GET | `AuthController::index()` | Landing page pilihan login |
| `/admin/login` | GET | `AuthController::adminLoginPage()` | Halaman login admin |
| `/admin/login` | POST | `AuthController::adminLogin()` | Proses login admin |
| `/karyawan/login` | GET | `AuthController::karyawanLoginPage()` | Halaman login karyawan |
| `/karyawan/login` | POST | `AuthController::karyawanLogin()` | Proses login karyawan |
| `/login` | GET | `AuthController::loginPage()` | Login lama (masih bisa digunakan) |
| `/login` | POST | `AuthController::login()` | Proses login lama |

## Method Baru di AuthController

### 1. `index()`
- Menampilkan landing page dengan 2 pilihan login
- Check session: jika sudah login, redirect ke dashboard sesuai role

### 2. `adminLoginPage()`
- Menampilkan form login admin
- Check session: jika sudah login, redirect ke dashboard

### 3. `karyawanLoginPage()`
- Menampilkan form login karyawan
- Check session: jika sudah login, redirect ke dashboard

### 4. `adminLogin()`
- Proses autentikasi untuk admin
- Validasi role: `admin` atau `super_admin`
- Reject jika role = `karyawan`

### 5. `karyawanLogin()`
- Proses autentikasi untuk karyawan
- Validasi role: `karyawan`
- Reject jika role = `admin` atau `super_admin`

## Validasi Role

### Login Admin
```php
if (!in_array($user['role'], ['admin', 'super_admin'])) {
    $_SESSION['error'] = 'Anda tidak memiliki akses admin';
    redirect('/admin/login');
}
```

### Login Karyawan
```php
if ($user['role'] !== 'karyawan') {
    $_SESSION['error'] = 'Gunakan halaman login admin untuk akun administrator';
    redirect('/karyawan/login');
}
```

## Security Improvements

1. **Role Segregation**: Admin dan karyawan tidak bisa login di halaman yang salah
2. **Clear Error Messages**: Pesan error yang spesifik untuk setiap kasus
3. **Session Check**: Semua halaman login check session terlebih dahulu
4. **Redirect Protection**: User yang sudah login tidak bisa akses halaman login

## UI/UX Improvements

### Landing Page
- Clean design dengan 2 card besar
- Hover effects untuk better interactivity
- Icon yang jelas membedakan admin dan karyawan
- Responsive design (mobile-friendly)

### Login Admin
- Icon admin (gear/settings)
- Brand color (blue) untuk konsistensi
- Text: "Login Admin" dan "Masuk dengan akun administrator"
- Link kembali ke halaman utama

### Login Karyawan
- Icon user/person
- Green color untuk membedakan dari admin
- Text: "Login Karyawan" dan "Masuk dengan akun karyawan"
- Link kembali ke halaman utama

## Backward Compatibility

Route `/login` lama masih berfungsi untuk:
- User yang sudah bookmark URL lama
- Testing atau development
- Transisi bertahap

Method `loginPage()` dan `login()` tetap ada dan berfungsi normal.

## Testing Checklist

- [ ] Akses `/` tanpa login → Tampil landing page
- [ ] Akses `/` dengan session admin → Redirect ke `/admin/dashboard`
- [ ] Akses `/` dengan session karyawan → Redirect ke `/karyawan/dashboard`
- [ ] Login admin dengan akun admin → Sukses masuk dashboard admin
- [ ] Login admin dengan akun karyawan → Error "tidak memiliki akses admin"
- [ ] Login karyawan dengan akun karyawan → Sukses masuk dashboard karyawan
- [ ] Login karyawan dengan akun admin → Error "gunakan halaman login admin"
- [ ] Akses `/admin/login` dengan session → Redirect ke dashboard
- [ ] Akses `/karyawan/login` dengan session → Redirect ke dashboard
- [ ] Link "Kembali ke halaman utama" berfungsi


## Contoh Flow

### Flow Admin Login:
```
User → / → Klik "Login sebagai Admin" 
     → /admin/login → Input username & password 
     → POST /admin/login → Validasi role=admin 
     → Success → /admin/dashboard
```

### Flow Karyawan Login:
```
User → / → Klik "Login sebagai Karyawan" 
     → /karyawan/login → Input username & password 
     → POST /karyawan/login → Validasi role=karyawan 
     → Success → /karyawan/dashboard
```

## Support

Jika ada pertanyaan teknis atau data tidak sesuai ekspektasi, chat di grup
