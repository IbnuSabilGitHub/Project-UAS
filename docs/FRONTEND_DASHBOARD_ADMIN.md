# Dokumentasi Frontend - Dashboard Admin

## Deskripsi

Dokumen ini berisi panduan untuk tim frontend dalam mengimplementasikan UI Dashboard Admin berdasarkan data JSON yang tersedia dari backend.

## Endpoint

**URL**: `/admin/dashboard`  
**Method**: GET  
**Auth Required**: Ya (Admin/Super Admin)

## Struktur Data JSON

Dashboard admin menyediakan 3 kategori statistik dalam format JSON:

### 1. Statistik Karyawan (`statsKaryawan`)

```json
{
  "total_karyawan": 25,
  "by_status": {
    "active": 20,
    "inactive": 3,
    "resigned": 2
  },
  "by_position": {
    "Software Engineer": 8,
    "Project Manager": 3,
    "UI/UX Designer": 5,
    "QA Engineer": 4,
    "DevOps Engineer": 3,
    "HR Manager": 2
  },
  "total_akun_aktif": 22,
  "total_tanpa_akun": 3,
  "bergabung_bulan_ini": 2
}
```

**Penjelasan Field:**

- `total_karyawan`: Total seluruh karyawan di sistem
- `by_status`: Breakdown karyawan berdasarkan status (active, inactive, resigned)
- `by_position`: Breakdown karyawan berdasarkan posisi/jabatan (top 10)
- `total_akun_aktif`: Jumlah karyawan yang memiliki akun aktif
- `total_tanpa_akun`: Jumlah karyawan yang belum memiliki akun
- `bergabung_bulan_ini`: Jumlah karyawan baru yang bergabung bulan ini

### 2. Statistik Cuti (`statLeave`)

```json
{
  "total": "45",
  "pending": "8",
  "approved": "32",
  "rejected": "5"
}
```

**Penjelasan Field:**

- `total`: Total seluruh pengajuan cuti
- `pending`: Jumlah cuti yang menunggu persetujuan
- `approved`: Jumlah cuti yang disetujui
- `rejected`: Jumlah cuti yang ditolak

### 3. Statistik Absensi (`statsAttendance`)

```json
{
  "total_employees": "18",
  "total_attendance": "420",
  "on_time": "350",
  "late": "60",
  "half_day": "10",
  "not_checkout": "15"
}
```

**Penjelasan Field:**

- `total_employees`: Jumlah karyawan yang tercatat hadir (unique)
- `total_attendance`: Total record absensi
- `on_time`: Jumlah absensi tepat waktu
- `late`: Jumlah absensi terlambat
- `half_day`: Jumlah absensi setengah hari
- `not_checkout`: Jumlah absensi yang belum checkout

**Catatan**: Data absensi default menampilkan statistik bulan berjalan.

## Implementasi UI - Saran

### 1. Layout Dashboard

```
┌─────────────────────────────────────────────────────┐
│  Dashboard Admin                                    │
├─────────────────────────────────────────────────────┤
│                                                     │
│  ┌───────────┐ ┌───────────┐ ┌───────────┐        │
│  │ Total     │ │ Akun      │ │ Bergabung │        │
│  │ Karyawan  │ │ Aktif     │ │ Bulan Ini │        │
│  │   25      │ │   22      │ │     2     │        │
│  └───────────┘ └───────────┘ └───────────┘        │
│                                                     │
│  Karyawan Berdasarkan Status                       │
│  ┌─────────────────────────────────────────┐       │
│  │ [Pie Chart atau Bar Chart]              │       │
│  │ Active: 20 | Inactive: 3 | Resigned: 2  │       │
│  └─────────────────────────────────────────┘       │
│                                                     │
│  Karyawan Berdasarkan Posisi                       │
│  ┌─────────────────────────────────────────┐       │
│  │ [Horizontal Bar Chart]                   │       │
│  │ Software Engineer: 8                     │       │
│  │ UI/UX Designer: 5                        │       │
│  │ QA Engineer: 4                           │       │
│  └─────────────────────────────────────────┘       │
│                                                     │
│  ┌─────────────────┐ ┌─────────────────────┐       │
│  │ Statistik Cuti  │ │ Statistik Absensi   │       │
│  │ - Pending: 8    │ │ - Tepat Waktu: 350  │       │
│  │ - Approved: 32  │ │ - Terlambat: 60     │       │
│  │ - Rejected: 5   │ │ - Half Day: 10      │       │
│  └─────────────────┘ └─────────────────────┘       │
└─────────────────────────────────────────────────────┘
```

## Example usage

```php
  <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading">
        <!-- Data dari backend -->
        <?= $statsLeave['pending'] ?? 0 ?>
    </p>
    <p class="text-body">Pending</p>
  </div
```


## Support

Jika ada pertanyaan teknis atau data tidak sesuai ekspektasi, chat di grup