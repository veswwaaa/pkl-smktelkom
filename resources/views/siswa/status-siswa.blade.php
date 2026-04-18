{{-- <!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Siswa - PKL SMK Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard-siswa-new.css') }}">
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="telkom-logo">
            <img src="{{ asset('img/telkom-logo.png') }}" alt="Telkom Schools" onerror="this.style.display='none'">
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
                    {{ substr($data->nama, 0, 1) }}
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header">{{ $data->nama }}</h6>
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
            <a href="/siswa/status" class="sidebar-item active" title="Status & Info Siswa">
                <i class="fas fa-user-circle"></i>
            </a>
            <a href="/siswa/pengajuan-pkl" class="sidebar-item" title="Pengajuan PKL">
                <i class="fas fa-file-alt"></i>
            </a>
            <a href="/siswa/status-pengajuan" class="sidebar-item" title="Status Pengajuan PKL">
                <i class="fas fa-tasks"></i>
            </a>
            <a href="/siswa/info-pkl" class="sidebar-item" title="Info PKL">
                <i class="fas fa-info-circle"></i>
            </a>
            <a href="/siswa/dokumen-pkl" class="sidebar-item" title="Dokumen PKL">
                <i class="fas fa-folder-open"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Dashboard Siswa</h2>
                <p class="text-muted">Selamat datang, <strong>{{ $data->nama }}</strong></p>
            </div>
            <div class="text-end">
                <small class="text-muted">NIS: {{ $data->nis }}</small><br>
                <small class="text-muted">Kelas: {{ $data->kelas }} - {{ $data->jurusan }}</small>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="fas fa-user-graduate fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Status Siswa</h6>
                                <h4 class="mb-0">Aktif</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                <i class="fas fa-building fa-2x text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Status PKL</h6>
                                <h4 class="mb-0">
                                    @if ($data->status_penempatan == 'ditempatkan')
                                        Ditempatkan
                                    @elseif($data->status_penempatan == 'selesai')
                                        Selesai
                                    @else
                                        Belum PKL
                                    @endif
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Tahun Ajaran</h6>
                                <h4 class="mb-0">{{ $data->tahun_ajaran }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($tanggalMulaiPkl && $tanggalSelesaiPkl)
            <!-- Jadwal PKL Global -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-primary bg-opacity-10 text-primary">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Jadwal Pelaksanaan PKL Global</h6>
                                    <p class="mb-0">
                                        {{ \Carbon\Carbon::parse($tanggalMulaiPkl)->translatedFormat('d F Y') }}
                                        s/d
                                        {{ \Carbon\Carbon::parse($tanggalSelesaiPkl)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="badge bg-primary text-white p-2 px-3">
                                <i class="fas fa-calendar-check me-1"></i> Jadwal Resmi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html> --}}
