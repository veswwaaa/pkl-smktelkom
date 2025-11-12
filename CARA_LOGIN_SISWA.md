# 📚 Cara Login Siswa Setelah Import Data

## ✅ Yang Sudah Diperbaiki:

1. **Controller Login** - Menambahkan `trim()` untuk menghilangkan spasi yang tidak terlihat
2. **Pesan Error** - Diperbaiki menjadi Bahasa Indonesia yang lebih jelas

## 🔐 Cara Login untuk Siswa

Setelah data siswa diimport dari Excel, sistem otomatis membuat akun login dengan format:

-   **Username:** NIS siswa (contoh: `543241165`)
-   **Password:** `dummy@[NIS]` (contoh: `dummy@543241165`)

### Contoh Login:

```
Username: 543241165
Password: dummy@543241165
```

## 🐛 Troubleshooting - Jika Login Gagal

### 1. Error: "Username (NIS) tidak terdaftar"

**Penyebab:**

-   Data siswa belum diimport
-   NIS yang diinput salah
-   Ada spasi di awal/akhir NIS (sudah diperbaiki dengan trim)

**Solusi:**

-   Pastikan data siswa sudah diimport melalui fitur Import Excel di halaman Kelola Siswa
-   Periksa NIS di database atau di daftar siswa
-   Copy-paste NIS langsung dari daftar siswa jika perlu

### 2. Error: "Password salah"

**Penyebab:**

-   Password tidak sesuai format `dummy@[NIS]`
-   Ada typo saat mengetik
-   Password case-sensitive (huruf kecil semua)

**Solusi:**

-   Pastikan format password: `dummy@` + NIS
-   Contoh: jika NIS = `123456789`, maka password = `dummy@123456789`
-   Ketik manual atau copy dari dokumentasi ini

## 🔧 Cara Mengecek Data User di Database

Jika masih ada masalah, Anda bisa cek manual:

```bash
php artisan tinker
```

Lalu jalankan:

```php
// Cek jumlah siswa
App\Models\tb_siswa::count();

// Cek jumlah user siswa
App\Models\User::where('role', 'siswa')->count();

// Lihat data user siswa
App\Models\User::where('role', 'siswa')->get(['id', 'username', 'id_siswa']);
```

## 📝 Catatan Penting

1. **Import Excel** = Otomatis membuat:

    - Data di tabel `tb_siswa`
    - Data di tabel `tb_users` dengan username = NIS dan password = `dummy@[NIS]`

2. **Format Password Default:**

    - Huruf kecil semua: `dummy@`
    - Diikuti NIS tanpa spasi
    - Contoh lengkap: `dummy@543241165`

3. **Setelah Clone Repository:**
    - Jalankan `npm install`
    - Jalankan `composer install`
    - Copy `.env.example` ke `.env` (jika belum ada)
    - Jalankan `php artisan key:generate`
    - Setup database di `.env`
    - Jalankan `php artisan migrate`

## 💡 Tips

-   Jika lupa password siswa, bisa di-reset melalui halaman admin (fitur yang bisa ditambahkan)
-   Untuk keamanan lebih baik, siswa sebaiknya bisa ganti password setelah login pertama kali
-   Password default ini hanya untuk kemudahan testing dan onboarding awal
