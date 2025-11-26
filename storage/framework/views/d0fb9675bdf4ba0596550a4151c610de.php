<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Wali Kelas - SMK Telkom</title>
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
        <div class="telkom-logo">
            <img src="<?php echo e(asset('img/telkom-logo.png')); ?>" alt="Telkom Logo" height="40">
        </div>
        <div class="navbar-right">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>
            <div class="dropdown">
                <button class="profile-dropdown" type="button" data-bs-toggle="dropdown">
                    <div class="profile-avatar">A</div>
                    <i class="fas fa-chevron-down text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
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
            <a href="/admin/dudi" class="sidebar-item" title="Kelola DUDI">
                <i class="fas fa-building"></i>
            </a>
            <a href="/admin/siswa" class="sidebar-item" title="kelola Siswa">
                <i class="fas fa-users"></i>
            </a>
            <a href="/admin/wali-kelas" class="sidebar-item active" title="Kelola Wali Kelas">
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
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div>
                    <h1>Kelola Wali Kelas</h1>
                    <p>Kelola data Wali Kelas SMK Telkom Banjarbaru</p>
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
                        <h5>Daftar Wali Kelas</h5>
                    </div>
                    <button class="add-btn" onclick="showAddModal()">
                        <i class="fas fa-plus"></i>
                        Tambah Wali Kelas
                    </button>
                </div>
            </div>

            <div class="table-container">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="12%">NIP</th>
                            <th width="18%">Nama Wali Kelas</th>
                            <th width="10%">Kelas</th>
                            <th width="12%">No. Telpon</th>
                            <th width="18%">Alamat</th>
                            <th width="10%">Username</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $waliKelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $wk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-center fw-bold"><?php echo e($index + 1); ?></td>
                                <td>
                                    <code class="bg-light text-dark px-2 py-1 rounded"><?php echo e($wk->nip); ?></code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            <?php echo e(substr($wk->nama_admin, 0, 1)); ?>

                                        </div>
                                        <strong class="text-primary"><?php echo e($wk->nama_admin); ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?php echo e($wk->kelas ?? '-'); ?></span>
                                </td>
                                <td>
                                    <code class="bg-light text-dark px-2 py-1 rounded"><?php echo e($wk->no_telpon); ?></code>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo e($wk->alamat); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-success"><?php echo e($wk->nip); ?></span>
                                </td>
                                <td class="text-center">
                                    <button class="action-btn btn-edit"
                                        onclick="editWaliKelas(<?php echo e($wk->id); ?>, '<?php echo e($wk->nip); ?>', '<?php echo e($wk->nama_admin); ?>', '<?php echo e($wk->no_telpon); ?>', '<?php echo e($wk->alamat); ?>', '<?php echo e($wk->kelas); ?>')"
                                        data-bs-toggle="tooltip" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn btn-reset"
                                        onclick="resetPasswordWaliKelas(<?php echo e($wk->id); ?>, '<?php echo e($wk->nama_admin); ?>', '<?php echo e($wk->nip); ?>')"
                                        data-bs-toggle="tooltip" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button class="action-btn btn-delete"
                                        onclick="deleteWaliKelas(<?php echo e($wk->id); ?>, '<?php echo e($wk->nama_admin); ?>')"
                                        data-bs-toggle="tooltip" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h6>Belum ada data Wali Kelas</h6>
                                        <p>Silakan tambah data Wali Kelas baru dengan mengklik tombol "Tambah Wali Kelas"</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Add Wali Kelas -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-plus-circle me-2"></i>
                        Tambah Data Wali Kelas Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Username:</strong> NIP wali kelas<br>
                        <strong>Password default:</strong> dummy@NIP (contoh: dummy@197001012000012001)
                    </div>
                    <form id="addForm">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nip" class="form-label">
                                        <i class="fas fa-id-card me-1"></i>
                                        NIP <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nip" name="nip" required>
                                    <small class="text-muted">NIP akan digunakan sebagai username</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_admin" class="form-label">
                                        <i class="fas fa-user me-1"></i>
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nama_admin" name="nama_admin" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kelas" class="form-label">
                                        <i class="fas fa-school me-1"></i>
                                        Kelas <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" id="kelas" name="kelas" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($k); ?>"><?php echo e($k); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="text-muted">Wali kelas hanya akan melihat siswa di kelas ini</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_telpon" class="form-label">
                                        <i class="fas fa-phone me-1"></i>
                                        Nomor Telepon <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="no_telpon" name="no_telpon" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Alamat <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
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

    <!-- Modal Edit Wali Kelas -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-edit me-2"></i>
                        Edit Data Wali Kelas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nip" class="form-label">
                                        <i class="fas fa-id-card me-1"></i>
                                        NIP <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_nip" name="nip" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nama_admin" class="form-label">
                                        <i class="fas fa-user me-1"></i>
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_nama_admin" name="nama_admin" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_kelas" class="form-label">
                                        <i class="fas fa-school me-1"></i>
                                        Kelas <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" id="edit_kelas" name="kelas" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($k); ?>"><?php echo e($k); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_no_telpon" class="form-label">
                                        <i class="fas fa-phone me-1"></i>
                                        Nomor Telepon <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="edit_no_telpon" name="no_telpon" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="edit_alamat" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Alamat <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
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

    <!-- Modal Reset Password -->
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
                        <strong class="text-primary" id="reset_wali_kelas_name"></strong>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Password akan direset menjadi: <strong>dummy@NIP</strong>
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
                        <h6>Password baru untuk Wali Kelas:</h6>
                        <strong class="text-primary" id="result_wali_kelas_name"></strong>
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
                        <strong>Format:</strong> dummy@NIP (contoh: dummy@197001012000012001)
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentEditId = null;
        let currentResetId = null;
        let currentResetNip = null;

        function showAddModal() {
            document.getElementById('addForm').reset();
            new bootstrap.Modal(document.getElementById('addModal')).show();
        }

        function submitAdd() {
            const form = document.getElementById('addForm');
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            Swal.fire({
                title: 'Menyimpan Data...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/admin/wali-kelas', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `${data.message}<br><small class="text-muted">Password default: ${data.default_password}</small>`,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan',
                    confirmButtonColor: '#e53e3e'
                });
            });
        }

        function editWaliKelas(id, nip, nama, no_telpon, alamat, kelas) {
            currentEditId = id;
            document.getElementById('edit_nip').value = nip;
            document.getElementById('edit_nama_admin').value = nama;
            document.getElementById('edit_no_telpon').value = no_telpon;
            document.getElementById('edit_alamat').value = alamat;
            document.getElementById('edit_kelas').value = kelas || '';
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function submitEdit() {
            const form = document.getElementById('editForm');
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            Swal.fire({
                title: 'Memperbarui Data...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`/admin/wali-kelas/${currentEditId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message,
                    confirmButtonColor: '#e53e3e'
                });
            });
        }

        function resetPasswordWaliKelas(id, nama, nip) {
            currentResetId = id;
            currentResetNip = nip;
            document.getElementById('reset_wali_kelas_name').textContent = nama;
            new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
        }

        function confirmResetPassword() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            Swal.fire({
                title: 'Mereset Password...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`/admin/wali-kelas/${currentResetId}/reset-password`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal')).hide();
                    document.getElementById('result_wali_kelas_name').textContent = data.wali_kelas_name;
                    document.getElementById('new_password_display').value = data.new_password;
                    Swal.close();
                    new bootstrap.Modal(document.getElementById('resetPasswordResultModal')).show();
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message,
                    confirmButtonColor: '#e53e3e'
                });
            });
        }

        function deleteWaliKelas(id, nama) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `Apakah Anda yakin ingin menghapus:<br><strong class="text-danger">${nama}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53e3e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    Swal.fire({
                        title: 'Menghapus Data...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(`/admin/wali-kelas/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => location.reload());
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: error.message,
                            confirmButtonColor: '#e53e3e'
                        });
                    });
                }
            });
        }

        function copyPassword() {
            const passwordField = document.getElementById('new_password_display');
            passwordField.select();
            document.execCommand('copy');

            Swal.fire({
                icon: 'success',
                title: 'Tersalin!',
                text: 'Password berhasil disalin ke clipboard',
                showConfirmButton: false,
                timer: 1000
            });
        }
    </script>
</body>
</html>
<?php /**PATH D:\laragon\www\pkl-smktelkom\resources\views/admin/kelola-wali-kelas.blade.php ENDPATH**/ ?>