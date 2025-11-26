<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard DUDI - <?php echo e($dudi->nama_dudi); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/kelola-dudi.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dudi-pages.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/shared-components.css')); ?>" rel="stylesheet">
    
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
                    <?php if($pengajuanCount > 0): ?>
                        <span class="notification-count"><?php echo e($pengajuanCount); ?></span>
                    <?php endif; ?>
                </div>
                <div class="profile-badge">
                    <?php echo e(strtoupper(substr($dudi->nama_dudi, 0, 1))); ?>

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
<?php /**PATH D:\laragon\www\pkl-smktelkom\resources\views/dudi/dashboard.blade.php ENDPATH**/ ?>