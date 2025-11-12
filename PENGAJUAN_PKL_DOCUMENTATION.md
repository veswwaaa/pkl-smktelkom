# 📋 Dokumentasi Fitur Pengajuan PKL

## ✅ Status: COMPLETED (8 November 2025)

---

## 📖 Deskripsi Fitur

Sistem Pengajuan PKL memungkinkan siswa untuk:

1. **Mengajukan DUDI Mandiri** (DUDI yang belum terdaftar di sistem sekolah)
2. **Memilih 3 DUDI** (kombinasi DUDI Sekolah & DUDI Mandiri)
3. **Tracking status pengajuan** (pending, diproses, approved, rejected)

Admin dapat:

1. **Melihat semua pengajuan PKL** dengan filter status, kelas, dan pencarian
2. **Approve/Reject pengajuan**
3. **Mengubah pilihan aktif** (pilihan 1, 2, atau 3)
4. **Kelola DUDI Mandiri** yang diajukan siswa

---

## 🗂️ Struktur Database

### 1. Tabel `tb_dudi_mandiri`

Menyimpan DUDI yang diinput oleh siswa (belum terdaftar di sekolah).

| Kolom            | Tipe         | Keterangan                        |
| ---------------- | ------------ | --------------------------------- |
| id               | BIGINT PK    | Primary Key                       |
| id_siswa         | INT UNSIGNED | FK ke tb_siswa (tanpa constraint) |
| nama_dudi        | VARCHAR      | Nama DUDI                         |
| nomor_telepon    | VARCHAR      | Nomor telepon DUDI                |
| person_in_charge | VARCHAR      | Nama PIC DUDI                     |
| alamat           | TEXT         | Alamat DUDI                       |
| status           | ENUM         | pending, approved, rejected       |
| created_at       | TIMESTAMP    | Waktu dibuat                      |
| updated_at       | TIMESTAMP    | Waktu diupdate                    |

**Index:** `id_siswa`

---

### 2. Tabel `tb_pengajuan_pkl`

Menyimpan pengajuan PKL siswa dengan 3 pilihan DUDI.

| Kolom                     | Tipe            | Keterangan                            |
| ------------------------- | --------------- | ------------------------------------- |
| id                        | BIGINT PK       | Primary Key                           |
| id_siswa                  | INT UNSIGNED    | FK ke tb_siswa                        |
| id_dudi_pilihan_1         | INT UNSIGNED    | FK ke tb_dudi (nullable)              |
| id_dudi_mandiri_pilihan_1 | BIGINT UNSIGNED | FK ke tb_dudi_mandiri (nullable)      |
| id_dudi_pilihan_2         | INT UNSIGNED    | FK ke tb_dudi (nullable)              |
| id_dudi_mandiri_pilihan_2 | BIGINT UNSIGNED | FK ke tb_dudi_mandiri (nullable)      |
| id_dudi_pilihan_3         | INT UNSIGNED    | FK ke tb_dudi (nullable)              |
| id_dudi_mandiri_pilihan_3 | BIGINT UNSIGNED | FK ke tb_dudi_mandiri (nullable)      |
| pilihan_aktif             | ENUM            | 1, 2, 3 (default: 1)                  |
| status                    | ENUM            | pending, diproses, approved, rejected |
| tanggal_pengajuan         | DATE            | Tanggal pengajuan                     |
| created_at                | TIMESTAMP       | Waktu dibuat                          |
| updated_at                | TIMESTAMP       | Waktu diupdate                        |

**Index:** `id_siswa`, `id_dudi_pilihan_1/2/3`, `id_dudi_mandiri_pilihan_1/2/3`

**Catatan:**

-   Setiap pilihan bisa berupa DUDI Sekolah ATAU DUDI Mandiri (salah satu nullable)
-   `pilihan_aktif` menentukan DUDI mana yang sedang diproses admin

---

## 🎯 Alur Kerja (Workflow)

### A. Siswa Side

#### 1. Login Siswa

-   URL: `/login`
-   Siswa login menggunakan NIS dan password

#### 2. Akses Halaman Pengajuan PKL

-   URL: `/siswa/pengajuan-pkl`
-   Menu: **Sidebar → Pengajuan PKL**

#### 3. Tab 1: Data Pengajuan PKL

**Form Input:**

-   ✅ Info Siswa (auto-fill dari session):
    -   NIS, Nama, Kelas, Jurusan (read-only)
