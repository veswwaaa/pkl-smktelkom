<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Siswa - SMK Telkom Banjarbaru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/kelola-dudi.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex align-items-center justify-content-between">
        <!-- Logo dan Brand -->
        <div class="telkom-logo">
            <img src="{{ asset('img/telkom-logo.png') }}" alt="Telkom Logo" height="40">
        </div>

        <!-- Right side -->
        <div class="navbar-right">
            <!-- Notification -->
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <button class="profile-dropdown" type="button" data-bs-toggle="dropdown">
                    <div class="profile-avatar">A</div>
                    <i class="fas fa-chevron-down text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="logout"><i
                                class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Left Sidebar -->
    <div class="left-sidebar">
        <div class="sidebar-menu">
            <a href="/dashboard" class="sidebar-item" title="Dashboard">
                <i class="fas fa-th-large"></i>
            </a>
            <a href="/admin/dudi" class="sidebar-item" title="Kelola DUDI">
                <i class="fas fa-building"></i>
            </a>
            <a href="/admin/siswa" class="sidebar-item active" title="Kelola Siswa">
                <i class="fas fa-users"></i>
            </a>
            <a href="/admin/pengajuan-pkl" class="sidebar-item" title="Pengajuan PKL">
                <i class="fas fa-clipboard-list"></i>
            </a>
            <a href="#" class="sidebar-item" title="Reports">
                <i class="fas fa-chart-bar"></i>
            </a>
            <a href="#" class="sidebar-item" title="Upload">
                <i class="fas fa-upload"></i>
            </a>
            <a href="#" class="sidebar-item" title="Share">
                <i class="fas fa-share-alt"></i>
            </a>
        </div>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <div>
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h1>Kelola Siswa</h1>
                    <p>Kelola data siswa PKL SMK Telkom Banjarbaru</p>
                </div>
            </div>
            <a href="/dashboard" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>


        {{-- <div class="container mt-4">
                {{-- headerrrrr --}}
        {{-- <div class="row mb-4" style="display: none;">
                    <div class="col-md-8">
                        <h2><i class="fas fa-users text-primary"></i>Kelola Siswa</h2>
                        <p class="text-muted">Manajemen data siswa PKL SMK Telkom</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="/dashboard" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div> --}}



        {{-- alert Messages - Hidden untuk SweetAlert --}}
        <div style="display: none;">
            @if (session('success'))
                <div id="success-message" data-message="{{ session('success') }}"></div>
            @endif

            @if (session('error'))
                <div id="error-message" data-message="{{ session('error') }}"></div>
            @endif

            @if ($errors->any())
                <div id="validation-errors" data-errors="{{ json_encode($errors->all()) }}"></div>
            @endif
        </div>

        {{-- add Button --}}
        <div class="data-table-card">
            <div class="table-header">
                <div class="table-header-content">
                    <div class="table-title">
                        <i class="fas fa-table"></i>
                        <h5>Daftar Siswa</h5>
                    </div>
                    <div>
                        <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addSiswaModal"
                            style="display: inline-block">
                            <i class="fas fa-plus"></i>
                            Tambah Siswa
                        </button>
                        <button class="excel-btn" data-bs-toggle="modal" data-bs-target="#importSiswaModal">
                            <i class="fas fa-file-excel"></i> Import Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- tabel siswa --}}
        <div class="table-container">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jenis Kelamin</th>
                        <th>Angkatan</th>
                        <th>Jurusan</th>
                        <th>Status PKL</th>
                        <th>DUDI</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $siswaItem)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-info">{{ $siswaItem->nis }}</span></td>
                            <td><strong>{{ $siswaItem->nama }}</strong></td>
                            <td>{{ $siswaItem->kelas }}</td>
                            <td>{{ $siswaItem->jenis_kelamin }}</td>
                            <td>{{ $siswaItem->angkatan }}</td>
                            <td><span class="badge bg-success">{{ $siswaItem->jurusan }}</span></td>
                            <td>
                                @if ($siswaItem->status_penempatan == 'ditempatkan')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Ditempatkan
                                    </span>
                                @elseif($siswaItem->status_penempatan == 'selesai')
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-flag-checkered"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock"></i> Belum
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($siswaItem->dudi)
                                    <small>{{ $siswaItem->dudi->nama_dudi }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($siswaItem->status_penempatan == 'belum')
                                    <button class="btn btn-success btn-sm" title="Tempatkan ke DUDI"
                                        onclick="openAssignModal({{ $siswaItem->id }}, '{{ $siswaItem->nama }}', '{{ $siswaItem->nis }}')">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </button>
                                @else
                                    <button class="btn btn-info btn-sm" title="Lihat Detail PKL"
                                        onclick="viewPklDetail({{ $siswaItem->id }}, '{{ $siswaItem->nama }}', '{{ $siswaItem->dudi ? $siswaItem->dudi->nama_dudi : '' }}', '{{ $siswaItem->tanggal_mulai_pkl }}', '{{ $siswaItem->tanggal_selesai_pkl }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" title="Set Tanggal PKL"
                                        onclick="openSetTanggalModal({{ $siswaItem->id }}, '{{ $siswaItem->nama }}', '{{ $siswaItem->tanggal_mulai_pkl }}', '{{ $siswaItem->tanggal_selesai_pkl }}')">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" title="Batalkan Penempatan"
                                        onclick="confirmCancelAssignment({{ $siswaItem->id }}, '{{ $siswaItem->nama }}')">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                @endif
                                <button class="btn btn-warning btn-sm" title="Edit"
                                    onclick="editSiswa({{ $siswaItem->id }},'{{ $siswaItem->nis }}', '{{ $siswaItem->nama }}','{{ $siswaItem->kelas }}','{{ $siswaItem->jenis_kelamin }}','{{ $siswaItem->angkatan }}','{{ $siswaItem->jurusan }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" title="Delete"
                                    onclick="confirmDelete({{ $siswaItem->id }}, '{{ $siswaItem->nama }}', '{{ $siswaItem->nis }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada data siswa</h5>
                                    <p class="text-muted">Klik "Tambah Siswa" untuk menambah data</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Add Siswa --}}
    <div class="modal fade" id="addSiswaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus"></i> Tambah Siswa Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="/admin/siswa" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nis" class="form-label">
                                        <i class="fas fa-id-card text-primary"></i> NIS
                                    </label>
                                    <input type="text" class="form-control" id="nis" name="nis"
                                        required placeholder="Masukkan NIS siswa">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">
                                        <i class="fas fa-user text-primary"></i> Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        required placeholder="Masukkan nama lengkap">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kelas" class="form-label">
                                        <i class="fas fa-school text-primary"></i> Kelas
                                    </label>
                                    <input type="text" class="form-control" id="kelas" name="kelas"
                                        required placeholder="Contoh: XII E">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_kelamin" class="form-label">
                                        <i class="fas fa-venus-mars text-primary"></i> Jenis Kelamin
                                    </label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="angkatan" class="form-label">
                                        <i class="fas fa-calendar text-primary"></i> Angkatan
                                    </label>
                                    <input type="text" class="form-control" id="angkatan" name="angkatan"
                                        required placeholder="Contoh: angkatan 26">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jurusan" class="form-label">
                                        <i class="fas fa-graduation-cap text-primary"></i> Jurusan
                                    </label>
                                    {{-- buat ngedit option jurusannya --}}
                                    <select class="form-select" id="jurusan" name="jurusan" required>
                                        <option value="">Pilih Jurusan</option>
                                        <option value="RPL">RPL (Rekayasa Perangkat Lunak)</option>
                                        <option value="TKJ">TKJ (Teknik Komputer & Jaringan)</option>
                                        <option value="TJKT">TJKT (Teknik Jaringan Komputer
                                            danTelekomunikasi)</option>
                                        <option value="DKV">DKV (Desain Komunikasi Visual)</option>
                                        <option value="ANM">ANM (Animasi)</option>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Info:</strong> Password akan otomatis di-generate dengan format:
                            <code>dummy@NIS</code>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Siswa --}}
    <div class="modal fade" id="editSiswaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i> Edit Data Siswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editSiswaForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nis" class="form-label">
                                        <i class="fas fa-id-card text-warning"></i> NIS
                                    </label>
                                    <input type="text" class="form-control" id="edit_nis" name="nis"
                                        required placeholder="Masukkan NIS siswa">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nama" class="form-label">
                                        <i class="fas fa-user text-warning"></i> Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control" id="edit_nama" name="nama"
                                        required placeholder="Masukkan nama lengkap">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_kelas" class="form-label">
                                        <i class="fas fa-school text-warning"></i> Kelas
                                    </label>
                                    <input type="text" class="form-control" id="edit_kelas" name="kelas"
                                        required placeholder="Contoh: XII RPL 1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_jenis_kelamin" class="form-label">
                                        <i class="fas fa-venus-mars text-warning"></i> Jenis Kelamin
                                    </label>
                                    <select class="form-select" id="edit_jenis_kelamin" name="jenis_kelamin"
                                        required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_angkatan" class="form-label">
                                        <i class="fas fa-calendar text-warning"></i> Angkatan
                                    </label>
                                    <input type="text" class="form-control" id="edit_angkatan" name="angkatan"
                                        required placeholder="Contoh: 2025">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_jurusan" class="form-label">
                                        <i class="fas fa-graduation-cap text-warning"></i> Jurusan
                                    </label>
                                    <select class="form-select" id="edit_jurusan" name="jurusan" required>
                                        <option value="">Pilih Jurusan</option>
                                        <option value="RPL">RPL (Rekayasa Perangkat Lunak)</option>
                                        <option value="TKJ">TKJ (Teknik Komputer & Jaringan)</option>
                                        <option value="TJKT">TJKT (Teknik Jaringan Komputer
                                            danTelekomunikasi)</option>
                                        <option value="DKV">DKV (Desain Komunikasi Visual)</option>
                                        <option value="ANM">ANM (Animasi)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Perhatian:</strong> Jika NIS diubah, username login siswa juga akan berubah
                            mengikuti NIS baru.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Import Excel --}}
    <div class="modal fade" id="importSiswaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-file-excel"></i> Import Data Siswa dari Excel
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="/admin/siswa/import" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">
                                <i class="fas fa-upload text-success"></i> Pilih File Excel
                            </label>
                            <input type="file" class="form-control" id="file" name="file"
                                accept=".xlsx,.xls,.csv" required>
                            <small class="text-muted">Format: .xlsx, .xls, atau .csv (Max: 2MB)</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Format Excel yang Diperlukan:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Baris pertama harus berisi header kolom</li>
                                <li>Kolom yang wajib ada:
                                    <ul>
                                        <li><code>nis</code> (9 digit angka)</li>
                                        <li><code>nama</code> (nama lengkap)</li>
                                        <li><code>kelas</code> (contoh: XII E)</li>
                                        <li><code>jenis_kelamin</code> (Laki-laki/Perempuan)</li>
                                        <li><code>angkatan</code> (tahun angkatan)</li>
                                        <li><code>jurusan</code> (RPL/TKJ/DKV/dll)</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Catatan:</strong>
                            <ul class="mb-0">
                                <li>Password otomatis: <code>dummy@NIS</code></li>
                                <li>Data dengan NIS yang sudah ada akan dilewati</li>
                                <li>Akun login siswa akan otomatis dibuat</li>
                            </ul>
                        </div>

                        <div class="alert alert-success">
                            <i class="fas fa-download"></i>
                            <strong>Download Template Excel:</strong><br>
                            <a href="#" class="btn btn-sm btn-outline-success mt-2">
                                <i class="fas fa-file-download"></i> Download Template
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload"></i> Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    {{-- Modal Assign Siswa ke DUDI --}}
    <div class="modal fade" id="assignDudiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-map-marker-alt"></i> Tempatkan Siswa ke DUDI
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="assignDudiForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-user"></i> <strong id="assignSiswaName"></strong>
                            (<span id="assignSiswaNis"></span>)
                        </div>

                        <div class="mb-3">
                            <label for="id_dudi" class="form-label">
                                <i class="fas fa-building text-success"></i> Pilih DUDI
                            </label>
                            <select class="form-select" id="id_dudi" name="id_dudi" required>
                                <option value="">-- Pilih DUDI --</option>
                                @foreach ($dudis as $dudi)
                                    <option value="{{ $dudi->id }}">{{ $dudi->nama_dudi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_mulai_pkl" class="form-label">
                                <i class="fas fa-calendar-alt text-success"></i> Tanggal Mulai PKL
                            </label>
                            <input type="date" class="form-control" id="tanggal_mulai_pkl"
                                name="tanggal_mulai_pkl" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_selesai_pkl" class="form-label">
                                <i class="fas fa-calendar-check text-success"></i> Tanggal Selesai PKL
                            </label>
                            <input type="date" class="form-control" id="tanggal_selesai_pkl"
                                name="tanggal_selesai_pkl" required>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i>
                            <small>Pastikan data DUDI dan tanggal PKL sudah benar sebelum menyimpan.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Tempatkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Detail PKL --}}
    <div class="modal fade" id="pklDetailModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle"></i> Detail Penempatan PKL
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%"><i class="fas fa-user text-info"></i> Nama Siswa</th>
                            <td id="detailNamaSiswa">-</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-building text-info"></i> DUDI</th>
                            <td id="detailNamaDudi">-</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-calendar-alt text-info"></i> Tanggal Mulai</th>
                            <td id="detailTanggalMulai">-</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-calendar-check text-info"></i> Tanggal Selesai</th>
                            <td id="detailTanggalSelesai">-</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Set Tanggal PKL --}}
    <div class="modal fade" id="setTanggalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-alt"></i> Set Tanggal PKL
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="setTanggalForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Atur tanggal mulai dan selesai PKL untuk: <strong id="setTanggalNamaSiswa"></strong>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_mulai_pkl" class="form-label">
                                <i class="fas fa-calendar-check text-success"></i> Tanggal Mulai PKL
                            </label>
                            <input type="date" class="form-control" id="tanggal_mulai_pkl"
                                name="tanggal_mulai_pkl" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_selesai_pkl" class="form-label">
                                <i class="fas fa-calendar-times text-danger"></i> Tanggal Selesai PKL
                            </label>
                            <input type="date" class="form-control" id="tanggal_selesai_pkl"
                                name="tanggal_selesai_pkl" required>
                        </div>

                        <div class="alert alert-warning">
                            <small>
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Catatan:</strong> Pastikan tanggal selesai lebih akhir dari tanggal mulai.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Tanggal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal import dari excel --}}




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/kelola-siswa.js') }}"></script>

    <!-- Hidden Delete Forms -->
    @foreach ($siswa as $siswaItem)
        <form id="delete-form-{{ $siswaItem->id }}" action="/admin/siswa/{{ $siswaItem->id }}" method="POST"
            style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
</body>

</html>
