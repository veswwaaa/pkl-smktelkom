# Dokumentasi Sistem Dokumen PKL - Tahap 1

## Overview

Sistem manajemen dokumen PKL untuk siswa dengan workflow:

1. **Tahap 1**: Siswa upload CV & Portofolio (✅ SELESAI)
2. **Tahap 2**: Admin kirim Surat Pernyataan → Siswa upload Eviden (⏳ PENDING)
3. **Tahap 3**: Admin kirim Surat Tugas (⏳ PENDING)

---

## Tahap 1: Upload CV & Portofolio

### Database Schema

#### Tabel: `dokumen_siswa`

```sql
CREATE TABLE dokumen_siswa (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_siswa INT UNSIGNED NOT NULL,

    -- CV & Portofolio
    file_cv VARCHAR(255) NULL,
    file_portofolio VARCHAR(255) NULL,
    tanggal_upload_cv_portofolio TIMESTAMP NULL,

    -- Surat Pernyataan (Tahap 2)
    file_surat_pernyataan VARCHAR(255) NULL,
    tanggal_kirim_surat_pernyataan TIMESTAMP NULL,
    nomor_surat_pernyataan VARCHAR(255) NULL,

    -- Eviden (Tahap 2)
    jawaban_surat_pernyataan TEXT NULL,
    file_foto_dengan_ortu VARCHAR(255) NULL,
    tanggal_upload_eviden TIMESTAMP NULL,

    -- Surat Tugas (Tahap 3)
    file_surat_tugas VARCHAR(255) NULL,
    tanggal_kirim_surat_tugas TIMESTAMP NULL,
    nomor_surat_tugas VARCHAR(255) NULL,

    -- Status tracking
    status_cv_portofolio ENUM('belum', 'sudah') DEFAULT 'belum',
    status_surat_pernyataan ENUM('belum', 'terkirim') DEFAULT 'belum',
    status_eviden ENUM('belum', 'sudah') DEFAULT 'belum',
    status_surat_tugas ENUM('belum', 'terkirim') DEFAULT 'belum',

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (id_siswa) REFERENCES tb_siswa(id) ON DELETE CASCADE
);
```

**Migration File**: `2025_11_26_030745_create_dokumen_siswa_table.php`

---

### Model & Relasi

#### Model: `DokumenSiswa`

**Location**: `app/Models/DokumenSiswa.php`

**Fillable Fields**:

-   `id_siswa`, `file_cv`, `file_portofolio`, `tanggal_upload_cv_portofolio`
-   `file_surat_pernyataan`, `tanggal_kirim_surat_pernyataan`, `nomor_surat_pernyataan`
-   `jawaban_surat_pernyataan`, `file_foto_dengan_ortu`, `tanggal_upload_eviden`
-   `file_surat_tugas`, `tanggal_kirim_surat_tugas`, `nomor_surat_tugas`
-   `status_cv_portofolio`, `status_surat_pernyataan`, `status_eviden`, `status_surat_tugas`

**Casts**:

-   `tanggal_upload_cv_portofolio` → datetime
-   `tanggal_kirim_surat_pernyataan` → datetime
-   `tanggal_upload_eviden` → datetime
-   `tanggal_kirim_surat_tugas` → datetime

**Relations**:

```php
public function siswa()
{
    return $this->belongsTo(tb_siswa::class, 'id_siswa', 'id');
}
```

#### Update Model: `tb_siswa`

**Relasi Baru**:

```php
public function dokumen()
{
    return $this->hasOne(DokumenSiswa::class, 'id_siswa', 'id');
}
```

---

### Controller

#### `DokumenSiswaController`

**Location**: `app/Http/Controllers/DokumenSiswaController.php`

**Methods**:

1. **`index()` - Halaman Upload Siswa**

    - Route: `GET /siswa/dokumen-pkl`
    - Auth: siswa
    - Return: View `siswa.dokumen-pkl`
    - Fitur: Menampilkan timeline dokumen, form upload CV & Portofolio

2. **`uploadCvPortofolio()` - Upload File**

    - Route: `POST /siswa/dokumen-pkl/upload`
    - Auth: siswa
    - Request: `file_cv`, `file_portofolio` (PDF/DOC/DOCX, max 5MB)
    - Validasi:
        ```php
        'file_cv' => 'required|mimes:pdf,doc,docx|max:5120'
        'file_portofolio' => 'required|mimes:pdf,doc,docx|max:5120'
        ```
    - Storage: `public/dokumen-siswa/cv` dan `public/dokumen-siswa/portofolio`
    - Response: JSON `{success: true, message: '...'}`
    - Activity Log: `logActivity('success', 'Upload CV & Portofolio', '...')`

