# Dokumentasi Frontend - Dashboard Karyawan

## Deskripsi
Dokumen ini berisi panduan untuk tim frontend dalam mengimplementasikan UI Dashboard Karyawan berdasarkan data JSON yang tersedia dari backend.

## Endpoint
**URL**: `/karyawan/dashboard`  
**Method**: GET  
**Auth Required**: Ya (Karyawan)

## Struktur Data JSON

Dashboard karyawan menyediakan 2 kategori statistik personal dalam format JSON:

### 1. Statistik Cuti (`statsLeave`)

```json
{
  "total": "12",
  "pending": "2",
  "approved": "8",
  "rejected": "2",
  "total_days_approved": "16",
  "by_type": {
    "annual": {
      "count": 5,
      "total_days": 10
    },
    "sick": {
      "count": 2,
      "total_days": 4
    },
    "unpaid": {
      "count": 1,
      "total_days": 2
    }
  },
  "tahun_ini": {
    "total": "8",
    "total_days": "14"
  }
}
```

**Penjelasan Field:**
- `total`: Total seluruh pengajuan cuti karyawan
- `pending`: Jumlah cuti yang sedang menunggu persetujuan
- `approved`: Jumlah cuti yang disetujui
- `rejected`: Jumlah cuti yang ditolak
- `total_days_approved`: Total hari cuti yang telah disetujui
- `by_type`: Breakdown cuti berdasarkan tipe (annual, sick, unpaid, dll)
  - `count`: Jumlah pengajuan untuk tipe tersebut
  - `total_days`: Total hari untuk tipe tersebut
- `tahun_ini`: Statistik cuti tahun berjalan
  - `total`: Jumlah pengajuan tahun ini
  - `total_days`: Total hari cuti tahun ini

### 2. Statistik Absensi (`statsAttendance`)

```json
{
  "bulan_ini": {
    "total_attendance": "20",
    "on_time": "16",
    "late": "3",
    "half_day": "1",
    "not_checkout": "2"
  },
  "keseluruhan": {
    "total_attendance": "180",
    "on_time": "150",
    "late": "25",
    "half_day": "5"
  },
  "tujuh_hari_terakhir": [
    {
      "tanggal": "2025-11-28",
      "status": "present",
      "check_in": "2025-11-28 08:30:00",
      "check_out": "2025-11-28 17:00:00"
    },
    {
      "tanggal": "2025-11-27",
      "status": "late",
      "check_in": "2025-11-27 09:15:00",
      "check_out": "2025-11-27 17:30:00"
    },
    {
      "tanggal": "2025-11-26",
      "status": "present",
      "check_in": "2025-11-26 08:00:00",
      "check_out": "2025-11-26 17:00:00"
    }
  ]
}
```

**Penjelasan Field:**

**`bulan_ini`** - Statistik absensi bulan berjalan:
- `total_attendance`: Total record absensi bulan ini
- `on_time`: Jumlah absensi tepat waktu
- `late`: Jumlah absensi terlambat
- `half_day`: Jumlah absensi setengah hari
- `not_checkout`: Jumlah absensi yang belum checkout

**`keseluruhan`** - Statistik absensi keseluruhan:
- `total_attendance`: Total record absensi dari awal
- `on_time`: Total absensi tepat waktu
- `late`: Total absensi terlambat
- `half_day`: Total absensi setengah hari

**`tujuh_hari_terakhir`** - Array detail absensi 7 hari terakhir:
- `tanggal`: Tanggal absensi
- `status`: Status absensi (present/late/half_day)
- `check_in`: Waktu check-in
- `check_out`: Waktu check-out (bisa null jika belum checkout)

## Implementasi UI - Saran

### 1. Layout Dashboard
```
┌─────────────────────────────────────────────────────┐
│  Dashboard Karyawan                                 │
├─────────────────────────────────────────────────────┤
│                                                     │
│  Statistik Cuti                                     │
│  ┌───────────┐ ┌───────────┐ ┌───────────┐        │
│  │ Pending   │ │ Disetujui │ │ Hari Cuti │        │
│  │     2     │ │     8     │ │    16     │        │
│  └───────────┘ └───────────┘ └───────────┘        │
│                                                     │
│  Cuti Berdasarkan Tipe                             │
│  ┌─────────────────────────────────────────┐       │
│  │ Annual Leave: 5 (10 hari)               │       │
│  │ Sick Leave: 2 (4 hari)                  │       │
│  │ Unpaid Leave: 1 (2 hari)                │       │
│  └─────────────────────────────────────────┘       │
│                                                     │
│  Statistik Absensi Bulan Ini                       │
│  ┌───────────┐ ┌───────────┐ ┌───────────┐        │
│  │ Total     │ │ Tepat     │ │ Terlambat │        │
│  │ Hadir     │ │ Waktu     │ │           │        │
│  │    20     │ │    16     │ │     3     │        │
│  └───────────┘ └───────────┘ └───────────┘        │
│                                                     │
│  Riwayat Absensi 7 Hari Terakhir                   │
│  ┌─────────────────────────────────────────┐       │
│  │ 28 Nov | ✓ On Time | 08:30 - 17:00     │       │
│  │ 27 Nov | ⚠ Late    | 09:15 - 17:30     │       │
│  │ 26 Nov | ✓ On Time | 08:00 - 17:00     │       │
│  └─────────────────────────────────────────┘       │
└─────────────────────────────────────────────────────┘
```

## Example usage

```php
  <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading">
        <!-- Data dari backend -->
         <?php echo $statsAttendance['keseluruhan']['total_attendance'] ?? 0; ?>
    </p>
    <p class="text-body">Pending</p>
  </div
```


## Support

Jika ada pertanyaan teknis atau data tidak sesuai ekspektasi, chat di grup
