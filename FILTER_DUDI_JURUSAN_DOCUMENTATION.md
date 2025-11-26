# Dokumentasi Fitur Filter DUDI Berdasarkan Jurusan

## 📋 Overview
Fitur ini memfilter daftar DUDI yang ditampilkan di dashboard siswa berdasarkan jurusan siswa yang login. Hanya DUDI yang menerima jurusan siswa yang akan ditampilkan.

## 🎯 Fitur Utama

### 1. Filter Otomatis di Dashboard Siswa
- **Lokasi**: Dashboard Siswa (`/dashboard`)
- **Fungsi**: Menampilkan hanya DUDI yang menerima jurusan siswa yang sedang login
- **Contoh**: Siswa jurusan RPL hanya melihat DUDI yang menerima RPL

### 2. Form Input Profil Penerimaan PKL di Admin
- **Lokasi**: Kelola DUDI → Icon Info (Profil Penerimaan PKL)
- **Fungsi**: Admin dapat mengatur jurusan yang diterima dan jobdesk untuk setiap DUDI
- **Field**:
  - **Jurusan yang Diterima** (Multi-select checkbox):
    - RPL (Rekayasa Perangkat Lunak)
    - TKJ (Teknik Komputer dan Jaringan)
    - MM (Multimedia)
    - DKV (Desain Komunikasi Visual)
    - TJKT (Teknik Jaringan Komputer dan Telekomunikasi)
  - **Jobdesk Siswa PKL** (Textarea):
    - Deskripsi tugas yang akan dikerjakan siswa PKL
    - Minimal 10 karakter

## 🔧 Technical Implementation

### 1. Database Schema
**Tabel**: `tb_dudi`
- `jurusan_diterima` (JSON/Array) - Daftar jurusan yang diterima DUDI
- `jobdesk` (TEXT) - Deskripsi tugas siswa PKL

### 2. Controller Logic

#### AuthenController - Filter DUDI
```php
// Filter DUDI berdasarkan jurusan siswa
$dudiTersedia = tb_dudi::where('jenis_dudi', 'sekolah')
    ->whereNotNull('jurusan_diterima')
    ->whereNotNull('jobdesk')
    ->get()
    ->filter(function($dudi) use ($data) {
        if ($dudi->jurusan_diterima && is_array($dudi->jurusan_diterima)) {
            return in_array($data->jurusan, $dudi->jurusan_diterima);
        }
        return false;
    });
```

#### DudiController - Update Profil Penerimaan
```php
public function updateProfilPenerimaan(Request $request)
{
    $request->validate([
        'id_dudi' => 'required|exists:tb_dudi,id',
        'jurusan_diterima' => 'required|array|min:1',
        'jurusan_diterima.*' => 'in:RPL,TKJ,MM,DKV,TJKT',
        'jobdesk' => 'required|string|min:10',
    ]);

    $dudi = tb_dudi::findOrFail($request->id_dudi);
    $dudi->jurusan_diterima = $request->jurusan_diterima;
    $dudi->jobdesk = $request->jobdesk;
    $dudi->save();

    return response()->json([
        'success' => true,
        'message' => 'Profil penerimaan PKL berhasil diperbarui!'
    ]);
}
```

### 3. Routes
```php
Route::post('/admin/dudi/update-profil-penerimaan', [DudiController::class, 'updateProfilPenerimaan']);
```

## 🎨 UI/UX Design

### Dashboard Siswa
1. **Header Section**:
   - Title: "Pilihan PKL yang Tersedia untuk Jurusan {JURUSAN}"
   - Badge: Jumlah DUDI yang tersedia

