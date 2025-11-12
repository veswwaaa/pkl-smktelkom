# Kredensial Default DUDI

## 🔐 Password Default

**Semua akun DUDI yang baru dibuat memiliki password default:**

```
dudi123
```

## 📝 Format Username

Username dibuat otomatis dari nama DUDI dengan aturan:

-   **Sama persis dengan nama DUDI** (boleh ada spasi)
-   Huruf **lowercase** (huruf kecil)
-   Jika nama DUDI sudah ada, akan ditambahkan angka di belakang dengan spasi

**Contoh:**

| Nama DUDI           | Username                                              |
| ------------------- | ----------------------------------------------------- |
| PT Telkom Indonesia | `pt telkom indonesia`                                 |
| PT Telkom Indonesia | `pt telkom indonesia 1` (jika yang pertama sudah ada) |
| CV Maju Jaya        | `cv maju jaya`                                        |
| UD Sumber Rejeki    | `ud sumber rejeki`                                    |

## 🔄 Cara Reset Password (Jika Lupa)

1. Login sebagai **Admin**
2. Buka menu **Kelola DUDI**
3. Cari DUDI yang ingin direset password
4. Klik tombol **"Reset Password"** (icon kunci)
5. Password baru akan ditampilkan di popup
6. **PENTING:** Catat password baru dan kirimkan ke DUDI

## ⚠️ Catatan Penting

-   Password default `dudi123` **hanya berlaku untuk akun baru**
-   Jika admin sudah melakukan **reset password**, password akan berubah sesuai yang di-generate sistem
-   Setelah login pertama kali, **DUDI disarankan mengganti password sendiri** (fitur akan ditambahkan)

## 📧 Template Mengirim Kredensial ke DUDI

```
Yth. [Nama DUDI],

Akun Anda untuk sistem PKL SMK Telkom Banjarbaru telah dibuat.

Kredensial Login:
• Username: [username]
• Password: dudi123

Link Login: [URL website]

Silakan login dan ganti password Anda setelah login pertama kali.

Terima kasih.

---
Admin PKL SMK Telkom Banjarbaru
```

## 🛡️ Keamanan

-   Password default memang sederhana agar mudah diingat
-   DUDI **wajib mengganti password** setelah login pertama (best practice)
-   Password di-hash dengan algoritma **bcrypt** di database (aman)

---

**Dibuat:** 11 November 2025  
**Update Terakhir:** 11 November 2025