3. **`adminIndex()` - Halaman Admin**

    - Route: `GET /admin/dokumen-siswa`
    - Auth: admin
    - Return: View `admin.dokumen-siswa`
    - Fitur: DataTables dengan filter, progress bar per siswa, modal detail

4. **`download()` - Download File**
    - Route: `GET /admin/dokumen-siswa/{id}/download/{type}`
    - Auth: admin
    - Params: `$id` (dokumen ID), `$type` (cv|portofolio|surat_pernyataan|foto_ortu|surat_tugas)
    - Return: File download dengan nama formatted

---

### Routes

**File**: `routes/web.php`

```php
// Siswa Routes
Route::get('/siswa/dokumen-pkl', [DokumenSiswaController::class, 'index']);
Route::post('/siswa/dokumen-pkl/upload', [DokumenSiswaController::class, 'uploadCvPortofolio']);

// Admin Routes
Route::get('/admin/dokumen-siswa', [DokumenSiswaController::class, 'adminIndex']);
Route::get('/admin/dokumen-siswa/{id}/download/{type}', [DokumenSiswaController::class, 'download']);
```

---

### Views

#### 1. Siswa: `dokumen-pkl.blade.php`

**Location**: `resources/views/siswa/dokumen-pkl.blade.php`

**Features**:

-   Timeline visual 4 tahap dokumen (CV → Surat Pernyataan → Eviden → Surat Tugas)
-   Status badge per tahap (belum/sudah/terkirim)
-   Drag & drop upload area untuk CV dan Portofolio
-   File preview sebelum upload
-   AJAX upload dengan progress indicator
-   Tombol "Upload Ulang" jika sudah upload sebelumnya
-   Link view/download file yang sudah diupload

**UI Components**:

-   Timeline dengan icon per step
-   Upload area dengan hover effect
-   File size validation (max 5MB)
-   SweetAlert2 untuk success/error messages

**JavaScript**:

-   `previewFile()` - Preview file sebelum upload
-   `clearFile()` - Hapus file dari preview
-   `showUploadForm()` / `hideUploadForm()` - Toggle form upload ulang
-   Form submit via Fetch API dengan FormData

#### 2. Admin: `dokumen-siswa.blade.php`

**Location**: `resources/views/admin/dokumen-siswa.blade.php`

**Layout**: Extends `layouts.admin`

**Features**:

-   4 Stats cards (Total Siswa, CV & Portofolio, Surat Pernyataan, Surat Tugas)
-   DataTables dengan kolom:
    -   NIS, Nama, Kelas
    -   Status per tahap (badge dengan tanggal)
    -   Progress bar visual (0%, 25%, 50%, 75%, 100%)
    -   Tombol "Detail"
-   Modal detail dokumen dengan:
    -   Info siswa lengkap
    -   Status per tahap dengan tombol download
    -   Tombol "Kirim Surat Pernyataan" / "Kirim Surat Tugas" (disabled jika belum waktunya)

**DataTables Config**:

```javascript
$("#tableDokumenSiswa").DataTable({
    order: [[2, "asc"]], // Sort by nama
    pageLength: 25,
    language: { url: "//...id.json" },
});
```

**Progress Calculation**:

```php
$progress = 0;
if ($dokumen->status_cv_portofolio == 'sudah') $progress += 25;
if ($dokumen->status_surat_pernyataan == 'terkirim') $progress += 25;
if ($dokumen->status_eviden == 'sudah') $progress += 25;
if ($dokumen->status_surat_tugas == 'terkirim') $progress += 25;
```

---

### Sidebar Update

**File**: `resources/views/layouts/admin.blade.php`

**Menu Baru**:

```html
<a
    href="/admin/dokumen-siswa"
    class="sidebar-item {{ request()->is('admin/dokumen-siswa*') ? 'active' : '' }}"
    title="Dokumen Siswa"
>
    <i class="fas fa-folder-open"></i>
</a>
```

---

## Cara Penggunaan

### Siswa:

