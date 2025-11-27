<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard DUDI - <?php echo e($dudi->nama_dudi); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/welcome-header.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/kelola-dudi.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dudi-pages.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/shared-components.css')); ?>" rel="stylesheet">
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex align-items-center justify-content-between">
        <!-- Logo dan Brand -->
        <div class="d-flex align-items-center gap-3">
            <!-- Hamburger Menu (Mobile Only) -->
            <button class="hamburger-menu" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <div class="telkom-logo">
                <img src="<?php echo e(asset('img/telkom-logo.png')); ?>" alt="Telkom Logo" height="40">
            </div>
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
                    <div class="profile-avatar">D</div>
                    <i class="fas fa-chevron-down text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <hr class="dropdown-divider">
                    <li><a class="dropdown-item text-danger" href="/logout"><i
                                class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Overlay untuk mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Left Sidebar -->
    <div class="left-sidebar" id="leftSidebar">
        <div class="sidebar-menu">
            <a href="/dudi/dashboard" class="sidebar-item active" title="Dashboard">
                <i class="fas fa-th-large"></i>
            </a>
            <a href="/dudi/surat-permohonan" class="sidebar-item" title="Surat Permohonan">
                <i class="fas fa-file-export"></i>
            </a>
            <a href="/dudi/surat-pengajuan" class="sidebar-item" title="Surat Pengajuan">
                <i class="fas fa-file-invoice"></i>
            </a>
            <a href="/dudi/surat-pkl" class="sidebar-item" title="Surat PKL">
                <i class="fas fa-clipboard-list"></i>
            </a>
        </div>
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

        <!-- Alert Warning -->
        <?php if($surat && !$surat->file_surat_balasan && $surat->file_surat_pengajuan): ?>
            <div class="alert-warning-custom">
                <i class="fas fa-exclamation-circle"></i>
                <span>Data pencarian Anda belum lengkap. Silakan lengkapi data pada halaman <strong>Surat
                        Pengajuan</strong> untuk dapat menerima pengajuan PKL dari siswa.</span>
            </div>
        <?php endif; ?>

        <?php if($surat && !$surat->file_balasan_permohonan && $surat->file_surat_permohonan): ?>
            <div class="alert-warning-custom">
                <i class="fas fa-exclamation-circle"></i>
                <span>Data permohonan belum dilengkapi. Silakan lengkapi data pada halaman <strong>Surat
                        Permohonan</strong>.</span>
            </div>
        <?php endif; ?>

        <!-- Notifications Section -->
        <div class="notification-section">
            <h5>
                <i class="fas fa-bell"></i>
                Notifikasi Pengajuan PKL
            </h5>

            <?php if($pengajuanList->count() > 0): ?>
                <?php $__currentLoopData = $pengajuanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="notification-item">
                        <div class="notification-info">
                            <h6><?php echo e($pengajuan->siswa->nama ?? 'Siswa'); ?></h6>
                            <p>Jurusan: <?php echo e($pengajuan->siswa->jurusan ?? '-'); ?> •
                                <?php echo e($pengajuan->created_at->format('d M Y')); ?></p>
                        </div>
                        <div class="notification-badge"></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada pengajuan PKL dari siswa</p>
                </div>
            <?php endif; ?>
        </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('leftSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>
<?php /**PATH D:\laragon\www\pkl-smktelkom\resources\views/dudi/dashboard.blade.php ENDPATH**/ ?>