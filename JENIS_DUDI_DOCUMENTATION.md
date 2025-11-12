# Dokumentasi Pemisahan DUDI Sekolah & DUDI Mandiri

## 📌 Overview

Sistem PKL kini memiliki **pemisahan jelas** antara DUDI yang bekerja sama dengan sekolah dan DUDI yang dipilih mandiri oleh siswa. Pemisahan ini dilakukan dalam **satu tabel** (`tb_dudi`) dengan menggunakan field pembeda `jenis_dudi`.

---

## 🗂️ Database Schema

### Field Baru di `tb_dudi`

| Field        | Type | Values                   | Default     | Description        |
| ------------ | ---- | ------------------------ | ----------- | ------------------ |
| `jenis_dudi` | ENUM | `'sekolah'`, `'mandiri'` | `'sekolah'` | Penanda jenis DUDI |

**Migrasi:** `2025_11_11_101527_add_jenis_dudi_to_tb_dudi_table.php`

---

## 🔄 Alur Sistem

### 1️⃣ DUDI Sekolah

-   **Ditambahkan oleh:** Admin secara manual
-   **Jenis:** `jenis_dudi = 'sekolah'`
-   **Visibilitas:** Muncul di semua siswa dalam dropdown pengajuan PKL
-   **Lokasi di Dropdown:** Optgroup "🏫 DUDI Sekolah"

### 2️⃣ DUDI Mandiri (Belum Approved)

-   **Ditambahkan oleh:** Siswa melalui form DUDI Mandiri
-   **Status:** Tersimpan di `tb_dudi_mandiri` dengan `status = 'pending'`
-   **Visibilitas:** Hanya muncul untuk siswa yang menambahkan
-   **Lokasi di Dropdown:** Optgroup "⏳ DUDI Mandiri Anda (Belum Approved)"
-   **Catatan:** Siswa bisa memilih DUDI ini dalam pengajuan PKL meskipun belum approved

### 3️⃣ DUDI Mandiri (Sudah Approved)

-   **Proses Approval:** Admin mengapprove dari halaman "Kelola DUDI Mandiri"
-   **Jenis:** `jenis_dudi = 'mandiri'`
-   **Visibilitas:** **HANYA muncul untuk siswa yang membuatnya** (PRIVAT)
-   **Lokasi di Dropdown:** Optgroup "👨‍🎓 DUDI Mandiri (Approved)"
-   **Akun DUDI:** Otomatis dibuatkan akun user dengan:
    -   Username: Nama DUDI (lowercase, boleh spasi)
    -   Password: `dudi123`
    -   Role: `dudi`
-   **Catatan:** DUDI Mandiri tetap privat bahkan setelah approved, tidak dibagikan ke siswa lain

---

## 🖥️ Halaman Admin

### Kelola DUDI (`/admin/dudi`)

**Filter Dropdown:**

-   🏢 **Semua DUDI** - Menampilkan semua DUDI (sekolah + mandiri)
-   🏫 **DUDI Sekolah** - Hanya menampilkan `jenis_dudi = 'sekolah'`
-   👨‍🎓 **DUDI Mandiri Siswa** - Hanya menampilkan `jenis_dudi = 'mandiri'`

**Kolom Tabel:**
| No | Nama DUDI | No. Telpon | Alamat | PIC | **Jenis** | Status | Aksi |
|----|-----------|------------|--------|-----|-----------|--------|------|
| ... | PT ABC | ... | ... | ... | 🏫 Sekolah | ... | ... |
| ... | PT XYZ | ... | ... | ... | 👨‍🎓 Mandiri | ... | ... |

---

## 👨‍🎓 Halaman Siswa

### Form Pengajuan PKL (`/siswa/pengajuan-pkl`)

**Dropdown Pilihan DUDI:**

```
Pilih DUDI
├── 🏫 DUDI Sekolah
│   ├── PT Telkomsel
│   ├── PT Pertamina
│   └── ...
├── 👨‍🎓 DUDI Mandiri (Approved)
│   ├── PT MTP
│   ├── PT Jojo
│   └── ...
└── ⏳ DUDI Mandiri Anda (Belum Approved)
    ├── PT ABC (Menunggu Persetujuan)
    └── ...
```

**Keterangan:**

-   **DUDI Sekolah:** DUDI yang ditambahkan admin
-   **DUDI Mandiri (Approved):** DUDI dari siswa lain yang sudah diapprove admin (bisa dipilih semua siswa)
-   **DUDI Mandiri Anda (Belum Approved):** DUDI yang Anda tambahkan sendiri tapi belum diapprove

---

## 🔧 Controller Changes

### `DudiController.php`

```php
// Method: index()
// Filter DUDI berdasarkan jenis_dudi
$query = tb_dudi::query();
if ($request->has('jenis_dudi') && $request->jenis_dudi != '') {
    $query->where('jenis_dudi', $request->jenis_dudi);
}
$dudi = $query->get();

// Method: store()
// Set jenis_dudi = 'sekolah' untuk DUDI yang ditambahkan admin
$dudi->jenis_dudi = 'sekolah';
```

### `DudiMandiriAdminController.php`

