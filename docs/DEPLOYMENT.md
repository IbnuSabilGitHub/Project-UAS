# Panduan Deployment HRIS

Dokumentasi ini menjelaskan cara menjalankan aplikasi HRIS di berbagai environment pengembangan

---

---

## 1. XAMPP/WAMP (Subfolder htdocs)

### Struktur Folder:
```
C:/xampp/htdocs/
└── HRIS/                    ← Project folder
    ├── public/
    │   ├── assets/
    │   └── index.php
    ├── app/
    ├── .htaccess
    └── index.php
```

### URL Akses:
```
http://localhost/HRIS/
```

### Instalasi:
1. Clone/copy project ke `C:/xampp/htdocs/HRIS/`
2. Import database dari `database/query.sql`
3. Copy `.env.example` ke `.env` dan sesuaikan:
   ```env
   DB_HOST=localhost
   DB_NAME=hris_db
   DB_USER=root
   DB_PASS=
   ```
4. Akses: `http://localhost/HRIS/`

### Path yang Dihasilkan:
- Base URL: `http://localhost/HRIS`
- Assets: `http://localhost/HRIS/public/assets/css/output.css`
- Routes: `http://localhost/HRIS/admin/dashboard`

✅ **Status**: Fully Supported (Default Configuration)

---

## 2. PHP Built-in Server

### 🎯 **Recommended untuk Development**

### Cara Menjalankan:

#### Option A: Document Root di folder `public/`
```bash
# Dari root project
cd C:/xampp/htdocs/HRIS
php -S localhost:8000 -d extension=mysqli -t public
```

#### Option B: Document Root di root project
```bash
# Dari root project
cd C:/xampp/htdocs/HRIS
php -S localhost:8000
```

### URL Akses:
```
http://localhost:8000
```

### Path yang Dihasilkan (Option A - Document Root di public/):
- Base URL: `http://localhost:8000`
- Assets: `http://localhost:8000/assets/css/output.css`
- Routes: `http://localhost:8000/admin/dashboard`

### Path yang Dihasilkan (Option B - Document Root di root):
- Base URL: `http://localhost:8000`
- Assets: `http://localhost:8000/public/assets/css/output.css`
- Routes: `http://localhost:8000/admin/dashboard`

✅ **Status**: Fully Supported (Auto-detected oleh `is_public_document_root()`)

### Keuntungan:
- ✅ Tidak perlu konfigurasi Apache/Nginx
- ✅ Cepat untuk testing
- ✅ Port bisa diganti sesuai kebutuhan

### Kekurangan:
- ⚠️ Tidak untuk production
- ⚠️ Single-threaded (lambat untuk concurrent requests)

---

## 3. Apache Virtual Host

### 🎯 **Recommended untuk Production**

### Konfigurasi Apache:

#### File: `C:/xampp/apache/conf/extra/httpd-vhosts.conf`

```apache
<VirtualHost *:80>
    ServerName hris.local
    ServerAlias www.hris.local
    
    # Document root HARUS di folder public/
    DocumentRoot "C:/xampp/htdocs/HRIS/public"
    
    <Directory "C:/xampp/htdocs/HRIS/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        
        # Enable .htaccess untuk URL rewriting
        <IfModule mod_rewrite.c>
            RewriteEngine On
        </IfModule>
    </Directory>
    
    # Log files
    ErrorLog "logs/hris-error.log"
    CustomLog "logs/hris-access.log" common
</VirtualHost>
```

### Edit Hosts File:

#### File: `C:/Windows/System32/drivers/etc/hosts`
```
127.0.0.1    hris.local
127.0.0.1    www.hris.local
```

### Restart Apache:
```bash
# XAMPP Control Panel → Stop Apache → Start Apache
```

### URL Akses:
```
http://hris.local
```

### Path yang Dihasilkan:
- Base URL: `http://hris.local`
- Assets: `http://hris.local/assets/css/output.css`
- Routes: `http://hris.local/admin/dashboard`

