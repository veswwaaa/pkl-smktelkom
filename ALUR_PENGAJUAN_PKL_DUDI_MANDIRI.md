# Alur Pengajuan PKL - DUDI Mandiri

## 📋 Gambaran Umum

Dokumen ini menjelaskan alur lengkap pengajuan PKL untuk siswa yang memilih **DUDI Mandiri** (DUDI yang belum terdaftar di sistem sekolah).

---

## 🔄 Alur Proses

### 1️⃣ **Siswa Mengajukan PKL dengan DUDI Mandiri**

**Aksi:** Siswa

-   Siswa login ke sistem
-   Masuk ke halaman "Pengajuan PKL"
-   Di tab "Data Pengajuan DUDI Mandiri", siswa mengisi form:
    -   Nama DUDI
    -   Nomor Telepon
    -   Person in Charge (PIC)
    -   Alamat
-   Klik "Simpan" untuk menyimpan DUDI Mandiri
-   Kemudian di tab "Data Pengajuan PKL", siswa memilih DUDI Mandiri yang baru dibuat sebagai salah satu dari 3 pilihan
-   Klik "Kirim Pengajuan"

**Status:** Pengajuan PKL dengan status `pending`

---

### 2️⃣ **Admin Melihat Pengajuan**

**Aksi:** Admin

-   Admin login dan masuk ke "Kelola Pengajuan PKL"
-   Admin melihat pengajuan dari siswa
-   Kolom "Pilihan Aktif" menampilkan:
    -   Nama DUDI Mandiri
    -   Badge "Mandiri" (warna kuning)
    -   Pesan: ⚠️ "Akun DUDI belum dibuat"

**Status:** Pengajuan masih `pending`

---

### 3️⃣ **Admin Membuat Akun DUDI**

**Aksi:** Admin

-   Klik tombol **"Buat Akun"** (ikon user-plus, warna biru)
-   Sistem menampilkan konfirmasi:

    ```
    Yang akan dilakukan:
    ✓ Membuat data DUDI di sistem
    ✓ Membuat akun login untuk DUDI
    ✓ Status pengajuan diubah ke "Diproses"

    PENTING: Siswa BELUM langsung diterima!
    ```

-   Admin klik "Ya, Buat Akun DUDI"
-   Sistem membuat:
    -   Data DUDI di tabel `tb_dudi` dengan `jenis_dudi = 'mandiri'`
    -   Akun User dengan role `dudi`
    -   Username dan password default (ditampilkan di alert)
-   Admin menyimpan kredensial dan mengirimkannya ke DUDI

**Status:** Pengajuan berubah menjadi `diproses`

**Output:**

```
✅ SIMPAN KREDENSIAL INI!
Username: [nama dudi]
Password: dudi123

Langkah selanjutnya:
1. Kirim surat pengajuan PKL + CV & portofolio siswa ke DUDI
2. Tunggu surat balasan dari DUDI
3. Jika DUDI menyetujui, klik tombol "Approve"
4. Jika DUDI menolak, klik tombol "Tolak"
```

---

### 4️⃣ **Admin Mengirim Surat Pengajuan ke DUDI**

**Aksi:** Admin (Manual - di luar sistem)

-   Admin mencetak/membuat surat pengajuan PKL
-   Melampirkan:
    -   CV siswa
    -   Portofolio siswa
    -   Surat pengantar dari sekolah
-   Mengirim surat ke DUDI via:
    -   Email
    -   Pos
    -   Langsung ke kantor DUDI

**Status:** Pengajuan tetap `diproses` (menunggu balasan)

---

### 5️⃣ **DUDI Memproses Surat**

**Aksi:** DUDI (Manual - di luar sistem)

-   DUDI menerima surat pengajuan PKL
-   DUDI mengevaluasi:
    -   Kebutuhan tenaga magang
    -   Kualifikasi siswa dari CV & portofolio
    -   Ketersediaan tempat
-   DUDI membuat surat balasan:
    -   ✅ **Menerima** siswa PKL, atau
    -   ❌ **Menolak** dengan alasan

**Status:** Pengajuan tetap `diproses`

---

### 6️⃣ **Admin Menerima Balasan dan Update Status**

**Aksi:** Admin

#### Jika DUDI **MENYETUJUI** (Surat Balasan: Diterima)

-   Admin klik tombol **"Approve"** (ikon check-circle, warna hijau)
-   Sistem menampilkan konfirmasi:

    ```
    Yang akan dilakukan:
    ✓ Siswa akan ditempatkan ke DUDI
    ✓ Pengajuan akan dikirim ke akun DUDI
    ✓ DUDI dapat melihat detail siswa

    Catatan: Pastikan sudah menerima surat balasan persetujuan!
    ```

-   Admin klik "Ya, Approve!"
-   Sistem memproses:
    -   Status pengajuan → `approved`
    -   Siswa ditempatkan ke DUDI (`id_dudi` di tabel siswa diisi)
    -   Status penempatan siswa → `ditempatkan`
    -   Pengajuan muncul di akun DUDI untuk approval akhir

**Status Final:** `approved` + siswa ditempatkan

#### Jika DUDI **MENOLAK** (Surat Balasan: Ditolak)

-   Admin klik tombol **"Tolak"** (ikon times-circle, warna kuning)
-   Sistem menampilkan form catatan untuk mengisi alasan penolakan
-   Admin klik "Ya, Tolak!"
-   Sistem memproses:
    -   Status pilihan yang aktif → `rejected`
    -   Jika ada pilihan lain, sistem cascade ke pilihan berikutnya
    -   Jika tidak ada pilihan lain, pengajuan → `rejected`

