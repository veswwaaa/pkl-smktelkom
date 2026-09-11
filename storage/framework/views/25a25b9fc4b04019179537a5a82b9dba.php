<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard DUDI - <?php echo e($dudi->nama_dudi); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="<?php echo e(asset('css/welcome-header.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/kelola-dudi.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dudi-pages.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/shared-components.css')); ?>" rel="stylesheet">
    <style>
        /* ── Dashboard DUDI – Clean Minimalist & Chart ── */
        :root {
            --red: #e31c25;
            --red-light: #fdf1f2;
            --red-soft: #f8d7da;
            --yellow-light: #fffbea;
            --green-light: #edfcf2;
            --blue-light: #eef3fb;
            --text-main: #1a1a2e;
            --text-sub: #6b7280;
            --border: #e5e7eb;
            --bg: #f7f8fc;
            --white: #ffffff;
            --shadow: 0 1px 4px rgba(0,0,0,.07);
            --shadow-hover: 0 4px 16px rgba(0,0,0,.11);
            --radius: 14px;
        }

        body { background: var(--bg); font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }

        /* ── Stat Cards ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }
        @media (max-width: 992px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 540px)  { .stat-grid { grid-template-columns: 1fr; } }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 20px 22px;
            box-shadow: var(--shadow);
            border-left: 4px solid transparent;
            transition: box-shadow .2s, transform .2s;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .stat-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }
        .stat-card.siswa   { border-left-color: var(--red); }
        .stat-card.surat   { border-left-color: #4b6bfb; }
        .stat-card.pending { border-left-color: #f59e0b; }
        .stat-card.status  { border-left-color: #22c55e; }

        .stat-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }
        .stat-card.siswa   .stat-icon { background: var(--red-light); color: var(--red); }
        .stat-card.surat   .stat-icon { background: #eef2ff; color: #4b6bfb; }
        .stat-card.pending .stat-icon { background: #fffbea; color: #f59e0b; }
        .stat-card.status  .stat-icon { background: #edfcf2; color: #22c55e; }

        .stat-label { font-size: 12px; color: var(--text-sub); font-weight: 500; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
        .stat-number { font-size: 26px; font-weight: 700; color: var(--text-main); line-height: 1; }

        /* ── Dashboard Header ── */
        .dash-header {
            background: var(--white);
            border-radius: var(--radius);
            padding: 24px 28px;
            margin-bottom: 24px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .dash-header-title { font-size: 20px; font-weight: 700; color: var(--text-main); margin: 0; }
        .dash-header-sub   { font-size: 14px; color: var(--text-sub); margin: 4px 0 0; }
        .dash-date { font-size: 13px; color: var(--text-sub); background: var(--bg); border-radius: 8px; padding: 6px 14px; }

        /* ── Section Cards ── */
        .section-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .section-head {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .section-head-title {
            font-size: 16px; font-weight: 700; color: var(--text-main);
            display: flex; align-items: center; gap: 10px;
        }
        .section-head-title i { color: var(--red); font-size: 16px; }

        .chart-container {
            padding: 20px 24px;
            position: relative;
            height: 280px;
        }

        /* ── Table Styling ── */
        .table-responsive { overflow-x: auto; }
        .table-custom { width: 100%; margin-bottom: 0; }
        .table-custom th {
            background: #fafafa;
            color: var(--text-sub);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 12px 20px;
            border-bottom: 1px solid var(--border);
        }
        .table-custom td {
            padding: 14px 20px;
            vertical-align: middle;
            font-size: 14px;
            color: var(--text-main);
            border-bottom: 1px solid var(--border);
        }
        .table-custom tr:last-child td { border-bottom: none; }

        .badge-jurusan {
            background: #eef2ff; color: #4338ca;
            padding: 4px 10px; border-radius: 6px;
            font-weight: 600; font-size: 12px;
        }

        /* ── Quick Action Cards ── */
        .surat-item {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .surat-item:last-child { border-bottom: none; }
        .surat-item-info { flex: 1; }
        .surat-item-title { font-weight: 600; font-size: 14px; color: var(--text-main); }
        .surat-item-sub { font-size: 12px; color: var(--text-sub); margin-top: 2px; }

        .btn-action-sm {
            padding: 6px 14px; font-size: 12px; font-weight: 600;
            border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-action-primary { background: var(--red); color: #fff; border: none; }
        .btn-action-primary:hover { background: #c01820; color: #fff; }
        .btn-action-secondary { background: #f3f4f6; color: var(--text-main); border: none; }
        .btn-action-secondary:hover { background: #e5e7eb; color: var(--text-main); }

        /* ── Empty State ── */
        .empty-state {
            padding: 40px 24px;
            text-align: center;
            color: var(--text-sub);
        }
        .empty-icon {
            width: 60px; height: 60px;
            background: var(--bg); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            font-size: 24px; color: #d1d5db;
        }
        .empty-state p { margin: 0; font-size: 14px; }
    </style>
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <button class="hamburger-menu" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="telkom-logo">
                <img src="<?php echo e(asset('img/telkom-logo.png')); ?>" alt="Telkom Logo" height="40">
            </div>
        </div>

        <div class="navbar-right">
            <div class="dropdown">
                <button class="profile-dropdown" type="button" data-bs-toggle="dropdown">
                    <div class="profile-avatar"><?php echo e(strtoupper(substr($dudi->nama_dudi, 0, 1))); ?></div>
                    <i class="fas fa-chevron-down text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header"><?php echo e($dudi->nama_dudi); ?></h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
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
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        
        <div class="dash-header">
            <div>
                <h1 class="dash-header-title">Dashboard Kemitraan DUDI</h1>
                <p class="dash-header-sub">Selamat datang, <strong><?php echo e($dudi->nama_dudi); ?></strong></p>
            </div>
            <span class="dash-date">
                <i class="fas fa-calendar-alt me-1"></i>
                <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

            </span>
        </div>

        
        <?php if($suratPerluBalasan > 0): ?>
            <div class="alert alert-warning d-flex align-items-center gap-3 border-0 shadow-sm mb-4" style="border-radius: 12px; background: #fffbea;">
                <i class="fas fa-exclamation-circle text-warning fs-5"></i>
                <div>
                    <strong>Perhatian:</strong> Terdapat <?php echo e($suratPerluBalasan); ?> surat resmi dari sekolah yang belum dibalas. Silakan lengkapi di menu Surat Permohonan / Pengajuan.
                </div>
            </div>
        <?php endif; ?>

        
        <div class="stat-grid">
            <div class="stat-card siswa">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <div class="stat-label">Siswa Magang</div>
                    <div class="stat-number"><?php echo e($totalSiswaMagang); ?></div>
                </div>
            </div>
            <div class="stat-card surat">
                <div class="stat-icon"><i class="fas fa-envelope-open-text"></i></div>
                <div>
                    <div class="stat-label">Surat Masuk</div>
                    <div class="stat-number"><?php echo e($totalSuratMasuk); ?></div>
                </div>
            </div>
            <div class="stat-card pending">
                <div class="stat-icon"><i class="fas fa-file-signature"></i></div>
                <div>
                    <div class="stat-label">Perlu Balasan</div>
                    <div class="stat-number"><?php echo e($suratPerluBalasan); ?></div>
                </div>
            </div>
            <div class="stat-card status">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-label">Status Kemitraan</div>
                    <div class="stat-number text-success fs-6 fw-bold mt-1">Aktif</div>
                </div>
            </div>
        </div>

        
        <div class="row g-4">
            
            
            <div class="col-lg-8">
                
                
                <div class="section-card">
                    <div class="section-head">
                        <div class="section-head-title">
                            <i class="fas fa-chart-pie"></i>
                            Distribusi Siswa Magang per Jurusan
                        </div>
                        <span class="text-muted fs-7"><i class="fas fa-info-circle me-1"></i>Data Penempatan</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="jurusanChart"></canvas>
                    </div>
                </div>

                
                <div class="section-card">
                    <div class="section-head">
                        <div class="section-head-title">
                            <i class="fas fa-users"></i>
                            Daftar Siswa Magang saat Ini
                        </div>
                        <span class="badge bg-light text-dark fw-bold"><?php echo e($totalSiswaMagang); ?> Siswa</span>
                    </div>
                    <div class="table-responsive">
                        <?php if($totalSiswaMagang > 0): ?>
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>NIS</th>
                                        <th>Kelas</th>
                                        <th>Jurusan</th>
                                        <th>Periode PKL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $siswaMagang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td>
                                                <div class="fw-semibold"><?php echo e($siswa->nama); ?></div>
                                            </td>
                                            <td class="text-muted"><?php echo e($siswa->nis); ?></td>
                                            <td><?php echo e($siswa->kelas); ?></td>
                                            <td>
                                                <span class="badge-jurusan"><?php echo e($siswa->jurusan); ?></span>
                                            </td>
                                            <td class="text-muted fs-7">
                                                <?php if($siswa->tanggal_mulai_pkl && $siswa->tanggal_selesai_pkl): ?>
                                                    <?php echo e(\Carbon\Carbon::parse($siswa->tanggal_mulai_pkl)->format('d M Y')); ?> - <?php echo e(\Carbon\Carbon::parse($siswa->tanggal_selesai_pkl)->format('d M Y')); ?>

                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-user-slash"></i></div>
                                <p>Belum ada siswa magang yang ditempatkan di DUDI ini saat ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div><!-- /.col-lg-8 -->

            
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-head">
                        <div class="section-head-title">
                            <i class="fas fa-folder-open"></i>
                            Surat Resmi Administrasi
                        </div>
                    </div>
                    <div>
                        
                        <div class="surat-item">
                            <div class="surat-item-info">
                                <div class="surat-item-title">Surat Permohonan PKL</div>
                                <div class="surat-item-sub">Surat permohonan resmi dari sekolah</div>
                            </div>
                            <div>
                                <a href="/dudi/surat-permohonan" class="btn-action-sm btn-action-primary">
                                    <i class="fas fa-eye"></i>Buka
                                </a>
                            </div>
                        </div>

                        
                        <div class="surat-item">
                            <div class="surat-item-info">
                                <div class="surat-item-title">Surat Pengajuan Siswa</div>
                                <div class="surat-item-sub">Daftar siswa yang diajukan sekolah</div>
                            </div>
                            <div>
                                <a href="/dudi/surat-pengajuan" class="btn-action-sm btn-action-secondary">
                                    <i class="fas fa-eye"></i>Buka
                                </a>
                            </div>
                        </div>

                        
                        <div class="p-3 m-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex align-items-center gap-2 mb-2 text-dark font-weight-bold fs-7">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <strong>Informasi Alur Berkas</strong>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 12px; line-height: 1.5;">
                                Seluruh penempatan siswa dan verifikasi PKL dikelola langsung oleh Tim Administrasi SMK Telkom. DUDI cukup mengunduh surat permohonan dan mengunggah surat balasan secara berkala.
                            </p>
                        </div>

                    </div>
                </div>
            </div><!-- /.col-lg-4 -->

        </div><!-- /.row -->

    </div><!-- /.main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('leftSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        // Initialize Chart.js for Jurusan Distribution
        const ctx = document.getElementById('jurusanChart').getContext('2d');
        const jurusanLabels = <?php echo json_encode($jurusanLabels); ?>;
        const jurusanCounts = <?php echo json_encode($jurusanCounts); ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: jurusanLabels,
                datasets: [{
                    label: 'Jumlah Siswa Magang',
                    data: jurusanCounts,
                    backgroundColor: [
                        'rgba(227, 28, 37, 0.85)',
                        'rgba(75, 107, 251, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(34, 197, 94, 0.85)'
                    ],
                    borderColor: [
                        '#e31c25',
                        '#4b6bfb',
                        '#f59e0b',
                        '#22c55e'
                    ],
                    borderWidth: 1.5,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.raw + ' Siswa';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
<?php /**PATH C:\laragon\www\pkl-smktelkom\resources\views/dudi/dashboard.blade.php ENDPATH**/ ?>