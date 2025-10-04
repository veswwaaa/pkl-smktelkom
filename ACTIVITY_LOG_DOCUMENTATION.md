# 📋 Activity Log System - Dokumentasi

## 🎯 Overview

Activity Log System adalah fitur untuk tracking semua aktivitas yang terjadi di website PKL SMK Telkom Banjarbaru. Sistem ini mencatat aktivitas dari Admin, DUDI, dan Siswa secara otomatis.

---

## 📁 File Structure

```
app/
├── Console/
│   └── Commands/
│       └── AddActivity.php          # Artisan command untuk manual add
├── Helpers/
│   └── ActivityHelper.php           # Helper functions untuk logging
├── Http/
│   └── Controllers/
│       ├── AuthenController.php     # Login, Register, Logout logging
│       └── DudiController.php       # CRUD DUDI logging
└── Models/
    └── Activity.php                 # Model Activity

database/
└── migrations/
    └── 2025_10_02_080732_create_activities_table.php

resources/
└── views/
    └── dashboardAdmin.blade.php     # Display activities

public/
└── css/
    └── dashboard-admin.css          # Styling untuk activity display
```

---

## 🗄️ Database Structure

**Tabel: `activities`**

| Field       | Type                       | Description                                                            |
| ----------- | -------------------------- | ---------------------------------------------------------------------- |
| id          | bigint unsigned            | Primary key                                                            |
| user_id     | bigint unsigned (nullable) | Foreign key ke tb_users                                                |
| username    | varchar(255)               | Nama user yang melakukan aktivitas                                     |
| type        | varchar(255)               | Tipe aktivitas (login, create, update, delete, info, warning, success) |
| title       | varchar(255)               | Judul aktivitas                                                        |
| description | text                       | Deskripsi detail aktivitas                                             |
| created_at  | timestamp                  | Waktu aktivitas dibuat                                                 |
| updated_at  | timestamp                  | Waktu aktivitas diupdate                                               |

---

## 🔧 Cara Menggunakan

### 1. **Automatic Logging (Sudah Terintegrasi)**

Activity log akan otomatis tercatat saat:

#### **Authentication Activities:**

-   ✅ User Login (Admin/DUDI/Siswa)
-   ✅ User Logout
-   ✅ Registrasi Siswa baru
-   ✅ Registrasi DUDI baru
-   ✅ Registrasi Admin baru

#### **CRUD DUDI Activities:**

-   ✅ Tambah DUDI baru
-   ✅ Update data DUDI
-   ✅ Hapus DUDI

### 2. **Manual Logging via Helper Function**

Gunakan helper function `logActivity()` di controller:

```php
logActivity(
    'create',                           // type
    'Siswa Baru Ditambahkan',          // title
    'Siswa John Doe berhasil ditambah', // description
    $userId                             // optional user_id
);
```

**Parameter:**

-   `$type` (string): Type aktivitas

    -   `login` - User login
    -   `create` - Create/tambah data baru
    -   `update` - Update/edit data
    -   `delete` - Hapus data
    -   `info` - Informasi umum
    -   `warning` - Peringatan
    -   `success` - Success message

-   `$title` (string): Judul singkat aktivitas
-   `$description` (string): Deskripsi lengkap
-   `$userId` (int|null): ID user (opsional, auto-detect dari session)

### 3. **Manual Logging via Artisan Command**

```bash
php artisan add:activity "Judul" "Deskripsi" "type"
```

**Contoh:**

```bash
# Info activity
php artisan add:activity "Maintenance" "Server dalam maintenance" "info"

# Warning activity
php artisan add:activity "Alert" "Disk space hampir penuh" "warning"

# Success activity
php artisan add:activity "Backup" "Backup database berhasil" "success"
```

**Valid Types:**

-   `login`
-   `create`
-   `update`
-   `delete`
-   `info`
-   `warning`
-   `success`

---

## 📊 Display Activities

### Di Dashboard Admin

Activities ditampilkan di section "Aktivitas Terkini" dengan:

-   ✅ Maksimal 10 aktivitas terbaru
-   ✅ Hanya menampilkan aktivitas hari ini
-   ✅ Newest on top (terbaru di atas)
-   ✅ Color-coded berdasarkan type:
    -   🔵 Blue: login, info
    -   🟢 Green: create, success
    -   🟠 Orange: update, warning
    -   🔴 Red: delete
    -   ⚪ Gray: default/empty

### Data yang ditampilkan:

-   Title aktivitas
-   Deskripsi
-   Username yang melakukan
-   Waktu relatif (e.g., "5 minutes ago")

---

## 🔍 Helper Functions