**Status Final:** `rejected` atau cascade ke pilihan berikutnya

---

### 7️⃣ **DUDI Login dan Melihat Pengajuan**

**Aksi:** DUDI

-   DUDI login menggunakan kredensial yang diberikan admin
-   Masuk ke halaman "Lamaran PKL"
-   Melihat daftar siswa yang diajukan ke DUDI mereka
-   DUDI dapat:
    -   Melihat detail siswa
    -   Memberikan approval akhir
    -   Menolak jika ada masalah

---

## 📊 Diagram Alur

```
Siswa Mengajukan PKL (DUDI Mandiri)
            ↓
    Status: PENDING
            ↓
Admin Klik "Buat Akun DUDI"
            ↓
    Status: DIPROSES
            ↓
Admin Kirim Surat + CV/Portofolio ke DUDI
            ↓
DUDI Evaluasi & Kirim Surat Balasan
            ↓
    ┌───────────────────┐
    ↓                   ↓
DITERIMA           DITOLAK
    ↓                   ↓
Admin Klik      Admin Klik
"Approve"          "Tolak"
    ↓                   ↓
Status:            Status:
APPROVED          REJECTED
    ↓                   ↓
Siswa            Cascade ke
Ditempatkan      Pilihan Lain
```

---

## 🔑 Poin Penting

### ⚠️ Perbedaan dengan Alur Lama

**SEBELUM:**

-   Klik "Buat Akun DUDI" → Siswa langsung di-approve dan ditempatkan

**SEKARANG:**

-   Klik "Buat Akun DUDI" → Status jadi "Diproses"
-   Admin kirim surat ke DUDI (manual)
-   Tunggu balasan DUDI
-   Baru admin klik "Approve" atau "Tolak"

### ✅ Keuntungan Alur Baru

1. **Profesional**: Mengikuti prosedur formal dengan surat menyurat
2. **Dokumentasi**: Ada bukti tertulis persetujuan DUDI
3. **Fleksibel**: DUDI bisa menolak jika tidak sanggup menerima
4. **Transparan**: Proses jelas dan terukur

### 🎯 Status Pengajuan PKL

| Status     | Deskripsi                                      | Action Available                                |
| ---------- | ---------------------------------------------- | ----------------------------------------------- |
| `pending`  | Baru diajukan, belum diproses                  | Buat Akun DUDI (jika mandiri) / Approve / Tolak |
| `diproses` | Akun DUDI sudah dibuat, menunggu balasan surat | Approve / Tolak                                 |
| `approved` | Disetujui, siswa sudah ditempatkan             | View Detail / Hapus                             |
| `rejected` | Ditolak                                        | View Detail / Hapus                             |

---

## 📝 Catatan untuk Admin

1. **Sebelum membuat akun DUDI**, pastikan:

    - Data DUDI lengkap dan benar
    - Nomor telepon DUDI valid untuk pengiriman kredensial

2. **Setelah membuat akun DUDI**, segera:

    - Simpan kredensial (username & password)
    - Kirim kredensial ke DUDI via WhatsApp/Email/Telepon
    - Siapkan surat pengajuan PKL

3. **Isi surat pengajuan harus mencakup:**

    - Data lengkap siswa (nama, NIS, kelas, jurusan)
    - Periode PKL (tanggal mulai dan selesai)
    - CV siswa
    - Portofolio/sertifikat siswa
    - Permintaan persetujuan

4. **Menunggu balasan DUDI:**

    - Hubungi DUDI jika belum ada balasan dalam 3-5 hari kerja
    - Pastikan surat diterima oleh pihak yang berwenang

5. **Jangan klik "Approve"** sebelum:
    - Menerima surat balasan resmi dari DUDI
    - Memastikan DUDI benar-benar menyetujui

---

## 🆘 Troubleshooting

### Q: Bagaimana jika DUDI tidak membalas surat?

**A:** Hubungi DUDI via telepon/email. Jika tetap tidak ada respon setelah follow-up 2-3 kali, klik "Tolak" dan siswa akan cascade ke pilihan DUDI berikutnya.

### Q: Bagaimana jika admin salah membuat akun DUDI?

**A:**

1. Jangan approve pengajuannya
2. Klik "Tolak" untuk cascade ke pilihan lain
3. Hapus data DUDI dari halaman "Kelola DUDI" jika diperlukan

### Q: Bisa tidak langsung approve tanpa surat?

**A:** Secara teknis bisa, tapi **tidak disarankan**. Alur surat menyurat penting untuk:

-   Dokumentasi formal
-   Bukti legal
-   Profesionalisme sekolah

### Q: Bagaimana jika DUDI mandiri sudah pernah dibuat sebelumnya?

**A:** Sistem akan otomatis detect. Jika DUDI mandiri dengan nama sama sudah punya akun, tidak akan muncul tombol "Buat Akun" melainkan langsung tombol "Approve/Tolak".

---

## 📞 Kontak Support

Jika ada kendala teknis atau pertanyaan lebih lanjut, hubungi:

-   Admin Sistem: [email/telepon]
-   Developer: [email/telepon]

---

**Terakhir diperbarui:** 14 November 2025
**Versi:** 2.0 (Alur dengan Surat Menyurat)
