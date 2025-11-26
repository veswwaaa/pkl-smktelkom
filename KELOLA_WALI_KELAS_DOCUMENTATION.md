# Kelola Wali Kelas - Admin Feature

## Overview
Fitur CRUD lengkap untuk mengelola data Wali Kelas oleh Admin, dengan sistem username dan password yang sama seperti siswa (menggunakan NIP).

## Features
✅ **Tambah Wali Kelas** - Admin dapat menambahkan wali kelas baru
✅ **Edit Data** - Mengubah NIP, nama, no telepon, dan alamat wali kelas
✅ **Hapus Wali Kelas** - Menghapus wali kelas dan akun loginnya
✅ **Reset Password** - Reset password ke format: `dummy@NIP`
✅ **Username otomatis** - Username menggunakan NIP wali kelas
✅ **Password default** - Password default: `dummy@NIP` (mirip dengan siswa: `dummy@NIS`)

## Sistem Login Wali Kelas
- **Username:** NIP wali kelas (contoh: `197001012000012001`)
- **Password:** `dummy@NIP` (contoh: `dummy@197001012000012001`)

## Files Created/Modified

### 1. Database
- ✅ `database/migrations/2025_11_24_063810_add_nip_to_tb_admin.php` - Menambahkan kolom NIP ke tb_admin
- ✅ Migration sudah dijalankan

### 2. Controller
- ✅ `app/Http/Controllers/WaliKelasAdminController.php`
  - `index()` - Menampilkan daftar wali kelas
  - `store()` - Menambahkan wali kelas baru (auto-create user dengan role wali_kelas)
  - `update()` - Mengubah data wali kelas
  - `destroy()` - Menghapus wali kelas dan usernya
  - `resetPassword()` - Reset password ke `dummy@NIP`

### 3. View
- ✅ `resources/views/admin/kelola-wali-kelas.blade.php`
  - Tabel daftar wali kelas
  - Modal tambah wali kelas
  - Modal edit wali kelas
  - Modal konfirmasi reset password
  - Modal hasil reset password (dengan tombol copy)

### 4. Model
- ✅ `app/Models/tb_admin.php` - Updated dengan fillable untuk NIP

### 5. Routes
- ✅ `routes/web.php` - Ditambahkan routes:
  ```php
  Route::get('/admin/wali-kelas', [WaliKelasAdminController::class, 'index']);
  Route::post('/admin/wali-kelas', [WaliKelasAdminController::class, 'store']);
  Route::post('/admin/wali-kelas/{id}', [WaliKelasAdminController::class, 'update']);
  Route::delete('/admin/wali-kelas/{id}', [WaliKelasAdminController::class, 'destroy']);
  Route::post('/admin/wali-kelas/{id}/reset-password', [WaliKelasAdminController::class, 'resetPassword']);
  ```

### 6. Sidebar
- ✅ `resources/views/dashboardAdmin.blade.php` - Ditambahkan menu "Kelola Wali Kelas" dengan icon chalkboard-teacher

## How to Use

### 1. Akses Menu
Login sebagai admin, lalu klik menu **Kelola Wali Kelas** (icon guru) di sidebar kiri.

### 2. Tambah Wali Kelas
1. Klik tombol **"Tambah Wali Kelas"**
2. Isi form:
   - **NIP** (wajib, unique) - akan menjadi username
   - **Nama Lengkap** (wajib)
   - **Nomor Telepon** (wajib)
   - **Alamat** (wajib)
3. Klik **"Simpan Data"**
4. Password otomatis: `dummy@NIP`

### 3. Edit Wali Kelas
1. Klik tombol **Edit** (icon pensil kuning)
2. Ubah data yang diperlukan
3. Klik **"Update Data"**

### 4. Reset Password
1. Klik tombol **Reset Password** (icon kunci kuning)
2. Konfirmasi reset
3. Password baru akan muncul di modal: `dummy@NIP`
4. Copy password dan berikan ke wali kelas

### 5. Hapus Wali Kelas
1. Klik tombol **Hapus** (icon trash merah)
2. Konfirmasi penghapusan
3. Data wali kelas dan akun user akan terhapus

## Password Format
- **Format:** `dummy@NIP`
- **Contoh:**
  - NIP: `197001012000012001`
  - Password: `dummy@197001012000012001`

Sama seperti siswa:
- NIS: `120939102`
- Password: `dummy@120939102`

## Technical Details

### Database Structure (tb_admin)
| Column       | Type         | Description                    |
|--------------|--------------|--------------------------------|
| id           | bigint       | Primary key                    |
| nip          | varchar(255) | NIP wali kelas (nullable, unique) |
| nama_admin   | varchar(255) | Nama lengkap wali kelas        |
| no_telpon    | varchar(20)  | Nomor telepon                  |
| alamat       | text         | Alamat lengkap                 |
| created_at   | timestamp    | Waktu dibuat                   |
| updated_at   | timestamp    | Waktu diupdate                 |

### User Account (tb_users)
```php
username: NIP wali kelas
password: Hash::make('dummy@' . NIP)
role: 'wali_kelas'
id_admin: ID dari tb_admin
id_dudi: null
id_siswa: null
```

## Activity Logs
Semua aksi dicatat di activity log:
- ✅ "Wali Kelas Baru Ditambahkan"
- ✅ "Data Wali Kelas Diperbarui"
- ✅ "Wali Kelas Dihapus"
- ✅ "Password Wali Kelas Direset"

## Security
- Username (NIP) harus unique
- Password di-hash menggunakan bcrypt
- CSRF protection pada semua form
- Middleware `isLoggedIn` untuk proteksi routes
- Validasi input server-side dan client-side

## Testing Steps
1. Login sebagai admin
2. Akses menu "Kelola Wali Kelas"
3. Tambah wali kelas baru dengan NIP test
4. Verify password default: `dummy@NIP`
5. Test login sebagai wali kelas (username=NIP, password=dummy@NIP)
6. Test edit data wali kelas
7. Test reset password
8. Test hapus wali kelas

## Integration with Existing Features
Wali Kelas dapat:
- ✅ Login ke sistem
- ✅ Akses dashboard wali kelas (read-only)
- ✅ Melihat data siswa PKL
- ✅ Filter & search siswa
- ❌ Tidak bisa edit/tambah/hapus data (read-only)

## Notes
- NIP field di tb_admin bersifat nullable untuk backward compatibility dengan admin yang sudah ada
- Hanya wali kelas yang memiliki NIP
- Password dapat direset kapan saja oleh admin
- Setiap wali kelas otomatis mendapat akun dengan role 'wali_kelas'

## Future Enhancements
- [ ] Import wali kelas via Excel
- [ ] Assign wali kelas ke kelas tertentu
- [ ] Dashboard khusus per kelas yang diampu
- [ ] Export data siswa ke PDF/Excel dari dashboard wali kelas
- [ ] Notifikasi email saat password direset