```php
// Method: approve()
// Set jenis_dudi = 'mandiri' saat approve DUDI Mandiri
$dudi = tb_dudi::create([
    // ...
    'jenis_dudi' => 'mandiri',
]);
```

### `PengajuanPklController.php`

```php
// Method: index()
// Pisahkan query DUDI berdasarkan jenis
$dudiSekolah = tb_dudi::where('jenis_dudi', 'sekolah')->get();

// DUDI Mandiri yang sudah approved - HANYA milik siswa yang login
$dudiMandiriApproved = DudiMandiri::where('id_siswa', $user->id_siswa)
    ->whereNotNull('id_dudi')
    ->with('dudi')
    ->get()
    ->pluck('dudi')
    ->filter();

// DUDI Mandiri yang belum approved - HANYA milik siswa yang login
$dudiMandiri = DudiMandiri::where('id_siswa', $user->id_siswa)
    ->whereNull('id_dudi')
    ->get();

return view('siswa.pengajuan-pkl', compact(
    'data',
    'dudiSekolah',
    'dudiMandiriApproved', // DUDI Mandiri siswa ini yang sudah approved
    'dudiMandiri' // DUDI Mandiri siswa ini yang belum approved
));
```

---

## 📊 Statistik (Contoh)

Setelah migration dan script update:

```
📊 Statistik DUDI:
   • DUDI Sekolah: 5
   • DUDI Mandiri: 4
   • Total: 9
```

Detail DUDI Mandiri yang sudah diupdate:

-   asade
-   pt jojo
-   pt telkomsel
-   pt mtp

---

## 🛠️ Maintenance Script

### `update-jenis-dudi.php`

Script untuk update data existing:

1. Mencari semua DUDI yang `id`-nya ada di `tb_dudi_mandiri.id_dudi`
2. Update field `jenis_dudi` menjadi `'mandiri'`
3. Menampilkan statistik sebelum dan sesudah

**Hasil Eksekusi:**

```
✅ Berhasil update 4 DUDI menjadi jenis 'mandiri'

Detail DUDI yang diupdate:
------------------------------------------------------------
ID: 7 | asade | Jenis: mandiri
ID: 8 | pt jojo | Jenis: mandiri
ID: 9 | pt telkomsel | Jenis: mandiri
ID: 10 | pt mtp | Jenis: mandiri
------------------------------------------------------------
```

---

## ❓ FAQ

### Q: Apakah DUDI Mandiri yang sudah approved bisa dipilih oleh siswa lain?

**A:** TIDAK! DUDI Mandiri tetap PRIVAT dan hanya muncul untuk siswa yang membuatnya, bahkan setelah approved oleh admin.

### Q: Apa perbedaan DUDI Sekolah dan DUDI Mandiri?

**A:**

-   **DUDI Sekolah:** Ditambahkan admin, muncul di semua siswa, kerjasama resmi sekolah
-   **DUDI Mandiri:** Ditambahkan siswa, hanya muncul untuk siswa yang membuat, pilihan pribadi siswa

### Q: Bagaimana cara membedakan DUDI Sekolah dan DUDI Mandiri?

**A:** Lihat kolom "Jenis" di tabel admin atau gunakan filter dropdown di atas tabel.

### Q: Apakah data DUDI lama berubah setelah migration?

**A:** Tidak! Data lama otomatis mendapat nilai default `jenis_dudi = 'sekolah'`. Hanya DUDI yang berasal dari DUDI Mandiri yang diupdate menjadi `'mandiri'` oleh script `update-jenis-dudi.php`.

### Q: Bisakah admin mengubah jenis DUDI dari 'mandiri' ke 'sekolah'?

**A:** Saat ini belum ada fitur edit jenis DUDI di UI. Namun bisa dilakukan manual di database jika diperlukan.

---

## 📝 Changelog

**2025-11-11**

-   ✅ Migration: Added `jenis_dudi` field to `tb_dudi`
-   ✅ Updated `DudiController` to set `jenis_dudi = 'sekolah'` for manual entries
-   ✅ Updated `DudiMandiriAdminController` to set `jenis_dudi = 'mandiri'` for approved DUDI Mandiri
-   ✅ Updated `PengajuanPklController` to separate DUDI queries
-   ✅ Added filter dropdown in admin kelola DUDI page
-   ✅ Updated student form to show 3 separate optgroups
-   ✅ Created `update-jenis-dudi.php` script to update existing data
-   ✅ Updated 4 existing DUDI Mandiri records

---

## 🎯 Benefit

1. **Privacy:** DUDI Mandiri tetap privat untuk setiap siswa, tidak terbagi ke siswa lain
2. **Transparency:** Admin dan siswa bisa melihat jelas mana DUDI dari sekolah dan mana dari siswa
3. **Simplicity:** Tidak perlu tabel terpisah, semua dalam satu tabel `tb_dudi`
4. **Filtering:** Admin bisa filter dengan mudah di halaman kelola DUDI
5. **User Experience:** Siswa mendapat informasi jelas saat memilih DUDI

---

**Dokumentasi dibuat:** 11 November 2025  
**Versi Sistem:** 1.1.0
