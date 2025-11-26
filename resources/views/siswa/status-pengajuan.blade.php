<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengajuan PKL - SMK Telkom</title>
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
            <button class="notification-btn" onclick="window.location.href='/siswa/pengajuan-pkl'">
                <i class="fas fa-bell"></i>
                @if ($pengajuan && $pengajuan->status == 'pending')
                    <span class="notification-badge">1</span>
                @endif
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
            <a href="/siswa/status-pengajuan" class="sidebar-item active" title="Status Pengajuan PKL">
                <i class="fas fa-tasks"></i>
            </a>
            <a href="/siswa/info-pkl" class="sidebar-item" title="Info PKL">
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
        <div class="row mb-4">
            <div class="col-12">
                <h2><i class="fas fa-tasks me-2 text-primary"></i>Status Pengajuan PKL</h2>
                <p class="text-muted">Lihat status pengajuan PKL Anda untuk setiap pilihan DUDI</p>
            </div>
        </div>

        @if ($pengajuan)
            <!-- Status Umum -->
            <div class="card mb-4">
                <div
                    class="card-header 
                    @if ($pengajuan->status == 'approved') bg-success
                    @elseif($pengajuan->status == 'rejected') bg-danger
                    @else bg-warning @endif text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Status Pengajuan:
                        <strong>{{ ucfirst($pengajuan->status) }}</strong>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tanggal Pengajuan:</strong></p>
                            <p>{{ $pengajuan->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d F Y H:i') : '-' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Pilihan Aktif:</strong></p>
                            @if ($pengajuan->pilihan_aktif == 'SMK Telkom Banjarbaru')
                                <p>
                                    <span class="badge bg-info fs-6">
                                        <i class="fas fa-school me-1"></i>PKL di Sekolah - SMK Telkom Banjarbaru
                                    </span>
                                </p>
                            @else
                                <p>Pilihan {{ $pengajuan->pilihan_aktif ?? '-' }}</p>
                            @endif
                        </div>
                    </div>
                    @if ($pengajuan->pilihan_aktif == 'SMK Telkom Banjarbaru')
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi PKL di Sekolah:</strong> Anda ditempatkan untuk melaksanakan PKL di SMK
                            Telkom Banjarbaru berdasarkan keputusan kurikulum.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pilihan 1 -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Pilihan 1</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>DUDI:</strong></p>
                            <h6>
                                @if ($pengajuan->id_dudi_pilihan_1)
                                    {{ $pengajuan->dudiPilihan1->nama_dudi ?? '-' }}
                                @elseif($pengajuan->id_dudi_mandiri_pilihan_1)
                                    {{ $pengajuan->dudiMandiriPilihan1->nama_dudi ?? '-' }}
                                    <span class="badge bg-info">Mandiri</span>
                                @else
                                    -
                                @endif
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong></p>
                            @php
                                $isPklDiSekolah = $pengajuan->pilihan_aktif == 'SMK Telkom Banjarbaru';
                                $isPilihan1Inactive = $isPklDiSekolah;
                            @endphp

                            @if ($isPilihan1Inactive)
                                <span class="badge bg-secondary fs-6">
                                    <i class="fas fa-ban me-1"></i>Tidak Aktif
                                </span>
                            @elseif ($pengajuan->status_pilihan_1 == 'approved')
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Diterima
                                </span>
                            @elseif($pengajuan->status_pilihan_1 == 'rejected')
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-times-circle me-1"></i>Ditolak
                                </span>
                            @else
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-clock me-1"></i>Menunggu
                                </span>
                            @endif
                        </div>
                    </div>
                    @if ($pengajuan->tanggal_response_pilihan_1)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <p><strong>Tanggal Response:</strong></p>
                                <p>{{ \Carbon\Carbon::parse($pengajuan->tanggal_response_pilihan_1)->format('d F Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif
                    @if ($pengajuan->catatan_pilihan_1)
                        <div class="alert alert-info mt-3">
                            <strong>Catatan:</strong> {{ $pengajuan->catatan_pilihan_1 }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pilihan 2 -->
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Pilihan 2</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>DUDI:</strong></p>
                            <h6>
                                @if ($pengajuan->id_dudi_pilihan_2)
                                    {{ $pengajuan->dudiPilihan2->nama_dudi ?? '-' }}
                                @elseif($pengajuan->id_dudi_mandiri_pilihan_2)
                                    {{ $pengajuan->dudiMandiriPilihan2->nama_dudi ?? '-' }}
                                    <span class="badge bg-info">Mandiri</span>
                                @else
                                    -
                                @endif
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong></p>
                            @php
                                $isPklDiSekolah = $pengajuan->pilihan_aktif == 'SMK Telkom Banjarbaru';
                                $isPilihan1Approved = $pengajuan->status_pilihan_1 == 'approved';
                                $isPilihan2Inactive = $isPklDiSekolah || $isPilihan1Approved;
                            @endphp

                            @if ($isPilihan2Inactive)
                                <span class="badge bg-secondary fs-6">
                                    <i class="fas fa-ban me-1"></i>Tidak Aktif
                                </span>
                            @elseif ($pengajuan->status_pilihan_2 == 'approved')
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Diterima
                                </span>
                            @elseif($pengajuan->status_pilihan_2 == 'rejected')
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-times-circle me-1"></i>Ditolak
                                </span>
                            @else
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-clock me-1"></i>Menunggu
                                </span>
                            @endif
                        </div>
                    </div>
                    @if ($pengajuan->tanggal_response_pilihan_2)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <p><strong>Tanggal Response:</strong></p>
                                <p>{{ \Carbon\Carbon::parse($pengajuan->tanggal_response_pilihan_2)->format('d F Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif
                    @if ($pengajuan->catatan_pilihan_2)
                        <div class="alert alert-info mt-3">
                            <strong>Catatan:</strong> {{ $pengajuan->catatan_pilihan_2 }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pilihan 3 -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Pilihan 3</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>DUDI:</strong></p>
                            <h6>
                                @if ($pengajuan->id_dudi_pilihan_3)
                                    {{ $pengajuan->dudiPilihan3->nama_dudi ?? '-' }}
                                @elseif($pengajuan->id_dudi_mandiri_pilihan_3)
                                    {{ $pengajuan->dudiMandiriPilihan3->nama_dudi ?? '-' }}
                                    <span class="badge bg-info">Mandiri</span>
                                @else
                                    -
                                @endif
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong></p>
                            @php
                                $isPklDiSekolah = $pengajuan->pilihan_aktif == 'SMK Telkom Banjarbaru';
                                $isPilihan1Approved = $pengajuan->status_pilihan_1 == 'approved';
                                $isPilihan2Approved = $pengajuan->status_pilihan_2 == 'approved';
                                $isPilihan3Inactive = $isPklDiSekolah || $isPilihan1Approved || $isPilihan2Approved;
                            @endphp

                            @if ($isPilihan3Inactive)
                                <span class="badge bg-secondary fs-6">
                                    <i class="fas fa-ban me-1"></i>Tidak Aktif
                                </span>
                            @elseif ($pengajuan->status_pilihan_3 == 'approved')
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Diterima
                                </span>
                            @elseif($pengajuan->status_pilihan_3 == 'rejected')
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-times-circle me-1"></i>Ditolak
                                </span>
                            @else
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-clock me-1"></i>Menunggu
                                </span>
                            @endif
                        </div>
                    </div>
                    @if ($pengajuan->tanggal_response_pilihan_3)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <p><strong>Tanggal Response:</strong></p>
                                <p>{{ \Carbon\Carbon::parse($pengajuan->tanggal_response_pilihan_3)->format('d F Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif
                    @if ($pengajuan->catatan_pilihan_3)
                        <div class="alert alert-info mt-3">
                            <strong>Catatan:</strong> {{ $pengajuan->catatan_pilihan_3 }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum Ada Pengajuan PKL</h4>
                    <p class="text-muted">Anda belum mengajukan PKL. Silakan buat pengajuan terlebih dahulu.</p>
                    <a href="/siswa/pengajuan-pkl" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-2"></i>Buat Pengajuan PKL
                    </a>
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
