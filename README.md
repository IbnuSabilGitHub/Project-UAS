# HRIS

Aplikasi HRIS sederhana dengan fitur inti: Login berbasis role, Dashboard, Manajemen Karyawan, Absensi Harian (Check-in/Check-out), dan Pengajuan/Persetujuan Cuti.

<div align="center">
  <img src="https://img.shields.io/badge/HTML5-v5-orange?logo=html5" />
  <img src="https://img.shields.io/badge/PHP-8.2-blue?logo=php" />
  <img src="https://img.shields.io/badge/JavaScript-ES6-yellow?logo=javascript" />
  <img src="https://img.shields.io/badge/MySQL-8.0-blue?logo=mysql" />
  <img src="https://img.shields.io/badge/TailwindCSS-4.1-38bdf8?logo=tailwindcss" />
</div>


## Fitur Utama
- Login/Logout + Session (role: `admin`, `karyawan`)
- Dashboard sesuai role
- CRUD Karyawan (admin)
- Absensi karyawan (check-in/check-out, 1x per hari)
- Pengajuan cuti (karyawan) + Approve/Reject (admin)

## Arsitektur
- Pola: PHP REST API + View HTML sederhana (Tailwind via CDN) + MySQL.
- Detail arsitektur, ERD, flow & activity diagram: lihat `ARCHITECTURE.md`.


## Instalasi
### Tailwind
```bash
  npx @tailwindcss/cli -i ./public/assets/css/input.css -o ./public/assets/css/output.css --watch
```


