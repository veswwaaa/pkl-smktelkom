<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard DUDI - {{ $data->nama_dudi }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/welcome-header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/kelola-dudi.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex align-items-center justify-content-between">
        <button class="hamburger-menu" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="navbar-brand">
            <div class="telkom-logo">
                <i class="fas fa-building fa-2x text-danger"></i>
            </div>
            <div class="brand-text">
                <h5 class="mb-0">{{ $data->nama_dudi }}</h5>
                <small class="text-muted">Dashboard DUDI</small>
            </div>
        </div>

        <div class="navbar-right">
            <button class="notification-btn" onclick="window.location.href='/dudi/lamaran-pkl'">
                <i class="fas fa-bell"></i>
                @if ($lamaranPending > 0)
                    <span class="notification-badge">{{ $lamaranPending }}</span>
                @endif
            </button>
            <div class="dropdown">
                <button class="profile-btn dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle fa-2x"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header">{{ $data->nama_dudi }}</h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto sidebar">
                <ul class="menu-list">
                    <li class="active">
                        <a href="/dashboard" data-bs-toggle="tooltip" title="Dashboard">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li>
                        <a href="/dudi/lamaran-pkl" data-bs-toggle="tooltip" title="Lamaran PKL">
                            <i class="fas fa-file-alt"></i>
                        </a>
                    </li>
                    <li>
                        <a href="/dudi/surat-pkl" data-bs-toggle="tooltip" title="Surat PKL">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
           <div class="main-content">
        <!-- Welcome Header -->
        <div class="welcome-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1>Selamat Datang Di Dashboard PKL</h1>
                    <p>Kelola program Praktik Kerja Lapangan SMK Telkom Banjarbaru dengan mudah dan efisien</p>
                </div>
                <div class="user-avatars">
                    <div class="user-avatar avatar-orange">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-avatar avatar-green">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="user-avatar avatar-gray">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-avatar avatar-blue">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </div>
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Lamaran
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLamaran }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Menunggu Persetujuan
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lamaranPending }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Siswa Diterima
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $siswaDiterima }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Menu Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-{{ $data->jenis_dudi == 'sekolah' ? '6' : '12' }} mb-3">
                                <a href="/dudi/lamaran-pkl" class="btn btn-outline-primary btn-lg w-100 text-start">
                                    <i class="fas fa-file-alt fa-2x float-start me-3"></i>
                                    <div>
                                        <strong>Kelola Lamaran PKL</strong>
                                        <br><small>Terima atau tolak lamaran siswa</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Info -->
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Perusahaan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama Perusahaan:</strong> {{ $data->nama_dudi }}</p>
                                <p><strong>Email:</strong> {{ $data->email ?? '-' }}</p>
                                <p><strong>Telepon:</strong> {{ $data->telepon ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Alamat:</strong> {{ $data->alamat ?? '-' }}</p>
                                <p><strong>Bidang:</strong> {{ $data->bidang_usaha ?? '-' }}</p>
                                <p><strong>Kuota Siswa:</strong> {{ $data->kuota ?? 'Tidak terbatas' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Initialize tooltips -->
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Show success/error messages from session
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}'
            });
        @endif
    </script>

    <link rel="stylesheet" href="{{ asset('css/shared-components.css') }}">
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.left-sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) sidebar.classList.toggle('show');
            if (overlay) overlay.classList.toggle('show');
        }
    </script>
</body>

</html>