1. Login sebagai siswa
2. Klik menu "Dokumen PKL" di sidebar
3. Upload CV dan Portofolio (format PDF/DOC/DOCX, max 5MB)
4. Klik "Upload Dokumen"
5. Tunggu admin untuk mengirim Surat Pernyataan (Tahap 2)

### Admin:

1. Login sebagai admin
2. Klik menu "Dokumen Siswa" di sidebar
3. Lihat daftar siswa dengan progress dokumen
4. Klik "Detail" untuk melihat dokumen siswa
5. Download CV/Portofolio siswa
6. (Tahap 2) Kirim Surat Pernyataan ke siswa

---

## File Storage

**Storage Path**: `storage/app/public/dokumen-siswa/`

**Structure**:

```
dokumen-siswa/
├── cv/
│   ├── [hash]_cv_siswa.pdf
│   └── ...
├── portofolio/
│   ├── [hash]_portofolio_siswa.pdf
│   └── ...
├── surat-pernyataan/ (Tahap 2)
├── eviden/ (Tahap 2)
└── surat-tugas/ (Tahap 3)
```

**Symbolic Link**: `public/storage` → `storage/app/public`

Pastikan symbolic link sudah dibuat:

```bash
php artisan storage:link
```

---

## Testing Checklist

### Siswa:

-   [ ] Bisa akses halaman dokumen-pkl
-   [ ] Timeline menampilkan 4 tahap dengan benar
-   [ ] Upload CV (PDF/DOC/DOCX) berhasil
-   [ ] Upload Portofolio berhasil
-   [ ] Validasi max 5MB berfungsi
-   [ ] Validasi format file berfungsi
-   [ ] File preview muncul sebelum upload
-   [ ] Success message muncul setelah upload
-   [ ] Status berubah menjadi "Sudah Upload"
-   [ ] File bisa dilihat di browser (open in new tab)
-   [ ] Tombol "Upload Ulang" muncul setelah upload
-   [ ] Upload ulang replace file lama

### Admin:

-   [ ] Bisa akses halaman admin/dokumen-siswa
-   [ ] Stats cards menampilkan jumlah yang benar
-   [ ] DataTables load data siswa
-   [ ] Status badge tampil sesuai kondisi (belum/sudah)
-   [ ] Progress bar menampilkan persentase yang benar
-   [ ] Modal detail bisa dibuka
-   [ ] Download CV dari admin berhasil
-   [ ] Download Portofolio dari admin berhasil
-   [ ] File name formatted dengan benar (CV_NamaSiswa_NIS.pdf)

---

## Next Steps (Tahap 2)

### Fitur yang akan dikembangkan:

1. **Admin kirim Surat Pernyataan**:

    - Form generate surat pernyataan dengan template
    - Auto-generate nomor surat
    - Send notification ke siswa
    - Update status_surat_pernyataan = 'terkirim'

2. **Siswa upload Eviden**:

    - Form jawaban pertanyaan dari surat pernyataan
    - Upload foto dengan orang tua
    - Validation dan storage
    - Update status_eviden = 'sudah'

3. **View Enhancements**:
    - Enable tombol "Kirim Surat Pernyataan" di admin
    - Notifikasi siswa ketika surat pernyataan terkirim
    - Timeline update real-time di siswa view

### Database Additions:

-   Tidak ada perubahan database (sudah disiapkan di Tahap 1)

### PDF Template:

-   Buat template surat pernyataan (dengan format sesuai screenshot user)
-   DomPDF untuk generate PDF

---

## Catatan Penting

1. **Storage Permission**: Pastikan folder `storage/app/public` memiliki write permission
2. **Symbolic Link**: Wajib jalankan `php artisan storage:link` sebelum testing
3. **File Cleanup**: Saat siswa upload ulang, file lama otomatis dihapus untuk menghemat storage
4. **Activity Log**: Setiap upload tercatat di tabel `activities` untuk audit trail
5. **Foreign Key**: Cascade delete - jika siswa dihapus, dokumen juga terhapus

---

## Dependencies

-   Laravel 10+
-   Bootstrap 5.3.2
-   Font Awesome 6.4.0
-   DataTables 1.13.7
-   SweetAlert2 11
-   jQuery 3.7.0

---

**Status**: ✅ Tahap 1 SELESAI
**Date**: 26 November 2025
**Next**: Implementasi Tahap 2 (Surat Pernyataan & Eviden)
