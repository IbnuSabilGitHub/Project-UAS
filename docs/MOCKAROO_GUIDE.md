# Panduan Generate Mock Data dengan Mockaroo

Panduan ini menjelaskan cara membuat file `MOCK_DATA.json` menggunakan [Mockaroo](https://www.mockaroo.com/) yang kompatibel dengan script `scripts/inject_karyawan.php`.

---

## Field yang Dibutuhkan

Script `inject_karyawan.php` meaca 4 field dari JSON:

| Field | Tipe | Keterangan |
|---|---|---|
| `name` | String | Nama lengkap karyawan |
| `email` | String | Email unik |
| `phone` | String | Nomor telepon |
| `join_date` | Date | Tanggal bergabung (format `YYYY-MM-DD`) |

> Field `nik`, `position`, `status`, dan `employment_status` di-generate otomatis oleh script — **tidak perlu dibuat di Mockaroo**.

---

## Langkah-langkah

### 1. Buka Mockaroo

Buka [https://www.mockaroo.com](https://www.mockaroo.com) di browser.

---

### 2. Tambahkan Field

Hapus semua field default yang ada, lalu tambahkan field berikut satu per satu:

#### Field 1 — `name`
- **Field Name:** `name`
- **Type:** `Full Name`

#### Field 2 — `email`
- **Field Name:** `email`
- **Type:** `Email Address`

#### Field 3 — `phone`
- **Field Name:** `phone`
- **Type:** `Phone`
- **Format:** `62###########`
  > Format ini menghasilkan nomor seperti `6281234567890` (format Indonesia tanpa tanda `+`).

#### Field 4 — `join_date`
- **Field Name:** `join_date`
- **Type:** `Date`
- **Min:** `2020-01-01`
- **Max:** `2025-12-31`
- **Format:** `%Y-%m-%d`
  > Format ini wajib `%Y-%m-%d` agar menghasilkan `2023-05-14`, sesuai format MySQL `DATE`.

---

### 3. Atur Jumlah Baris & Format

Di bagian bawah halaman:
- **Rows:** isi sesuai kebutuhan (contoh: `50`)
- **Format:** pilih **`JSON`**
- Centang opsi **`array`** (bukan JSON lines)

---

### 4. Download

Klik tombol **Download Data**.

File akan terunduh sebagai `MOCK_DATA.json`.

---

### 5. Pindahkan File ke Folder `scripts/`

```bash
mv ~/Downloads/MOCK_DATA.json /path/to/HRIS-APP/scripts/MOCK_DATA.json
```

---

### 6. Jalankan Script Inject

```bash
cd /path/to/HRIS-APP/scripts
php inject_karyawan.php
```

Output yang diharapkan:
```
Memulai proses inject data karyawan...
Total data: 50

✓ [1/50] Berhasil: John Doe (NIK: 3271234567890123)
✓ [2/50] Berhasil: Jane Smith (NIK: 6175849302716482)
...
========================================
Proses selesai!
Berhasil: 50
Gagal: 0
========================================
```

---

## Contoh Struktur JSON yang Benar

```json
[
  {
    "name": "John Doe",
    "email": "johndoe@example.com",
    "phone": "6281234567890",
    "join_date": "2022-03-15"
  },
  {
    "name": "Jane Smith",
    "email": "janesmith@example.com",
    "phone": "6289876543210",
    "join_date": "2021-07-01"
  }
]
```

---

## Catatan

- Pastikan field `email` **unik** — Mockaroo secara default sudah menggenerate email unik menggunakan type `Email Address`.
- Field `name` dan `email` **tidak boleh kosong** (kolom `NOT NULL` di database).
- Jika ada baris yang gagal di-insert (biasanya karena email duplikat), script akan melanjutkan ke baris berikutnya dan melaporkan jumlah gagal di akhir.
