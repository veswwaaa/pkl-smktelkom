<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengajuan PKL - SMK Telkom Banjarbaru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/kelola-dudi.css')); ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex align-items-center justify-content-between">
        <!-- Logo dan Brand -->
        <div class="telkom-logo">
            <img src="<?php echo e(asset('img/telkom-logo.png')); ?>" alt="Telkom Logo" height="40">
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
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                    <li>
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
            <a href="/dashboard" class="sidebar-item" title="Dashboard">
                <i class="fas fa-home"></i>
            </a>
            <a href="/admin/dudi" class="sidebar-item" title="Kelola DUDI">
                <i class="fas fa-building"></i>
            </a>
            <a href="/admin/siswa" class="sidebar-item" title="Kelola Siswa">
                <i class="fas fa-users"></i>
            </a>
            <a href="/admin/pengajuan-pkl" class="sidebar-item active" title="Pengajuan PKL">
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
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h3><i class="fas fa-clipboard-list text-danger me-2"></i>Kelola Pengajuan PKL</h3>
                <p class="text-muted mb-0">Kelola pengajuan PKL dari siswa</p>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Data</h6>
                    <a href="/admin/pengajuan-pkl/export-approved" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel me-1"></i> Export Siswa Approved ke Excel
                    </a>
                </div>
                <form action="/admin/pengajuan-pkl" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending
                            </option>
                            <option value="diproses" <?php echo e(request('status') == 'diproses' ? 'selected' : ''); ?>>Diproses
                            </option>
                            <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved
                            </option>
                            <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas" class="form-select">
                            <option value="">Semua Kelas</option>
                            <?php $__currentLoopData = ['XIIA', 'XIIB', 'XIIC', 'XIID', 'XIIE', 'XIIF', 'XIIG']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kelas); ?>" <?php echo e(request('kelas') == $kelas ? 'selected' : ''); ?>>
                                    <?php echo e($kelas); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cari (Nama/NIS)</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Ketik nama atau NIS..." value="<?php echo e(request('search')); ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Pilihan Aktif</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $pengajuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php if($item->siswa): ?>
                                    <tr>
                                        <td><?php echo e($pengajuan->firstItem() + $index); ?></td>
                                        <td><?php echo e($item->siswa->nis); ?></td>
                                        <td><?php echo e($item->siswa->nama); ?></td>
                                        <td><?php echo e($item->siswa->kelas); ?></td>
                                        <td><span class="badge bg-info"><?php echo e($item->siswa->jurusan); ?></span></td>
                                        <td>
                                            <?php
                                                $pilihanAktif = $item->pilihan_aktif;
                                                $dudi = null;
                                                $dudiMandiri = null;
                                                $isDudiMandiri = false;
                                                $dudiMandiriHasAccount = false;
                                                $isPklDiSekolah = $pilihanAktif == 'SMK Telkom Banjarbaru';

                                                // Definisikan nama untuk semua pilihan
                                                $pilihan1Nama = $item->dudiPilihan1
                                                    ? $item->dudiPilihan1->nama_dudi
                                                    : ($item->dudiMandiriPilihan1
                                                        ? $item->dudiMandiriPilihan1->nama_dudi . ' (Mandiri)'
                                                        : '-');
                                                $pilihan2Nama = $item->dudiPilihan2
                                                    ? $item->dudiPilihan2->nama_dudi
                                                    : ($item->dudiMandiriPilihan2
                                                        ? $item->dudiMandiriPilihan2->nama_dudi . ' (Mandiri)'
                                                        : '-');
                                                $pilihan3Nama = $item->dudiPilihan3
                                                    ? $item->dudiPilihan3->nama_dudi
                                                    : ($item->dudiMandiriPilihan3
                                                        ? $item->dudiMandiriPilihan3->nama_dudi . ' (Mandiri)'
                                                        : '-');

                                                if ($pilihanAktif == '1') {
                                                    $dudi = $item->dudiPilihan1;
                                                    $dudiMandiri = $item->dudiMandiriPilihan1;
                                                    $isDudiMandiri =
                                                        $item->dudiPilihan1 == null &&
                                                        $item->dudiMandiriPilihan1 != null;
                                                    if ($isDudiMandiri && $dudiMandiri) {
                                                        $dudiMandiriHasAccount = $dudiMandiri->id_dudi != null;
                                                        // Jika DUDI Mandiri sudah punya akun, ambil data DUDI-nya
                                                        if ($dudiMandiriHasAccount) {
                                                            $dudi = $dudiMandiri->dudi;
                                                        } else {
                                                            $dudi = $dudiMandiri;
                                                        }
                                                    }
                                                } elseif ($pilihanAktif == '2') {
                                                    $dudi = $item->dudiPilihan2;
                                                    $dudiMandiri = $item->dudiMandiriPilihan2;
                                                    $isDudiMandiri =
                                                        $item->dudiPilihan2 == null &&
                                                        $item->dudiMandiriPilihan2 != null;
                                                    if ($isDudiMandiri && $dudiMandiri) {
                                                        $dudiMandiriHasAccount = $dudiMandiri->id_dudi != null;
                                                        if ($dudiMandiriHasAccount) {
                                                            $dudi = $dudiMandiri->dudi;
                                                        } else {
                                                            $dudi = $dudiMandiri;
                                                        }
                                                    }
                                                } elseif ($pilihanAktif == '3') {
                                                    $dudi = $item->dudiPilihan3;
                                                    $dudiMandiri = $item->dudiMandiriPilihan3;
                                                    $isDudiMandiri =
                                                        $item->dudiPilihan3 == null &&
                                                        $item->dudiMandiriPilihan3 != null;
                                                    if ($isDudiMandiri && $dudiMandiri) {
                                                        $dudiMandiriHasAccount = $dudiMandiri->id_dudi != null;
                                                        if ($dudiMandiriHasAccount) {
                                                            $dudi = $dudiMandiri->dudi;
                                                        } else {
                                                            $dudi = $dudiMandiri;
                                                        }
                                                    }
                                                }
                                            ?>

                                            <?php if($isPklDiSekolah): ?>
                                                <strong>SMK Telkom Banjarbaru</strong>
                                                <span class="badge bg-info text-white ms-1"
                                                    style="font-size: 0.65rem;">
                                                    <i class="fas fa-school"></i> PKL di Sekolah
                                                </span>
                                            <?php else: ?>
                                                <strong><?php echo e($dudi->nama_dudi ?? '-'); ?></strong>
                                                <?php if($isDudiMandiri): ?>
                                                    <span class="badge bg-warning text-dark ms-1"
                                                        style="font-size: 0.65rem;">Mandiri</span>
                                                    <?php if(!$dudiMandiriHasAccount): ?>
                                                        <br><small class="text-danger" style="font-size: 0.75rem;">
                                                            <i class="fas fa-exclamation-circle"></i> Akun DUDI belum
                                                            dibuat
                                                        </small>
                                                    <?php else: ?>
                                                        <br><small class="text-success" style="font-size: 0.75rem;">
                                                            <i class="fas fa-check-circle"></i> Akun sudah dibuat
                                                        </small>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <br>
                                            <?php if(!$isPklDiSekolah): ?>
                                                <small class="badge bg-primary" style="font-size: 0.7rem;">Pilihan
                                                    <?php echo e($pilihanAktif); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(date('d M Y', strtotime($item->tanggal_pengajuan))); ?></td>
                                        <td>
                                            <?php if($item->status == 'pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php elseif($item->status == 'diproses'): ?>
                                                <span class="badge bg-info">Diproses</span>
                                            <?php elseif($item->status == 'approved'): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="Lihat Detail"
                                                onclick="viewDetail(<?php echo e($item->id); ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <?php if($item->status == 'pending' || $item->status == 'diproses'): ?>
                                                <?php if($isDudiMandiri && !$dudiMandiriHasAccount): ?>
                                                    
                                                    <button class="btn btn-sm btn-success"
                                                        title="DUDI bersedia jadi tempat PKL, buat akun"
                                                        onclick="confirmCreateDudiAccount(<?php echo e($dudiMandiri->id); ?>, '<?php echo e($dudiMandiri->nama_dudi); ?>', <?php echo e($item->id); ?>, '<?php echo e($item->siswa->nama); ?>')">
                                                        <i class="fas fa-check-circle"></i> DUDI Bersedia
                                                    </button>
                                                    <button class="btn btn-sm btn-danger"
                                                        title="DUDI tidak bersedia jadi tempat PKL"
                                                        onclick="confirmDudiTidakBersedia(<?php echo e($item->id); ?>, '<?php echo e($item->siswa->nama); ?>', '<?php echo e($dudiMandiri->nama_dudi); ?>')">
                                                        <i class="fas fa-times-circle"></i> Tidak Bersedia
                                                    </button>
                                                <?php elseif($isDudiMandiri && $dudiMandiriHasAccount): ?>
                                                    
                                                    <button class="btn btn-sm btn-success"
                                                        title="DUDI menyetujui siswa"
                                                        onclick="confirmApprove(<?php echo e($item->id); ?>, '<?php echo e($item->siswa->nama); ?>', '<?php echo e($dudi->nama_dudi ?? ''); ?>')">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" title="DUDI tidak setuju"
                                                        onclick="confirmRejectDudiMandiri(<?php echo e($item->id); ?>, '<?php echo e($item->siswa->nama); ?>', '<?php echo e($dudi->nama_dudi ?? ''); ?>')">
                                                        <i class="fas fa-times-circle"></i> DUDI Tolak
                                                    </button>
                                                <?php else: ?>
                                                    
                                                    <button class="btn btn-sm btn-success"
                                                        title="Approve & Kirim ke DUDI"
                                                        onclick="confirmApprove(<?php echo e($item->id); ?>, '<?php echo e($item->siswa->nama); ?>', '<?php echo e($dudi->nama_dudi ?? ''); ?>')">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                    <button class="btn btn-sm btn-warning" title="Reject"
                                                        onclick="confirmReject(<?php echo e($item->id); ?>, '<?php echo e($item->siswa->nama); ?>')">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <button class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="confirmDelete(<?php echo e($item->id); ?>, '<?php echo e($item->siswa->nama); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada pengajuan PKL</h5>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan <?php echo e($pengajuan->firstItem() ?? 0); ?> - <?php echo e($pengajuan->lastItem() ?? 0); ?> dari
                        <?php echo e($pengajuan->total()); ?> data
                    </div>
                    <div>
                        <?php echo e($pengajuan->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle"></i> Detail Pengajuan PKL
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p>Loading...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(asset('js/kelola-pengajuan.js')); ?>"></script>

    <!-- SweetAlert Notifications -->
    <script>
        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                html: '<?php echo session('success'); ?>',
                timer: 5000,
                showConfirmButton: true
            });
        <?php endif; ?>

        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?php echo e(session('error')); ?>',
                confirmButtonColor: '#e53e3e'
            });
        <?php endif; ?>
    </script>
</body>

</html>
<?php /**PATH C:\laragon\www\pkl-smktelkom\resources\views/admin/kelola-pengajuan.blade.php ENDPATH**/ ?>