-   ✅ Pilihan 1 (Dropdown gabungan):
    -   DUDI Sekolah
    -   DUDI Mandiri (yang sudah diinput siswa)
-   ✅ Pilihan 2 (Dropdown gabungan)
-   ✅ Pilihan 3 (Dropdown gabungan)

**Validasi:**

-   Semua pilihan harus diisi
-   Tidak boleh ada duplikasi (pilihan 1, 2, 3 harus berbeda)
-   Siswa hanya bisa submit 1 pengajuan (cek existing)

**Submit:**

-   Data disimpan ke `tb_pengajuan_pkl`
-   Status: `pending`
-   Tanggal pengajuan: otomatis (hari ini)
-   Activity log dicatat

#### 4. Tab 2: Data Pengajuan Individu (DUDI Mandiri)

**Form Input:**

-   Nama DUDI
-   Nomor Telepon
-   Person in Charge (PIC)
-   Alamat

**Submit:**

-   Data disimpan ke `tb_dudi_mandiri`
-   Status: `pending`
-   Otomatis ter-link ke siswa (via `id_siswa`)
-   DUDI langsung muncul di dropdown Tab 1
-   Activity log dicatat

---

### B. Admin Side

#### 1. Akses Halaman Kelola Pengajuan PKL

-   URL: `/admin/pengajuan-pkl`
-   Menu: **Sidebar → Pengajuan PKL** (icon clipboard)

#### 2. Filter & Pencarian

**Filter:**

-   Status: All, Pending, Diproses, Approved, Rejected
-   Kelas: XIIA - XIIG
-   Search: Nama/NIS siswa

**Hasil:**

-   Tabel dengan kolom:
    -   No, NIS, Nama, Kelas, Jurusan
    -   Pilihan Aktif (badge + nama DUDI)
    -   Tanggal Pengajuan
    -   Status (badge warna-warni)
    -   Aksi (View, Approve, Reject, Delete)

#### 3. View Detail Pengajuan

**Klik tombol View (mata):**

-   Modal muncul dengan:
    -   Data Siswa (NIS, Nama, Kelas, Jurusan)
    -   Pilihan DUDI 1/2/3 (dengan badge "Aktif" di pilihan aktif)
    -   Tanggal pengajuan
    -   Status
    -   Form ubah pilihan aktif (dropdown + button)

#### 4. Ubah Pilihan Aktif

-   Admin bisa mengubah DUDI mana yang akan diproses
-   Dropdown menampilkan nama DUDI untuk setiap pilihan
-   Submit → pilihan_aktif di database diupdate
-   Activity log dicatat

#### 5. Approve Pengajuan

**Klik tombol Approve (hijau):**

-   SweetAlert konfirmasi
-   Jika disetujui:
    -   Status pengajuan → `approved`
    -   Siswa otomatis ditempatkan ke DUDI (pilihan aktif)
    -   `tb_siswa.id_dudi` → diisi dengan DUDI pilihan aktif
    -   `tb_siswa.status_penempatan` → `ditempatkan`
    -   Activity log dicatat
-   Success message muncul

#### 6. Reject Pengajuan

**Klik tombol Reject (kuning):**

-   SweetAlert konfirmasi
-   Jika ditolak:
    -   Status pengajuan → `rejected`
    -   Siswa tidak ditempatkan
    -   Activity log dicatat
-   Success message muncul

#### 7. Delete Pengajuan

**Klik tombol Delete (merah):**

-   SweetAlert konfirmasi
-   Data pengajuan dihapus dari database
-   Activity log dicatat

#### 8. Akses Halaman Kelola DUDI Mandiri

-   URL: `/admin/dudi-mandiri`
-   Menu: (belum ada di sidebar, akses manual via URL)

**Fitur:**

-   Filter by status (pending, approved, rejected)
-   Search by nama DUDI atau siswa
-   Tabel: Siswa, DUDI, Kontak, Alamat, Status
-   Aksi: Approve, Reject, Delete

---

## 📁 File Structure

### Controllers

```
app/Http/Controllers/
├── PengajuanPklController.php          # Siswa side
├── PengajuanPklAdminController.php     # Admin side
├── DudiMandiriController.php           # Siswa side
└── DudiMandiriAdminController.php      # Admin side
```

### Models

```
app/Models/
├── PengajuanPkl.php      # Model pengajuan PKL
└── DudiMandiri.php       # Model DUDI Mandiri
```

### Views