### `logActivity($type, $title, $description, $userId = null)`

Mencatat aktivitas baru.

**Return:** Activity model instance

### `getRecentActivities($limit = 10)`

Mengambil aktivitas terbaru hari ini.

**Parameters:**

-   `$limit` (int): Jumlah aktivitas (default: 10)

**Return:** Collection of Activity models

---

## 📝 Model Scopes

### `Activity::today()`

Filter aktivitas hari ini saja.

```php
$todayActivities = Activity::today()->get();
```

### `Activity::latest10()`

Ambil 10 aktivitas terbaru.

```php
$latestActivities = Activity::latest10()->get();
```

---

## 🎨 Customization

### Menambahkan Type Baru

1. **Update validation di Command:**

```php
// app/Console/Commands/AddActivity.php
$validTypes = ['login', 'create', 'update', 'delete', 'info', 'warning', 'success', 'custom'];
```

2. **Tambahkan color di Blade:**

```blade
@php
    $dotColor = match($activity->type) {
        'login' => 'blue',
        'create' => 'green',
        'custom' => 'purple',  // Custom type
        default => 'gray'
    };
@endphp
```

3. **Tambahkan CSS color:**

```css
.activity-dot.purple {
    background: #9c27b0;
}
```

### Mengubah Limit Display

Edit di `AuthenController.php`:

```php
$activities = getRecentActivities(20); // Ubah dari 10 ke 20
```

### Menampilkan Activities di Dashboard Lain

Di controller dashboard siswa/dudi:

```php
public function dashboardSiswa()
{
    $activities = getRecentActivities(5); // Siswa lihat 5 terbaru
    return view('dashboardSiswa', compact('activities'));
}
```

---

## 🔒 Security & Best Practices

### ✅ Sudah Diimplementasikan:

-   User ID nullable (untuk system activities)
-   Auto-detect user dari session
-   Soft relationship (tidak strict foreign key)
-   Timestamp otomatis

### 🚨 Recommendations:

-   Pertimbangkan archival untuk activities > 30 hari
-   Add index untuk query performa:
    ```php
    $table->index(['created_at', 'type']);
    ```
-   Implementasi role-based filtering (admin bisa lihat semua, user hanya miliknya)

---

## 📈 Future Enhancements

### Potential Features:

1. **Filtering:**

    - Filter by type
    - Filter by date range
    - Filter by user

2. **Export:**

    - Export activities to PDF
    - Export to Excel/CSV

3. **Notifications:**

    - Real-time notifications untuk activities penting
    - Email digest harian

4. **Analytics:**

    - Activity statistics dashboard
    - User activity graphs
    - Peak usage times

5. **Advanced Logging:**
    - IP Address tracking
    - Browser/Device info
    - Before/After data comparison (untuk update)

---

## 🧪 Testing

### Test Command:

```bash
# Test info
php artisan add:activity "Test" "Testing activity log" "info"

# Test warning
php artisan add:activity "Alert" "Test warning" "warning"

# Test dengan type invalid (harus error)
php artisan add:activity "Test" "Invalid type" "invalid"
```

### Test Helper Function:

```php
// Di tinker atau controller test
logActivity('create', 'Test', 'Test description');
```

### Test Display:

1. Login sebagai Admin
2. Buka dashboard
3. Lihat section "Aktivitas Terkini"
4. Activities harus tampil dengan color yang sesuai

---

## 🐛 Troubleshooting

### Activities tidak muncul di dashboard:

-   ✅ Pastikan login sebagai Admin
-   ✅ Cek apakah ada activities hari ini
-   ✅ Clear cache: `php artisan cache:clear`
-   ✅ Restart server

### Helper function tidak ditemukan:

```bash
composer dump-autoload
```

### Migration error:

```bash
php artisan migrate:rollback
php artisan migrate
```

---

## 📞 Support

Jika ada pertanyaan atau issue, silakan check:

1. Error logs: `storage/logs/laravel.log`
2. Database: Pastikan tabel `activities` exist
3. Composer autoload: Run `composer dump-autoload`

---

## ✅ Checklist Implementasi

-   [x] Create migration
-   [x] Create model
-   [x] Create helper functions
-   [x] Create artisan command
-   [x] Autoload helper
-   [x] Integrate to AuthenController
-   [x] Integrate to DudiController
-   [x] Update dashboard view
-   [x] Add CSS styling
-   [x] Test command
-   [x] Test display
-   [ ] Test with real user login
-   [ ] Add to SiswaController (when created)

---

**Version:** 1.0.0  
**Last Updated:** October 2, 2025  
**Author:** PKL SMK Telkom Development Team
