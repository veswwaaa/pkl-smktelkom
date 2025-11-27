<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Dashboard PKL - SMK Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/welcome-header.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dashboard-admin.css')); ?>" rel="stylesheet">
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex align-items-center justify-content-between">
        <!-- Logo dan Brand -->
        <div class="d-flex align-items-center gap-3">
            <!-- Hamburger Menu (Mobile Only) -->
            <button class="hamburger-menu" id="hamburgerMenu" onclick="toggleSidebar()">
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
                    <div class="profile-avatar">A</div>
                    <i class="fas fa-chevron-down text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
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
            <a href="#" class="sidebar-item active" title="Dashboard">
                <i class="fas fa-th-large"></i>
            </a>
            <a href="/admin/dudi" class="sidebar-item" title="Kelola DUDI">
                <i class="fas fa-building"></i>
            </a>
            <a href="/admin/siswa" class="sidebar-item" title="kelola Siswa">
                <i class="fas fa-users"></i>
            </a>
            <a href="/admin/wali-kelas" class="sidebar-item" title="Kelola Wali Kelas">
                <i class="fas fa-chalkboard-teacher"></i>
            </a>
            <a href="/admin/pengajuan-pkl" class="sidebar-item" title="Pengajuan PKL">
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
            <a href="/admin/surat-dudi" class="sidebar-item" title="Surat DUDI">
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

        <!-- Stats Cards -->
        <div class="stats-row">
            <!-- Total Siswa -->
            <div class="stat-card border-red" onclick="window.location.href='/admin/siswa'" style="cursor: pointer;">
                <div class="stat-icon icon-red">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo e($totalSiswa); ?></div>
                <div class="stat-label">Total Siswa</div>
                <div class="stat-change positive">
                    Lihat selengkapnya
                </div>
                <i class="fas fa-chevron-right stat-arrow"></i>
            </div>

            <!-- Total DUDI -->
            <div class="stat-card border-red" onclick="window.location.href='/admin/dudi'" style="cursor: pointer;">
                <div class="stat-icon icon-red">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-number"><?php echo e($totalDudi); ?></div>
                <div class="stat-label">Total DUDI</div>
                <div class="stat-change positive">
                    Lihat selengkapnya
                </div>
                <i class="fas fa-chevron-right stat-arrow"></i>
            </div>

            <!-- Total Wali Kelas -->
            <div class="stat-card border-red" onclick="window.location.href='/admin/wali-kelas'"
                style="cursor: pointer;">
                <div class="stat-icon icon-red">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-number"><?php echo e($totalWaliKelas); ?></div>
                <div class="stat-label">Total Wali Kelas</div>
                <div class="stat-change positive">
                    Lihat selengkapnya
                </div>
                <i class="fas fa-chevron-right stat-arrow"></i>
            </div>
        </div>

        <!-- Content Row -->
        <div class="content-row">
            <!-- Activities Section -->
            <div class="activities-card">
                <div class="activities-header">
                    <div class="activities-title">
                        <i class="fas fa-calendar-alt"></i>
                        <h5>Aktivitas Terkini</h5>
                    </div>
                    <span class="activities-subtitle">Kegiatan terbaru dalam sistem PKL (Hari Ini)</span>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="activity-item">
                        <?php
                            $dotColor = match ($activity->type) {
                                'login' => 'blue',
                                'create' => 'green',
                                'update' => 'orange',
                                'delete' => 'red',
                                'success' => 'green',
                                'warning' => 'orange',
                                'info' => 'blue',
                                default => 'gray',
                            };
                        ?>
                        <div class="activity-dot <?php echo e($dotColor); ?>"></div>
                        <div class="activity-content">
                            <h6><?php echo e($activity->title); ?></h6>
                            <p><?php echo e($activity->description); ?></p>
                            <span class="activity-time">
                                <i class="fas fa-user me-1"></i><?php echo e($activity->username); ?> •
                                <?php echo e($activity->created_at->diffForHumans()); ?>

                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="activity-item">
                        <div class="activity-dot gray"></div>
                        <div class="activity-content">
                            <h6>Belum Ada Aktivitas</h6>
                            <p>Belum ada aktivitas yang tercatat hari ini</p>
                            <span class="activity-time">-</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Sidebar -->
            <div class="right-sidebar">
                <!-- Calendar -->
                <div class="calendar-card">
                    <div class="calendar-header">
                        <i class="fas fa-calendar text-primary"></i>
                        <h6>Kalender</h6>
                    </div>
                    <div class="calendar-widget">
                        <iframe
                            src="https://calendar.google.com/calendar/embed?height=300&wkst=1&bgcolor=%23ffffff&ctz=Asia%2FMakassar&src=id.indonesian%23holiday%40group.v.calendar.google.com&color=%23039BE5&showTitle=0&showCalendars=0&showTabs=0&showPrint=0&showDate=1&showNav=1"
                            class="calendar-iframe" frameborder="0" scrolling="no">
                        </iframe>
                    </div>
                </div>

                <!-- Notes & Agenda -->
                <div class="notes-card">
                    <div class="notes-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-sticky-note text-warning"></i>
                            <h6>Catatan Admin</h6>
                        </div>
                        <button class="btn btn-sm btn-primary" onclick="addNote()">
                            <i class="fas fa-plus"></i> Catatan Baru
                        </button>
                    </div>

                    <div id="notesList">
                        <!-- Notes akan dimuat via AJAX -->
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Memuat catatan...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('js/dashboard-notes.js')); ?>"></script>

    <script>
        // Toggle Sidebar untuk Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('leftSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        // Close sidebar when clicking on menu item (mobile)
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleSidebar();
                }
            });
        });
    </script>
</body>

</html>
<?php /**PATH C:\laragon\www\pkl-smktelkom\resources\views/dashboardAdmin.blade.php ENDPATH**/ ?>