```
resources/views/
├── siswa/
│   └── pengajuan-pkl.blade.php         # Form pengajuan siswa (2 tabs)
└── admin/
    ├── kelola-pengajuan.blade.php      # Kelola pengajuan admin
    └── kelola-dudi-mandiri.blade.php   # (BELUM DIBUAT)
```

### Assets

```
public/
├── css/
│   ├── pengajuan-pkl.css               # Styling form siswa
│   └── kelola-dudi.css                 # Styling (reuse untuk admin)
└── js/
    └── pengajuan-pkl.js                # Validasi form siswa
```

### Migrations

```
database/migrations/
├── 2025_11_08_033815_create_tb_dudi_mandiri_table.php
└── 2025_11_08_033841_create_tb_pengajuan_pkl_table.php
```

---

## 🔗 Routes

### Siswa Routes

```php
// Pengajuan PKL
GET  /siswa/pengajuan-pkl                 → index (tampil form)
POST /siswa/pengajuan-pkl                 → store (simpan pengajuan)

// DUDI Mandiri
POST   /siswa/dudi-mandiri                → store (simpan DUDI mandiri)
GET    /siswa/dudi-mandiri/current        → getByCurrentSiswa (JSON API)
DELETE /siswa/dudi-mandiri/{id}           → destroy (hapus DUDI mandiri)
```

### Admin Routes

```php
// Pengajuan PKL
GET    /admin/pengajuan-pkl                     → index (list semua)
GET    /admin/pengajuan-pkl/{id}/detail         → detail (JSON API)
POST   /admin/pengajuan-pkl/{id}/approve        → approve
POST   /admin/pengajuan-pkl/{id}/reject         → reject
POST   /admin/pengajuan-pkl/{id}/change-pilihan → changePilihan
DELETE /admin/pengajuan-pkl/{id}                → destroy

// DUDI Mandiri
GET    /admin/dudi-mandiri                      → index
POST   /admin/dudi-mandiri/{id}/approve         → approve
POST   /admin/dudi-mandiri/{id}/reject          → reject
DELETE /admin/dudi-mandiri/{id}                 → destroy
```

---

## 🎨 UI Components

### Siswa Side

-   **Sidebar Navigation:**

    -   Dashboard
    -   Pengajuan PKL (active)
    -   Logout

-   **Tabs:**

    -   Tab 1: Data Pengajuan PKL (form 3 pilihan)
    -   Tab 2: Data Pengajuan Individu (input DUDI mandiri)

-   **Info Box:**

    -   Alert biru dengan data siswa (NIS, Nama, Kelas, Jurusan)

-   **Dropdown Gabungan:**
    -   `<optgroup label="DUDI Sekolah">`
    -   `<optgroup label="DUDI Mandiri">`

### Admin Side

-   **Sidebar Navigation:**

    -   Dashboard
    -   Kelola DUDI
    -   Kelola Siswa
    -   **Pengajuan PKL** (active) ← NEW
    -   Reports

-   **Filter Bar:**

    -   Dropdown Status
    -   Dropdown Kelas
    -   Input Search
    -   Button Filter

-   **Table:**

    -   Striped hover table
    -   Badge untuk status (warning, info, success, danger)
    -   Badge untuk pilihan aktif (primary)
    -   Action buttons (info, success, warning, danger)

-   **Modal Detail:**
    -   2 kolom layout (Data Siswa | Pilihan DUDI)
    -   Form ubah pilihan aktif
    -   Badge "Aktif" pada pilihan yang dipilih

---

## 🔐 Security & Validation

### Siswa Side

1. **Middleware:** `isLoggedIn` + role check (siswa)
2. **Validasi Form:**
    - Pilihan 1/2/3 required
    - No duplicate validation (JavaScript + backend)
    - Siswa hanya bisa 1 pengajuan (check existing)
3. **Session Data:**
    - `id_siswa` diambil dari `$user->id_siswa`
    - Tidak ada input manual dari user

### Admin Side

1. **Middleware:** `isLoggedIn` + role check (admin)
2. **Validasi:**
    - Status enum validation
    - Pilihan aktif enum (1, 2, 3)
3. **Authorization:**
    - Hanya admin yang bisa approve/reject/delete

---

## 📊 Activity Logging

Semua aksi tercatat di `activities` table:

### Siswa Actions

-   **create:** "DUDI Mandiri Ditambahkan"
-   **create:** "Pengajuan PKL Dibuat"
-   **delete:** "DUDI Mandiri Dihapus"

### Admin Actions