2. **DUDI Card**:
   - Logo DUDI dengan gradient background
   - Nama DUDI (bold, 1.25rem)
   - Deskripsi singkat jobdesk
   - Contact info (phone, address)
   - Badge jurusan dengan warna berbeda:
     - **RPL**: Biru (#3b82f6)
     - **TKJ**: Cyan (#06b6d4)
     - **MM**: Orange (#f59e0b)
     - **DKV**: Red (#ef4444)
     - **TJKT**: Green (#10b981)
   - Jobdesk section dengan border kiri merah

3. **Empty State**:
   - Icon building dalam circle
   - Pesan: "Belum Ada DUDI Tersedia untuk Jurusan {JURUSAN}"
   - Info box dengan tips:
     - DUDI sedang memproses profil
     - Belum ada DUDI untuk jurusan tersebut
     - Saran ajukan DUDI mandiri

### Modal Profil Penerimaan (Admin)
1. **Header**: Background hijau dengan icon info
2. **Body**:
   - Nama DUDI (read-only)
   - Checkbox group untuk jurusan (5 options)
   - Textarea untuk jobdesk (10 baris)
3. **Footer**: Button Batal & Simpan Profil

## 📊 Alur Kerja

### Untuk Admin:
1. Masuk ke **Kelola DUDI**
2. Klik icon **Info (hijau)** pada DUDI yang ingin diatur
3. Modal **Profil Penerimaan PKL** akan muncul
4. Centang jurusan yang diterima (minimal 1)
5. Isi jobdesk siswa PKL (minimal 10 karakter)
6. Klik **Simpan Profil**
7. Data tersimpan dan log activity tercatat

### Untuk Siswa:
1. Login ke dashboard siswa
2. Lihat section **"Pilihan PKL yang Tersedia untuk Jurusan {JURUSAN}"**
3. Hanya DUDI yang menerima jurusan siswa yang ditampilkan
4. Lihat detail jurusan (badge warna) dan jobdesk
5. Jika kosong, lihat empty state dengan informasi lengkap

## ✅ Validasi

### Server-side (Laravel)
- `jurusan_diterima`: Required, array, minimal 1 item
- `jurusan_diterima.*`: Harus salah satu dari RPL, TKJ, MM, DKV, TJKT
- `jobdesk`: Required, string, minimal 10 karakter

### Client-side (JavaScript)
- Minimal 1 jurusan harus dipilih
- Jobdesk tidak boleh kosong
- Alert menggunakan SweetAlert2

## 🔐 Security
- CSRF Token protection
- Role-based access (hanya admin yang bisa update)
- Input validation di server & client
- XSS prevention dengan Laravel Blade escaping

## 📝 Activity Log
Setiap perubahan profil penerimaan akan dicatat:
```
Activity Type: update
Title: Profil Penerimaan PKL Diperbarui
Description: Profil penerimaan PKL untuk DUDI {nama} telah diperbarui. Jurusan: RPL, TKJ
```

## 🎯 Benefits
1. **Untuk Siswa**:
   - Melihat hanya DUDI yang relevan dengan jurusan
   - Tidak bingung dengan DUDI yang tidak menerima jurusan mereka
   - Informasi jobdesk yang jelas

2. **Untuk Admin**:
   - Mudah mengatur profil penerimaan setiap DUDI
   - Interface yang user-friendly
   - Data terstruktur dan konsisten

3. **Untuk DUDI**:
   - Menerima siswa sesuai kebutuhan jurusan
   - Jobdesk yang sudah jelas dari awal
   - Mengurangi mismatch penempatan

## 🐛 Troubleshooting

### DUDI tidak muncul di dashboard siswa
**Kemungkinan penyebab**:
1. DUDI belum mengisi profil penerimaan (jurusan_diterima & jobdesk NULL)
2. Jurusan siswa tidak ada di list jurusan_diterima DUDI
3. Jenis DUDI bukan 'sekolah'

**Solusi**:
1. Admin harus mengisi profil penerimaan PKL untuk DUDI tersebut
2. Pastikan jurusan siswa dicentang di modal profil penerimaan
3. Pastikan jenis_dudi = 'sekolah' (bukan 'mandiri')

### Error saat menyimpan profil penerimaan
**Kemungkinan penyebab**:
1. Tidak ada jurusan yang dipilih
2. Jobdesk kurang dari 10 karakter
3. CSRF token expired

**Solusi**:
1. Centang minimal 1 jurusan
2. Isi jobdesk minimal 10 karakter
3. Refresh halaman dan coba lagi

## 📚 Related Files
- `app/Http/Controllers/AuthenController.php` (line 260-275)
- `app/Http/Controllers/DudiController.php` (method: updateProfilPenerimaan)
- `resources/views/dashboardSiswa.blade.php`
- `resources/views/admin/kelola-dudi.blade.php` (Modal Profil Penerimaan)
- `public/css/dashboard-siswa-new.css` (DUDI Card styles)
- `routes/web.php` (Route: /admin/dudi/update-profil-penerimaan)

## 🚀 Future Enhancements
1. Tambah filter berdasarkan lokasi DUDI
2. Tambah kapasitas maksimal siswa per jurusan
3. Notifikasi ke siswa ketika DUDI baru tersedia untuk jurusan mereka
4. Export data DUDI per jurusan ke Excel
5. Dashboard analytics: jumlah DUDI per jurusan

---

**Dibuat**: 26 November 2025  
**Author**: GitHub Copilot  
**Version**: 1.0
