<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi PKL - SMK Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard-siswa-new.css') }}">
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="telkom-logo">
            <img src="{{ asset('img/telkom-logo.png') }}" alt="Telkom Schools" onerror="this.style.display='none'">
            <h5>Telkom Schools</h5>
        </div>

        <div class="navbar-right">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
            </button>
            <div class="dropdown">
                <div class="user-avatar" data-bs-toggle="dropdown">
                    {{ substr($siswa->nama, 0, 1) }}
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header">{{ $siswa->nama }}</h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                    </li>
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
            <a href="/siswa/status" class="sidebar-item" title="Status & Info Siswa">
                <i class="fas fa-user-circle"></i>
            </a>
            <a href="/siswa/pengajuan-pkl" class="sidebar-item" title="Pengajuan PKL">
                <i class="fas fa-file-alt"></i>
            </a>
            <a href="/siswa/status-pengajuan" class="sidebar-item" title="Status Pengajuan PKL">
                <i class="fas fa-tasks"></i>
            </a>
            <a href="/siswa/info-pkl" class="sidebar-item active" title="Info PKL">
                <i class="fas fa-info-circle"></i>
            </a>
            <a href="/siswa/upload-dokumen" class="sidebar-item" title="Upload Dokumen">
                <i class="fas fa-upload"></i>
            </a>
            <a href="#" class="sidebar-item" title="Download Surat" onclick="alert('Fitur dalam pengembangan')">
                <i class="fas fa-file-download"></i>
            </a>
            <a href="/logout" class="sidebar-item" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="fas fa-briefcase me-2 text-success"></i>Informasi PKL</h2>
                <p class="text-muted">Detail tempat dan jadwal Praktik Kerja Lapangan Anda</p>
            </div>
            <div class="text-end">
                <small class="text-muted">NIS: {{ $siswa->nis }}</small><br>
                <small class="text-muted">{{ $siswa->nama }}</small>
            </div>
        </div>

        <!-- Status Card -->
        <div class="card border-success mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    Status:
                    @if ($siswa->status_penempatan == 'ditempatkan')
                        Sedang PKL
                    @else
                        PKL Selesai
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tempat PKL</label>
                            <h5 class="text-dark">
                                <i class="fas fa-building text-primary me-2"></i>
                                {{ $siswa->dudi ? $siswa->dudi->nama_dudi : '-' }}
                            </h5>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted mb-1">Alamat</label>
                            <p class="text-dark">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                {{ $siswa->dudi ? $siswa->dudi->alamat : '-' }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted mb-1">Bidang Usaha</label>
                            <p class="text-dark">
                                <i class="fas fa-industry text-info me-2"></i>
                                {{ $siswa->dudi ? $siswa->dudi->bidang_usaha : '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tanggal Mulai PKL</label>
                            <h5 class="text-dark">
                                <i class="fas fa-calendar-check text-success me-2"></i>
                                @if ($siswa->tanggal_mulai_pkl)
                                    {{ \Carbon\Carbon::parse($siswa->tanggal_mulai_pkl)->format('d F Y') }}
                                @else
                                    <span class="text-warning">Belum ditentukan</span>
                                @endif
                            </h5>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tanggal Selesai PKL</label>
                            <h5 class="text-dark">
                                <i class="fas fa-calendar-times text-danger me-2"></i>
                                @if ($siswa->tanggal_selesai_pkl)
                                    {{ \Carbon\Carbon::parse($siswa->tanggal_selesai_pkl)->format('d F Y') }}
                                @else
                                    <span class="text-warning">Belum ditentukan</span>
                                @endif
                            </h5>
                        </div>
                        @if ($siswa->tanggal_mulai_pkl && $siswa->tanggal_selesai_pkl)
                            <div class="mb-3">
                                <label class="text-muted mb-1">Durasi PKL</label>
                                <p class="text-dark">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    {{ \Carbon\Carbon::parse($siswa->tanggal_mulai_pkl)->diffInDays(\Carbon\Carbon::parse($siswa->tanggal_selesai_pkl)) }}
                                    hari
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                @if (!$siswa->tanggal_mulai_pkl || !$siswa->tanggal_selesai_pkl)
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Tanggal PKL akan diatur oleh admin atau DUDI tempat Anda
                        PKL.
                        Silakan menunggu atau hubungi admin/DUDI untuk informasi lebih lanjut.
                    </div>
                @endif
            </div>
        </div>

        <!-- Contact Info -->
        @if ($siswa->dudi)
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-address-book me-2"></i>Kontak DUDI</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Email</label>
                            <p class="text-dark">
                                <i class="fas fa-envelope me-2"></i>
                                {{ $siswa->dudi->email ?? '-' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Telepon</label>
                            <p class="text-dark">
                                <i class="fas fa-phone me-2"></i>
                                {{ $siswa->dudi->telepon ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="alert alert-light">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        <strong>Tips:</strong> Simpan kontak DUDI Anda untuk keperluan komunikasi selama PKL.
                    </div>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-4">
            <a href="/dashboard" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