-   **update:** "Status Pengajuan PKL Diubah"
-   **update:** "Pilihan PKL Diubah"
-   **update:** "Pengajuan PKL Disetujui"
-   **update:** "Pengajuan PKL Ditolak"
-   **delete:** "Pengajuan PKL Dihapus"
-   **update:** "DUDI Mandiri Disetujui"
-   **update:** "DUDI Mandiri Ditolak"
-   **delete:** "DUDI Mandiri Dihapus"

---

## 🐛 Troubleshooting

### 1. Dropdown DUDI Kosong

**Penyebab:**

-   Belum ada DUDI Sekolah di database
-   Belum input DUDI Mandiri

**Solusi:**

-   Admin: Tambah DUDI via "Kelola DUDI"
-   Siswa: Input DUDI Mandiri di Tab 2

### 2. Form Submit Error "Anda sudah mengajukan PKL"

**Penyebab:**

-   Siswa sudah punya pengajuan sebelumnya

**Solusi:**

-   Siswa hanya bisa 1 pengajuan
-   Admin harus hapus pengajuan lama jika ingin siswa ajukan lagi

### 3. Approve Gagal

**Penyebab:**

-   Pilihan aktif tidak valid
-   DUDI tidak ditemukan

**Solusi:**

-   Pastikan pilihan aktif sudah di-set
-   Pastikan DUDI masih ada di database

### 4. DUDI Mandiri Tidak Muncul di Dropdown

**Penyebab:**

-   Status masih pending/rejected
-   DUDI Mandiri milik siswa lain

**Solusi:**

-   Dropdown hanya menampilkan DUDI Mandiri milik siswa yang login
-   Admin harus approve DUDI Mandiri agar bisa digunakan (opsional)

---

## 🚀 Next Steps / Future Enhancements

### Yang Sudah Selesai

-   ✅ Database structure (migrations)
-   ✅ Models dengan relationships
-   ✅ Siswa: Form pengajuan PKL (2 tabs)
-   ✅ Siswa: Input DUDI Mandiri
-   ✅ Admin: Kelola pengajuan PKL
-   ✅ Admin: Filter & search
-   ✅ Admin: Approve/reject pengajuan
-   ✅ Admin: Ubah pilihan aktif
-   ✅ Routes & middleware
-   ✅ Activity logging
-   ✅ UI/UX responsive

### Yang Perlu Ditambahkan

-   ⏳ **Admin: Kelola DUDI Mandiri** (view sudah ada di controller, tinggal buat Blade)
-   ⏳ **Notifikasi:** Email/WhatsApp ke siswa saat status berubah
-   ⏳ **Grade Input:** Admin input grade siswa (A/B/C/D)
-   ⏳ **Upload CV & Portfolio:** Siswa upload dokumen
-   ⏳ **Export:** Export pengajuan ke Excel/PDF
-   ⏳ **Dashboard Stats:** Tampilkan jumlah pengajuan pending/approved/rejected
-   ⏳ **DUDI Side:** View pengajuan dari siswa, approve/reject
-   ⏳ **Surat Pengajuan:** Generate surat otomatis

---

## 📝 Developer Notes

### Kenapa Tidak Pakai Foreign Key Constraint?

-   `tb_siswa.id` menggunakan `int(11)` (bukan bigint)
-   Untuk menghindari error FK constraint mismatch
-   Menggunakan **index** saja untuk performance
-   Relasi tetap di-handle di Eloquent Model

### Kenapa Pilihan Pakai Format "sekolah-{id}" atau "mandiri-{id}"?

-   Agar bisa membedakan apakah pilihan dari DUDI Sekolah atau DUDI Mandiri
-   Di backend: `explode('-', $request->pilihan_1)` → `[$type, $id]`
-   Lebih fleksibel daripada 2 dropdown terpisah

### Kenapa Ada Pilihan Aktif?

-   Jika pilihan 1 ditolak DUDI, bisa pindah ke pilihan 2
-   Admin bisa mengatur strategi penempatan siswa
-   Lebih fleksibel dalam workflow approval

---

## 👨‍💻 Credits

-   **Developer:** GitHub Copilot + Developer (veswwaaa)
-   **Framework:** Laravel 11
-   **Frontend:** Bootstrap 5.3.2
-   **Icons:** Font Awesome 6.0
-   **Alerts:** SweetAlert2
-   **Date:** November 8, 2025

---

**Status Akhir:** ✅ Feature Complete & Ready for Testing!
