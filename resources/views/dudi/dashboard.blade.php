<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard DUDI - {{ $dudi->nama_dudi }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/kelola-dudi.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dudi-pages.css') }}" rel="stylesheet">
    <link href="{{ asset('css/shared-components.css') }}" rel="stylesheet">
    {{-- <style style="display:none;">
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 70px;
            height: 100vh;
            background: linear-gradient(180deg, #dc3545 0%, #c82333 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar .logo {
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }

        .sidebar .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .sidebar .menu-list li {
            margin-bottom: 15px;
        }

        .sidebar .menu-list li a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            margin: 0 auto;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
            font-size: 22px;
        }

        .sidebar .menu-list li a:hover,
        .sidebar .menu-list li.active a {
            background: rgba(255, 255, 255, 0.2);
        }

        .main-content {
            margin-left: 70px;
            padding: 20px;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .top-bar {
            background: white;
            padding: 15px 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .welcome-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .welcome-card h4 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .welcome-card p {
            color: #666;
            margin-bottom: 0;
        }

        .stat-circles {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .stat-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        .stat-circle.teal {
            background: #17a2b8;
        }

        .stat-circle.blue {
            background: #007bff;
        }

        .stat-circle.green {
            background: #28a745;
        }

        .stat-circle.gray {
            background: #6c757d;
        }

        .alert-warning-custom {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-warning-custom i {
            color: #856404;
        }

        .notification-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .notification-section h5 {
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notification-section h5 i {
            color: #dc3545;
        }

        .notification-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }

        .notification-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .notification-info h6 {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .notification-info p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }

        .notification-badge {
            width: 12px;
            height: 12px;
            background: #dc3545;
            border-radius: 50%;
        }

        .profile-badge {
            width: 40px;
            height: 40px;
            background: #ffc107;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
        }

        .notification-bell i {
            font-size: 20px;
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        } --}}
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-graduation-cap text-danger" style="font-size: 24px;"></i>
        </div>

        <ul class="menu-list">
            <li class="active">
                <a href="/dudi/dashboard" data-bs-toggle="tooltip" title="Dashboard">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
            <li>
                <a href="/dudi/surat-pengajuan" data-bs-toggle="tooltip" title="Surat Pengajuan">
                    <i class="fas fa-file-import"></i>
                </a>
            </li>
            <li>
                <a href="/dudi/surat-permohonan" data-bs-toggle="tooltip" title="Surat Permohonan">
                    <i class="fas fa-file-signature"></i>
                </a>
            </li>
            <li style="margin-top: auto; margin-bottom: 20px;">
                <a href="/logout" data-bs-toggle="tooltip" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div>
                <h5 class="mb-0"><strong>Halaman Dashboard</strong></h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                    @if ($pengajuanCount > 0)
                        <span class="notification-count">{{ $pengajuanCount }}</span>
                    @endif
                </div>
                <div class="profile-badge">
                    {{ strtoupper(substr($dudi->nama_dudi, 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="welcome-card">
            <h4>Selamat Datang Di Dashboard PKL</h4>
            <p>Kelola program Praktik Kerja Lapangan SMK Telkom Banjarbaru dengan mudah dan efisien</p>

            <div class="stat-circles">
                <div class="stat-circle teal" data-bs-toggle="tooltip" title="Total Pengajuan">A</div>
                <div class="stat-circle blue" data-bs-toggle="tooltip" title="Menunggu Review">B</div>
                <div class="stat-circle green" data-bs-toggle="tooltip" title="Diterima">C</div>
                <div class="stat-circle gray" data-bs-toggle="tooltip" title="Ditolak">D</div>
            </div>
        </div>

        <!-- Alert Warning -->
        @if ($surat && !$surat->file_surat_balasan && $surat->file_surat_pengajuan)
            <div class="alert-warning-custom">
                <i class="fas fa-exclamation-circle"></i>
                <span>Data pencarian Anda belum lengkap. Silakan lengkapi data pada halaman <strong>Surat
                        Pengajuan</strong> untuk dapat menerima pengajuan PKL dari siswa.</span>
            </div>
        @endif

        @if ($surat && !$surat->file_balasan_permohonan && $surat->file_surat_permohonan)
            <div class="alert-warning-custom">
                <i class="fas fa-exclamation-circle"></i>
                <span>Data permohonan belum dilengkapi. Silakan lengkapi data pada halaman <strong>Surat
                        Permohonan</strong>.</span>
            </div>
        @endif

        <!-- Notifications Section -->
        <div class="notification-section">
            <h5>
                <i class="fas fa-bell"></i>
                Notifikasi Pengajuan PKL
            </h5>

            @if ($pengajuanList->count() > 0)
                @foreach ($pengajuanList as $pengajuan)
                    <div class="notification-item">
                        <div class="notification-info">
                            <h6>{{ $pengajuan->siswa->nama ?? 'Siswa' }}</h6>
                            <p>Jurusan: {{ $pengajuan->siswa->jurusan ?? '-' }} •
                                {{ $pengajuan->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="notification-badge"></div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada pengajuan PKL dari siswa</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>
