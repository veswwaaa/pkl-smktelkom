# Dashboard Wali Kelas - SMK Telkom

## Deskripsi
Dashboard khusus untuk Wali Kelas yang memungkinkan mereka untuk melihat data siswa yang akan PKL secara read-only (tanpa fitur edit/hapus).

## Fitur
- ✅ Tampilan dashboard dengan header "Selamat Datang Di Dashboard PKL"
- ✅ Tabel data siswa dengan informasi lengkap (NIS, Nama, Kelas, Jenis Kelamin, Angkatan, Jurusan, Status, DUDI)
- ✅ Filter berdasarkan Kelas, Jurusan, dan Status Penempatan
- ✅ Pencarian siswa berdasarkan nama atau NIS
- ✅ Summary cards: Total Siswa, Ditempatkan, Belum Ditempatkan, Selesai
- ✅ **Read-only** - tidak ada tombol aksi (tambah/edit/hapus)

## Instalasi

### 1. Jalankan Migration
Tambahkan role `wali_kelas` ke database:

```bash
php artisan migrate
```

### 2. Buat Akun Wali Kelas
Karena belum ada form registrasi khusus wali kelas, gunakan salah satu cara berikut:

#### Cara A: Manual via Database (Recommended)
1. Buka phpMyAdmin atau MySQL client
2. Buat admin baru di tabel `tb_admin`:
```sql
INSERT INTO tb_admin (nama_admin, no_telpon, alamat, created_at, updated_at) 
VALUES ('Wali Kelas XII RPL 1', '08123456789', 'SMK Telkom Banjarbaru', NOW(), NOW());
```

3. Catat ID yang baru dibuat (misal: 5)

4. Buat user dengan role `wali_kelas` di tabel `tb_users`:
```sql
INSERT INTO tb_users (username, password, role, id_admin, id_dudi, id_siswa, created_at, updated_at) 
VALUES ('walikelas1', '$2y$12$abcdefghijklmnopqrstuvwxyz', 'wali_kelas', 5, NULL, NULL, NOW(), NOW());
```

**Catatan:** Password di atas adalah contoh hash. Untuk membuat password yang benar:
- Gunakan Laravel Tinker (cara B)
- Atau gunakan tool online untuk bcrypt hash
- Password default: `walikelas123`

#### Cara B: Via Laravel Tinker (Recommended untuk development)
```bash
php artisan tinker
```

Kemudian jalankan:
```php
// Buat admin
$admin = new App\Models\tb_admin();
$admin->nama_admin = 'Wali Kelas XII RPL 1';
$admin->no_telpon = '08123456789';
$admin->alamat = 'SMK Telkom Banjarbaru';
$admin->save();

// Buat user wali kelas
$user = new App\Models\User();
$user->username = 'walikelas1';
$user->password = Hash::make('walikelas123');
$user->role = 'wali_kelas';
$user->id_admin = $admin->id;
$user->id_dudi = null;
$user->id_siswa = null;
$user->save();
```

Tekan Ctrl+C untuk keluar dari Tinker.

### 3. Login
1. Buka http://localhost/pkl-smktelkom/login
2. Username: `walikelas1`
3. Password: `walikelas123` (atau password yang kamu buat)
4. Setelah login, akan otomatis redirect ke dashboard wali kelas

## Struktur File

```
app/
  Http/
    Controllers/
      WaliKelasController.php          # Controller untuk wali kelas
      
resources/
  views/
    dashboardWaliKelas.blade.php       # View dashboard wali kelas

public/
  css/
    wali-kelas.css                     # Styling khusus wali kelas

routes/
  web.php                              # Routes ditambahkan di sini

database/
  migrations/
    2025_11_24_062300_add_wali_kelas_role_to_users.php  # Migration role
```

## Routes
- `GET /wali-kelas/dashboard` - Halaman dashboard wali kelas
- `GET /wali-kelas/siswa` - API untuk mendapatkan data siswa (JSON)

## Akses & Keamanan
- Hanya user dengan role `wali_kelas` yang bisa mengakses dashboard ini
- Middleware `isLoggedIn` melindungi semua route wali kelas
- Tidak ada aksi CRUD - hanya read-only view

## Tampilan
Dashboard menggabungkan 2 konsep:
1. **Header Dashboard** - Mirip dashboard admin dengan "Selamat Datang Di Dashboard PKL"
2. **Tabel Data Siswa** - Mirip halaman "Kelola Siswa" tapi tanpa tombol aksi

## Troubleshooting

### Error: Role 'wali_kelas' tidak valid
Pastikan migration sudah dijalankan:
```bash
php artisan migrate:status
php artisan migrate
```

### Tidak bisa login
1. Cek apakah user ada di database: `SELECT * FROM tb_users WHERE username = 'walikelas1';`
2. Cek role: pastikan `role = 'wali_kelas'`
3. Cek password: gunakan `Hash::check()` di Tinker

### Dashboard tidak muncul
1. Clear cache Laravel:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

2. Cek file CSS sudah ada di `public/css/wali-kelas.css`

## Pengembangan Selanjutnya (Opsional)
- [ ] Form registrasi khusus wali kelas
- [ ] Export data siswa ke Excel/PDF
- [ ] Filter berdasarkan kelas yang diampu
- [ ] Notifikasi untuk siswa yang belum ditempatkan
- [ ] Riwayat PKL siswa

## Kredensial Default
- **Username:** walikelas1
- **Password:** walikelas123
- **Role:** wali_kelas

⚠️ **Penting:** Ganti password default setelah pertama kali login untuk keamanan!