✅ **Status**: Fully Supported

### Keuntungan:
- ✅ Mirip production environment
- ✅ Performance lebih baik
- ✅ Support SSL (HTTPS)
- ✅ Custom domain (.local)


## 🔧 Troubleshooting

### 1. Assets Tidak Terbaca (CSS/JS 404)

#### Gejala:
- Halaman muncul tanpa styling
- Console browser menunjukkan error 404 untuk CSS/JS

#### Solusi:

**A. Cek Document Root:**
```bash
# Pastikan document root benar
# Jika menggunakan Virtual Host, document root HARUS di public/
DocumentRoot "C:/xampp/htdocs/HRIS/public"
```

**B. Cek Fungsi Helper:**
```php
// Test di browser
echo asset('css/output.css');

// Output yang benar:
// XAMPP htdocs: http://localhost/HRIS/public/assets/css/output.css
// Virtual Host: http://hris.local/assets/css/output.css
// PHP Server: http://localhost:8000/assets/css/output.css
```

**C. Cek File Exists:**
```bash
# Pastikan file CSS ada
ls -la public/assets/css/output.css

# Atau di Windows
dir public\assets\css\output.css
```

**D. Compile Tailwind:**
```bash
npm install
npm run build
```

### 2. URL Routing Salah (404 untuk Routes)

#### Gejala:
- Homepage berfungsi
- Halaman lain (misal: `/admin/dashboard`) error 404

#### Solusi:

**A. Enable mod_rewrite (Apache):**
```bash
# XAMPP: Sudah enabled by default
# Linux:
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**B. Pastikan .htaccess Berfungsi:**

File: `public/.htaccess`
```apache
RewriteEngine On

# Redirect semua request ke index.php kecuali file yang ada
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

**C. AllowOverride All:**

Di `httpd-vhosts.conf`:
```apache
<Directory "C:/xampp/htdocs/HRIS/public">
    AllowOverride All  # ← Pastikan ini All, bukan None
</Directory>
```

### 3. Database Connection Error

#### Gejala:
```
SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'
```

#### Solusi:

**A. Cek `.env` file:**
```env
DB_HOST=localhost
DB_NAME=hris_db
DB_USER=root
DB_PASS=         # Kosongkan jika tidak ada password
```

**B. Import Database:**
```bash
# Via phpMyAdmin atau CLI
mysql -u root -p hris_db < database/query.sql
```

### 4. Permission Error (Linux/Production)

#### Solusi:
```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/hris

# Set permissions
sudo find /var/www/hris -type f -exec chmod 644 {} \;
sudo find /var/www/hris -type d -exec chmod 755 {} \;

# Storage folder writable
sudo chmod -R 775 /var/www/hris/storage
```

### 5. Session/Flash Message Tidak Bekerja

#### Gejala:
- Login berhasil tapi tidak redirect
- Flash message tidak muncul

#### Solusi:

**A. Pastikan session_start() dipanggil:**
```php
// Di public/index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```

**B. Cek session path writable:**
```bash
# Linux
ls -la /var/lib/php/sessions

# Windows
# Session disimpan di temp folder, sudah auto writable
```

---

## 📊 Perbandingan Environment

| Feature | XAMPP htdocs | PHP Built-in | Virtual Host |
|---------|-------------|--------------|--------------|
| Setup Complexity | ⭐ Easy | ⭐ Very Easy | ⭐⭐ Medium |
| Performance | ⭐⭐⭐ Good | ⭐⭐ Fair | ⭐⭐⭐⭐ Great |
| Production Ready | ⚠️ No | ❌ Never | ✅ Yes |
| SSL Support | ✅ Yes | ❌ No | ✅ Yes |
| Custom Domain | ⚠️ Limited | ❌ No | ✅ Yes |
| Recommended For | Development | Quick Testing | Staging/Prod |

---


**Last Updated**: December 10, 2025
