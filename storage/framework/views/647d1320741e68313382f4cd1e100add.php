<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> - PKL SMK Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/kelola-dudi.css')); ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex align-items-center justify-content-between">
        <div class="telkom-logo">
            <img src="<?php echo e(asset('img/telkom-logo.png')); ?>" alt="Telkom Logo" height="40"
                onerror="this.style.display='none'">
        </div>
        <div class="navbar-right">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
            </button>
            <div class="dropdown">
                <button class="profile-dropdown" type="button" data-bs-toggle="dropdown">
                    <div class="profile-avatar">A</div>
                    <i class="fas fa-chevron-down text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li></li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="/logout"><i
                                class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Left Sidebar -->
    <div class="left-sidebar">
        <div class="sidebar-menu">
            <a href="/dashboard" class="sidebar-item <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>"
                title="Dashboard">
                <i class="fas fa-th-large"></i>
            </a>
            <a href="/admin/dudi"
                class="sidebar-item <?php echo e(request()->is('admin/dudi*') && !request()->is('admin/dudi-mandiri*') ? 'active' : ''); ?>"
                title="Kelola DUDI">
                <i class="fas fa-building"></i>
            </a>
            <a href="/admin/siswa" class="sidebar-item <?php echo e(request()->is('admin/siswa*') ? 'active' : ''); ?>"
                title="Kelola Siswa">
                <i class="fas fa-users"></i>
            </a>
             <a href="/admin/wali-kelas" class="sidebar-item" title="Kelola Wali Kelas">
                <i class="fas fa-chalkboard-teacher"></i>
            </a>
            <a href="/admin/pengajuan-pkl"
                class="sidebar-item <?php echo e(request()->is('admin/pengajuan-pkl*') ? 'active' : ''); ?>" title="Pengajuan PKL">
                <i class="fas fa-clipboard-list"></i>
            </a>
            <a href="/admin/surat-permohonan"
                class="sidebar-item <?php echo e(request()->is('admin/surat-permohonan*') ? 'active' : ''); ?>"
                title="Surat Permohonan Data">
                <i class="fas fa-file-invoice"></i>
            </a>
            <a href="/admin/surat-pengajuan"
                class="sidebar-item <?php echo e(request()->is('admin/surat-pengajuan*') ? 'active' : ''); ?>"
                title="Surat Pengajuan PKL">
                <i class="fas fa-file-export"></i>
            </a>
            <a href="/admin/surat-dudi"
                class="sidebar-item <?php echo e(request()->is('admin/surat-dudi*') && !request()->is('admin/surat-pengajuan*') && !request()->is('admin/surat-permohonan*') ? 'active' : ''); ?>"
                title="Surat Balasan DUDI">
                <i class="fas fa-envelope"></i>
            </a>
            <a href="/admin/dokumen-siswa"
                class="sidebar-item <?php echo e(request()->is('admin/dokumen-siswa*') ? 'active' : ''); ?>" title="Dokumen Siswa">
                <i class="fas fa-folder-open"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\pkl-smktelkom\resources\views/layouts/admin.blade.php ENDPATH**/ ?>