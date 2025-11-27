<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola DUDI - SMK Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/kelola-dudi.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/kelola-dudi-additional.css')); ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
                    <div class="profile-avatar">A</div>
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
            <a href="/dashboard" class="sidebar-item" title="Dashboard">
                <i class="fas fa-th-large"></i>
            </a>
            <a href="/admin/dudi" class="sidebar-item active" title="Kelola DUDI">
                <i class="fas fa-building"></i>
            </a>
            <a href="/admin/siswa" class="sidebar-item" title="Kelola Siswa">
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
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <div>
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <h1>Kelola DUDI</h1>
                    <p>Kelola data DUDI yang bermitra dengan SMK Telkom Banjarbaru</p>
                </div>
            </div>
            <a href="/dashboard" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Alert Messages -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Ada kesalahan pada input:</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Data Table -->
        <div class="data-table-card">
            <div class="table-header">
                <div class="table-header-content">
                    <div class="table-title">
                        <i class="fas fa-table"></i>
                        <h5>Daftar DUDI</h5>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <!-- Filter Dropdown -->
                        <div class="dropdown">
                            <select class="form-select" id="filterJenisDudi" onchange="filterDudi()"
                                style="min-width: 200px;">
                                <option value="">🏢 Semua DUDI</option>
                                <option value="sekolah" <?php echo e(request('jenis_dudi') == 'sekolah' ? 'selected' : ''); ?>>
                                    🏫 DUDI Sekolah
                                </option>
                                <option value="mandiri" <?php echo e(request('jenis_dudi') == 'mandiri' ? 'selected' : ''); ?>>
                                    👨‍🎓 DUDI Mandiri Siswa
                                </option>
                            </select>
                        </div>
                        <button class="add-btn" onclick="showAddModal()">
                            <i class="fas fa-plus"></i>
                            Tambah DUDI
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="18%">Nama DUDI</th>
                            <th width="12%">No. Telpon</th>
                            <th width="15%">Alamat</th>
                            <th width="10%">PIC</th>
                            <th width="10%">Jenis</th>
                            <th width="10%">Status</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $dudi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dudiItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-center fw-bold"><?php echo e($index + 1); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            <?php echo e(substr($dudiItem->nama_dudi, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <strong class="text-primary"><?php echo e($dudiItem->nama_dudi); ?></strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code
                                        class="bg-light text-dark px-2 py-1 rounded"><?php echo e($dudiItem->nomor_telpon); ?></code>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo e($dudiItem->alamat); ?></span>
                                </td>
                                <td><?php echo e($dudiItem->person_in_charge); ?></td>
                                <td>
                                    <?php if($dudiItem->jenis_dudi == 'mandiri'): ?>
                                        <span class="badge bg-primary">
                                            <i class="fas fa-user-graduate"></i> Mandiri
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-school"></i> Sekolah
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge status-active">
                                        Ditempatkan
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if($dudiItem->jenis_dudi == 'sekolah'): ?>
                                        <button class="action-btn btn-info"
                                            onclick="viewProfilPenerimaan(<?php echo e($dudiItem->id); ?>, '<?php echo e($dudiItem->nama_dudi); ?>', <?php echo e(json_encode($dudiItem->jurusan_diterima)); ?>, '<?php echo e(addslashes($dudiItem->jobdesk ?? '')); ?>')"
                                            data-bs-toggle="tooltip" title="Lihat Profil Penerimaan">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button class="action-btn btn-success btn-upload-surat"
                                        data-dudi-id="<?php echo e($dudiItem->id); ?>"
                                        data-dudi-nama="<?php echo e($dudiItem->nama_dudi); ?>" title="Upload Surat Pengajuan">
                                        <i class="fas fa-upload"></i>
                                    </button>
                                    <button class="action-btn btn-edit"
                                        onclick="editDudi(<?php echo e($dudiItem->id); ?>, '<?php echo e($dudiItem->nama_dudi); ?>', '<?php echo e($dudiItem->nomor_telpon); ?>', '<?php echo e($dudiItem->alamat); ?>', '<?php echo e($dudiItem->person_in_charge); ?>')"
                                        data-bs-toggle="tooltip" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn btn-reset"
                                        onclick="resetPasswordDudi(<?php echo e($dudiItem->id); ?>, '<?php echo e($dudiItem->nama_dudi); ?>')"
                                        data-bs-toggle="tooltip" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button class="action-btn btn-delete"
                                        onclick="deleteDudi(<?php echo e($dudiItem->id); ?>, '<?php echo e($dudiItem->nama_dudi); ?>')"
                                        data-bs-toggle="tooltip" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h6>Belum ada data DUDI</h6>
                                        <p>Silakan tambah data DUDI baru dengan mengklik tombol "Tambah DUDI"</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Add DUDI -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="addModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>
                        Tambah Data DUDI Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_dudi" class="form-label">
                                        <i class="fas fa-building me-1"></i>
                                        Nama DUDI
                                    </label>
                                    <input type="text" class="form-control" id="nama_dudi" name="nama_dudi"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nomor_telpon" class="form-label">
                                        <i class="fas fa-phone me-1"></i>
                                        Nomor Telepon
                                    </label>
                                    <input type="text" class="form-control" id="nomor_telpon" name="nomor_telpon"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Alamat
                                    </label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="person_in_charge" class="form-label">
                                        <i class="fas fa-user-tie me-1"></i>
                                        Person in Charge
                                    </label>
                                    <input type="text" class="form-control" id="person_in_charge"
                                        name="person_in_charge" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>
                                        Password Login
                                    </label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Batal
                    </button>
                    <button type="button" class="btn btn-danger" onclick="submitAdd()">
                        <i class="fas fa-save me-1"></i>
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit DUDI -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white" id="editModalLabel">
                        <i class="fas fa-edit me-2"></i>
                        Edit Data DUDI
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nama_dudi" class="form-label">
                                        <i class="fas fa-building me-1"></i>
                                        Nama DUDI
                                    </label>
                                    <input type="text" class="form-control" id="edit_nama_dudi" name="nama_dudi"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nomor_telpon" class="form-label">
                                        <i class="fas fa-phone me-1"></i>
                                        Nomor Telepon
                                    </label>
                                    <input type="text" class="form-control" id="edit_nomor_telpon"
                                        name="nomor_telpon" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_alamat" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Alamat
                                    </label>
                                    <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_person_in_charge" class="form-label">
                                        <i class="fas fa-user-tie me-1"></i>
                                        Person in Charge
                                    </label>
                                    <input type="text" class="form-control" id="edit_person_in_charge"
                                        name="person_in_charge" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Batal
                    </button>
                    <button type="button" class="btn btn-warning text-white" onclick="submitEdit()">
                        <i class="fas fa-save me-1"></i>
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password Confirmation -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Konfirmasi Reset Password
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-key fa-3x text-warning mb-3"></i>
                        <h6>Apakah Anda yakin ingin reset password untuk:</h6>
                        <strong class="text-primary" id="reset_dudi_name"></strong>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        Password lama akan terhapus dan diganti dengan password baru yang di-generate otomatis.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Batal
                    </button>
                    <button type="button" class="btn btn-warning text-white" onclick="confirmResetPassword()">
                        <i class="fas fa-key me-1"></i>
                        Ya, Reset Password
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password Result -->
    <div class="modal fade" id="resetPasswordResultModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i>
                        Password Berhasil Direset
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-key fa-3x text-success mb-3"></i>
                        <h6>Password baru untuk DUDI:</h6>
                        <strong class="text-primary" id="result_dudi_name"></strong>
                    </div>

                    <div class="password-result-container">
                        <label class="form-label">Password Baru:</label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light" id="new_password_display" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyPassword()">
                                <i class="fas fa-copy me-1"></i>
                                Copy
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Penting:</strong> Salin password ini dan berikan kepada DUDI. Password tidak akan
                        ditampilkan lagi setelah modal ini ditutup.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                        <i class="fas fa-check me-1"></i>
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Profil Penerimaan PKL -->
    <div class="modal fade" id="profilPenerimaanModal" tabindex="-1" aria-labelledby="profilPenerimaanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="profilPenerimaanModalLabel">
                        <i class="fas fa-info-circle me-2"></i>
                        Profil Penerimaan PKL
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="profilPenerimaanForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="profil_id_dudi" name="id_dudi">
                    <div class="modal-body">
                        <div class="mb-3">
                            <h6 class="text-primary"><i class="fas fa-building me-2"></i>Nama DUDI:</h6>
                            <p class="fs-5 fw-bold" id="detail_nama_dudi">-</p>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="fas fa-graduation-cap me-2"></i>Jurusan yang
                                    Diterima: <span class="text-danger">*</span>
                                </h6>
                                <div id="detail_jurusan_diterima">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="jurusan_diterima[]"
                                            value="RPL" id="jurusan_rpl">
                                        <label class="form-check-label" for="jurusan_rpl">
                                            <span class="badge bg-primary">RPL</span> - Rekayasa Perangkat Lunak
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="jurusan_diterima[]"
                                            value="TKJ" id="jurusan_tkj">
                                        <label class="form-check-label" for="jurusan_tkj">
                                            <span class="badge bg-info">TKJ</span> - Teknik Komputer dan Jaringan
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="jurusan_diterima[]"
                                            value="ANM" id="jurusan_anm">
                                        <label class="form-check-label" for="jurusan_anm">
                                            <span class="badge bg-warning text-dark">ANM</span> - Animasi
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="jurusan_diterima[]"
                                            value="DKV" id="jurusan_dkv">
                                        <label class="form-check-label" for="jurusan_dkv">
                                            <span class="badge bg-danger">DKV</span> - Desain Komunikasi Visual
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="jurusan_diterima[]"
                                            value="TJKT" id="jurusan_tjkt">
                                        <label class="form-check-label" for="jurusan_tjkt">
                                            <span class="badge bg-success">TJKT</span> - Teknik Jaringan Komputer dan
                                            Telekomunikasi
                                        </label>
                                    </div>
                                </div>
                                <small class="text-muted">Centang jurusan yang diterima untuk PKL</small>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="fas fa-briefcase me-2"></i>Jobdesk Siswa PKL: <span
                                        class="text-danger">*</span></h6>
                                <textarea class="form-control" id="detail_jobdesk" name="jobdesk" rows="10"
                                    placeholder="Contoh: Membantu maintenance website, membuat aplikasi mobile, testing software, dll..."></textarea>
                                <small class="text-muted">Jelaskan tugas yang akan dikerjakan siswa PKL</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </button>
                        <button type="button" class="btn btn-success" onclick="submitProfilPenerimaan()">
                            <i class="fas fa-save me-1"></i>
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Upload Surat Pengajuan -->
    <div class="modal fade" id="uploadSuratModal" tabindex="-1" aria-labelledby="uploadSuratModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="uploadSuratModalLabel">
                        <i class="fas fa-upload me-2"></i>
                        Upload Surat Pengajuan PKL
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadSuratForm" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="upload_id_dudi" name="id_dudi">

                        <div class="mb-3">
                            <label class="form-label">Untuk DUDI:</label>
                            <input type="text" class="form-control bg-light" id="upload_nama_dudi" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="file_surat" class="form-label">
                                <i class="fas fa-file-pdf me-1"></i>
                                File Surat Pengajuan <span class="text-danger">*</span>
                            </label>
                            <input type="file" class="form-control" id="file_surat" name="file_surat"
                                accept=".pdf,.doc,.docx" required>
                            <small class="text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                        </div>

                        <div class="mb-3">
                            <label for="catatan_admin" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>
                                Catatan (Opsional)
                            </label>
                            <textarea class="form-control" id="catatan_admin" name="catatan_admin" rows="3"
                                placeholder="Tambahkan catatan untuk DUDI..."></textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Surat ini akan dikirim ke DUDI untuk semua siswa yang mengajukan ke DUDI ini.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Batal
                    </button>
                    <button type="button" class="btn btn-primary" onclick="submitUploadSurat()">
                        <i class="fas fa-upload me-1"></i>
                        Upload Surat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Inline functions untuk memastikan tersedia saat halaman load -->
    <script>
        // Event listener untuk button upload surat
        document.addEventListener('DOMContentLoaded', function() {
            // Attach event listener ke semua button upload
            document.querySelectorAll('.btn-upload-surat').forEach(function(button) {
                button.addEventListener('click', function() {
                    var idDudi = this.getAttribute('data-dudi-id');
                    var namaDudi = this.getAttribute('data-dudi-nama');

                    console.log('Button clicked - ID:', idDudi, 'Nama:', namaDudi);

                    // Reset form
                    document.getElementById("uploadSuratForm").reset();

                    // Set values
                    document.getElementById("upload_id_dudi").value = idDudi;
                    document.getElementById("upload_nama_dudi").value = namaDudi;

                    console.log('Values set - ID:', document.getElementById("upload_id_dudi")
                        .value);
                    console.log('Values set - Nama:', document.getElementById("upload_nama_dudi")
                        .value);

                    // Show modal
                    var uploadModal = new bootstrap.Modal(document.getElementById(
                        "uploadSuratModal"));
                    uploadModal.show();
                });
            });
        });

        // Function to show upload surat modal (backup, jika masih dipanggil dari tempat lain)
        // Function to view profil penerimaan PKL
        function viewProfilPenerimaan(idDudi, namaDudi, jurusanDiterima, jobdesk) {
            console.log('=== viewProfilPenerimaan called ===');
            console.log('ID DUDI:', idDudi);
            console.log('Nama DUDI:', namaDudi);
            console.log('Jurusan Diterima:', jurusanDiterima);
            console.log('Jobdesk:', jobdesk);

            // Set ID DUDI
            document.getElementById('profil_id_dudi').value = idDudi;

            // Set nama DUDI
            document.getElementById('detail_nama_dudi').textContent = namaDudi;

            // Reset semua checkbox jurusan
            document.querySelectorAll('input[name="jurusan_diterima[]"]').forEach(function(checkbox) {
                checkbox.checked = false;
            });

            // Set jurusan diterima yang sudah dipilih
            if (jurusanDiterima && jurusanDiterima.length > 0) {
                jurusanDiterima.forEach(function(jurusan) {
                    var checkbox = document.querySelector('input[name="jurusan_diterima[]"][value="' + jurusan +
                        '"]');
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }

            // Set jobdesk
            var jobdeskTextarea = document.getElementById('detail_jobdesk');
            if (jobdesk && jobdesk.trim() !== '') {
                jobdeskTextarea.value = jobdesk;
            } else {
                jobdeskTextarea.value = '';
            }

            // Show modal
            var profilModal = new bootstrap.Modal(document.getElementById('profilPenerimaanModal'));
            profilModal.show();
        }

        // Function to show upload surat modal
        function showUploadSuratModal(idDudi, namaDudi) {
            console.log('=== showUploadSuratModal called ===');
            console.log('ID DUDI:', idDudi);
            console.log('Nama DUDI:', namaDudi);

            // Get elements
            var fieldIdDudi = document.getElementById("upload_id_dudi");
            var fieldNamaDudi = document.getElementById("upload_nama_dudi");

            console.log('Field ID DUDI found:', fieldIdDudi);
            console.log('Field Nama DUDI found:', fieldNamaDudi);

            // Reset form dulu
            document.getElementById("uploadSuratForm").reset();
            console.log('Form reset');

            // Baru set value setelah reset
            fieldIdDudi.value = idDudi;
            fieldNamaDudi.value = namaDudi;

            console.log('After set - ID DUDI value:', fieldIdDudi.value);
            console.log('After set - Nama DUDI value:', fieldNamaDudi.value);

            var uploadModal = new bootstrap.Modal(document.getElementById("uploadSuratModal"));
            uploadModal.show();
            console.log('Modal shown');
        }
        // Function to submit upload surat form
        function submitUploadSurat() {
            var form = document.getElementById("uploadSuratForm");
            var formData = new FormData(form);
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            // Validate file
            var fileInput = document.getElementById("file_surat");
            if (!fileInput.files.length) {
                Swal.fire({
                    icon: "warning",
                    title: "Perhatian!",
                    text: "Silakan pilih file surat terlebih dahulu",
                });
                return;
            }

            // Validate file size (5MB)
            var maxSize = 5 * 1024 * 1024;
            if (fileInput.files[0].size > maxSize) {
                Swal.fire({
                    icon: "error",
                    title: "File Terlalu Besar!",
                    text: "Ukuran file maksimal adalah 5MB",
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: "Mengupload Surat...",
                text: "Mohon tunggu sebentar",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // Submit form via AJAX
            fetch("/admin/dudi/upload-surat", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                        Accept: "application/json",
                    },
                })
                .then(async (response) => {
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.includes("application/json")) {
                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || "Terjadi kesalahan pada server");
                        }
                        return data;
                    } else {
                        throw new Error("Server tidak mengembalikan response JSON yang valid");
                    }
                })
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500,
                        }).then(() => {
                            bootstrap.Modal.getInstance(document.getElementById("uploadSuratModal")).hide();
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || "Terjadi kesalahan");
                    }
                })
                .catch((error) => {
                    console.error("Upload error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Upload!",
                        text: error.message || "Terjadi kesalahan saat mengupload surat",
                        confirmButtonColor: "#e53e3e",
                    });
                });
        }

        // Function to submit profil penerimaan PKL
        function submitProfilPenerimaan() {
            var form = document.getElementById("profilPenerimaanForm");
            var formData = new FormData(form);
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            // Validate at least one jurusan selected
            var jurusanChecked = document.querySelectorAll('input[name="jurusan_diterima[]"]:checked');
            if (jurusanChecked.length === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Perhatian!",
                    text: "Silakan pilih minimal 1 jurusan yang diterima",
                });
                return;
            }

            // Validate jobdesk
            var jobdesk = document.getElementById("detail_jobdesk").value.trim();
            if (!jobdesk) {
                Swal.fire({
                    icon: "warning",
                    title: "Perhatian!",
                    text: "Silakan isi jobdesk siswa PKL",
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: "Menyimpan...",
                text: "Sedang menyimpan profil penerimaan PKL",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // Submit form
            fetch("/admin/dudi/update-profil-penerimaan", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json",
                    },
                    body: formData,
                })
                .then(async (response) => {
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.includes("application/json")) {
                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || "Terjadi kesalahan pada server");
                        }
                        return data;
                    } else {
                        throw new Error("Server tidak mengembalikan response JSON yang valid");
                    }
                })
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500,
                        }).then(() => {
                            bootstrap.Modal.getInstance(document.getElementById("profilPenerimaanModal"))
                                .hide();
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || "Terjadi kesalahan");
                    }
                })
                .catch((error) => {
                    console.error("Submit error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Menyimpan!",
                        text: error.message || "Terjadi kesalahan saat menyimpan profil penerimaan",
                        confirmButtonColor: "#e53e3e",
                    });
                });
        }
    </script>

    <script src="<?php echo e(asset('js/kelola-dudi-clean.js')); ?>"></script>
    <script src="<?php echo e(asset('js/kelola-dudi-reset-password.js')); ?>"></script>

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
    <style>
        /* Force sidebar to display on desktop, toggleable on mobile */
        body .left-sidebar {
            position: fixed !important;
            top: 70px !important;
            left: 0 !important;
            width: 90px !important;
            height: calc(100vh - 70px) !important;
            background: #e53e3e !important;
            z-index: 1040 !important;
            padding: 20px 0 !important;
            visibility: visible !important;
            display: block !important;
        }

        .sidebar-item {
            color: white !important;
            display: flex !important;
        }

        .sidebar-item i {
            color: white !important;
        }

        .hamburger-menu {
            display: none !important;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .hamburger-menu {
                display: block !important;
                background: none !important;
                border: none !important;
                font-size: 1.5rem !important;
                color: #e53e3e !important;
                cursor: pointer !important;
                padding: 8px !important;
            }

            body .left-sidebar {
                transform: translateX(-100%) !important;
                transition: transform 0.3s ease !important;
            }

            body .left-sidebar.show {
                transform: translateX(0) !important;
            }

            .main-content {
                margin-left: 0 !important;
            }

            .sidebar-overlay {
                display: none !important;
            }

            .sidebar-overlay.show {
                display: block !important;
            }
        }
    </style>
</body>

</html>
<?php /**PATH D:\laragon\www\pkl-smktelkom\resources\views/admin/kelola-dudi.blade.php ENDPATH**/ ?>