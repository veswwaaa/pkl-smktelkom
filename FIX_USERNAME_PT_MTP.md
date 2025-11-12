# Fix Username PT MTP

## 🔍 Masalah

Username "pt mtp" tidak bisa login karena **username di database adalah "ptmtp"** (tanpa spasi).

## 📋 Detail

**Username di Database:**

```json
{
    "id": 23,
    "username": "ptmtp",
    "id_dudi": 10
}
```

**DUDI:**

-   Nama DUDI: "pt mtp" (dengan spasi)
-   ID DUDI: 10
-   Username akun: "ptmtp" (tanpa spasi)

## 🕐 Timeline

Username ini dibuat **sebelum** perubahan format username yang mengizinkan spasi.

**Perubahan Format Username:**

-   **Dulu:** `preg_replace('/\s+/', '', strtolower($nama))` → hapus semua spasi
-   **Sekarang:** `strtolower(trim($nama))` → lowercase, boleh spasi

## ✅ Solusi

### Opsi 1: Login dengan Username Tanpa Spasi (Recommended)

```
Username: ptmtp
Password: dudi123
```

### Opsi 2: Update Username di Database

Jalankan query berikut untuk mengupdate username:

```sql
UPDATE tb_users
SET username = 'pt mtp'
WHERE id = 23;
```

Atau via PHP Artisan Tinker:

```php
php artisan tinker --execute="DB::table('tb_users')->where('id', 23)->update(['username' => 'pt mtp']); echo 'Username updated!';"
```

Setelah itu, DUDI bisa login dengan:

```
Username: pt mtp
Password: dudi123
```

## 🔐 Password

Password untuk semua DUDI yang baru dibuat adalah: **`dudi123`**

Jika password tidak cocok, kemungkinan password sudah direset oleh admin. Gunakan fitur "Reset Password" di halaman admin kelola DUDI.

## 📌 Catatan

Akun DUDI dengan username lama (sebelum 11 Nov 2025) mungkin masih menggunakan format tanpa spasi. Untuk konsistensi, bisa dijalankan script update username massal jika diperlukan.

---

**Dibuat:** 11 November 2025
