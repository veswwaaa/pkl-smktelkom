# Alur Pengajuan PKL - DUDI Mandiri vs DUDI Sekolah

## 1. DUDI Pilihan Sekolah (DUDI yang sudah terdaftar di sistem)

```
[SISWA] → Submit Pengajuan PKL dengan DUDI dari list sekolah
    ↓
[ADMIN] → Melihat pengajuan → Approve → Pengajuan dikirim ke DUDI
    ↓
[DUDI]  → Login → Melihat lamaran → Approve/Reject
    ↓
[SISTEM] → Update status siswa (diterima/ditolak)
```

**Karakteristik:**

-   DUDI sudah punya akun login di sistem
-   Admin bisa langsung approve dan kirim ke DUDI
-   Proses lebih cepat dan otomatis

---

## 2. DUDI Mandiri (DUDI yang diajukan oleh siswa)

```
[SISWA] → Submit Pengajuan PKL dengan data DUDI baru (Mandiri)
         → Input: Nama Perusahaan, Alamat, PIC, Telepon, dll
    ↓
[ADMIN] → Melihat pengajuan (Status: DUDI Mandiri - Akun belum dibuat)
    ↓
[ADMIN] → Menghubungi DUDI secara manual (telepon/email ke PIC)
         → Mengirim surat permohonan (di luar sistem)
    ↓
[DUDI]  → Memberikan persetujuan (secara offline)
    ↓
[ADMIN] → Klik "Buat Akun DUDI & Approve" di sistem
         → Sistem otomatis:
            1. Membuat data DUDI di database
            2. Membuat akun login DUDI (username & password)
            3. Approve pengajuan PKL siswa
            4. Mengirim pengajuan ke akun DUDI yang baru dibuat
    ↓
[ADMIN] → Mengirimkan kredensial login ke DUDI (username & password)
         → Via email/WhatsApp/telepon
    ↓
[DUDI]  → Login dengan akun yang dibuat admin
         → Melihat lamaran PKL siswa
         → Approve/Reject lamaran
    ↓
[SISTEM] → Update status siswa (diterima/ditolak)
```

**Karakteristik:**

-   DUDI belum terdaftar di sistem, diajukan oleh siswa
-   Admin harus koordinasi manual terlebih dahulu dengan DUDI
-   Setelah DUDI setuju, admin buat akun dan approve sekaligus
-   Username: nama perusahaan (lowercase, tanpa spasi)
-   Password default: `dudi123`

---

## 3. Indikator di Halaman Admin

### Kolom "DUDI Tujuan" di Tabel Pengajuan PKL:

**DUDI Sekolah:**

```
PT Telkom Indonesia
Pilihan 1
```

**DUDI Mandiri - Belum Ada Akun:**

```
PT Maju Jaya Mandiri [Mandiri]
⚠️ Akun DUDI belum dibuat
Pilihan 1
```

**DUDI Mandiri - Sudah Ada Akun:**

```
PT Maju Jaya Mandiri [Mandiri]
✓ Akun sudah dibuat
Pilihan 1
```

### Action Buttons:

**DUDI Sekolah (atau DUDI Mandiri sudah punya akun):**

-   👁️ Detail
-   ✅ Approve & Kirim ke DUDI
-   ❌ Reject
-   🗑️ Hapus

**DUDI Mandiri - Belum Ada Akun:**

-   👁️ Detail
-   👤➕ **Buat Akun DUDI & Approve** ← Tombol khusus
-   ❌ Reject
-   🗑️ Hapus

---

## 4. Flow Chart Keputusan

```
Siswa Submit PKL
    |
    v
Apakah DUDI dari list sekolah?
    |
    +-- YA --> [Admin Approve] --> [Kirim ke DUDI] --> [DUDI Approve/Reject]
    |
    +-- TIDAK (DUDI Mandiri) --> [Admin lihat pengajuan]
                                     |
                                     v
                            [Admin hubungi DUDI secara manual]
                                     |
                                     v
                            Apakah DUDI setuju?
                                     |
                                     +-- YA --> [Admin klik "Buat Akun & Approve"]
                                     |             |
                                     |             v
                                     |          [Sistem buat akun DUDI]
                                     |             |
                                     |             v
                                     |          [Sistem approve pengajuan]
                                     |             |
                                     |             v
                                     |          [Admin kirim kredensial ke DUDI]
                                     |             |
                                     |             v
                                     |          [DUDI login & approve/reject]
                                     |
                                     +-- TIDAK --> [Admin reject pengajuan]
```

---

## 5. Keuntungan Alur Baru

✅ **Fleksibilitas**: Siswa bisa mengajukan DUDI di luar list sekolah
✅ **Kontrol Admin**: Admin tetap punya kontrol penuh sebelum approve
✅ **Efisiensi**: Setelah DUDI setuju, proses otomatis (buat akun + approve)
✅ **Transparansi**: Status DUDI Mandiri jelas terlihat di sistem
✅ **Tracking**: Semua aktivitas tercatat di activity log
✅ **Skalabilitas**: DUDI baru otomatis masuk ke database untuk pengajuan berikutnya

---

## 6. Catatan Penting

⚠️ **Untuk Admin:**

1. Pastikan menghubungi DUDI terlebih dahulu sebelum klik "Buat Akun"
2. Catat username dan password yang dihasilkan sistem
3. Kirimkan kredensial ke DUDI via komunikasi yang aman
4. Password default: `dudi123` (DUDI harus menggantinya setelah login pertama)

⚠️ **Untuk Siswa:**

1. Data DUDI Mandiri harus lengkap dan akurat
2. Pastikan Person-in-Charge (PIC) yang diinput bisa dihubungi
3. Proses DUDI Mandiri lebih lama karena perlu koordinasi manual
4. Sabar menunggu admin menghubungi DUDI yang diajukan

⚠️ **Untuk DUDI:**

1. Login pertama kali gunakan username dan password dari admin
2. Segera ganti password setelah login pertama
3. Cek pengajuan PKL dari siswa di menu "Lamaran PKL"
4. Approve/reject sesuai kuota dan ketersediaan

---

## 7. FAQ

**Q: Berapa lama proses DUDI Mandiri?**
A: Tergantung kecepatan admin menghubungi DUDI dan DUDI memberikan persetujuan. Bisa 1-3 hari kerja.

**Q: Apakah DUDI Mandiri bisa ditolak?**
A: Ya, jika DUDI tidak memberikan persetujuan, admin akan reject pengajuan PKL siswa.

**Q: Apakah siswa lain bisa mengajukan ke DUDI Mandiri yang sama?**
A: Ya! Setelah DUDI Mandiri diapprove dan punya akun, DUDI tersebut otomatis masuk ke list DUDI sekolah untuk siswa lain.

**Q: Bagaimana jika username DUDI Mandiri sudah ada?**
A: Sistem otomatis menambahkan angka di belakang (contoh: ptmajujaya, ptmajujaya1, ptmajujaya2)

**Q: Apakah password bisa diganti?**
A: Ya, DUDI bisa mengganti password setelah login. (Fitur change password akan ditambahkan)

---

Dibuat: 10 November 2025
Update Terakhir: 10 November 2025
