<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permohonan - <?php echo e($dudi->nama_dudi); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        body {
            background: #f5f5f5;
        }

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

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
            font-weight: 600;
        }

        .btn-info {
            background: #17a2b8;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
        }

        .notification-bell i {
            font-size: 20px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-graduation-cap text-danger" style="font-size: 24px;"></i>
        </div>

        <ul class="menu-list">
            <li>
                <a href="/dudi/dashboard" data-bs-toggle="tooltip" title="Dashboard">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
            <li>
                <a href="/dudi/surat-pengajuan" data-bs-toggle="tooltip" title="Surat Pengajuan">
                    <i class="fas fa-file-import"></i>
                </a>
            </li>
            <li class="active">
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
                <h5 class="mb-0"><strong>Surat Permohonan Data</strong></h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="profile-badge">
                    <?php echo e(strtoupper(substr($dudi->nama_dudi, 0, 1))); ?>

                </div>
            </div>
        </div>

        <?php if($surat && $surat->file_surat_permohonan): ?>
            <!-- Download Surat Permohonan -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-download me-2"></i>Download Surat Permohonan dari Admin</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Tanggal Dikirim:</strong></p>
                            <p><?php echo e($surat->tanggal_upload_permohonan ? $surat->tanggal_upload_permohonan->format('d M Y H:i') : '-'); ?>

                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Catatan dari Admin:</strong></p>
                            <p><?php echo e($surat->catatan_admin_permohonan ?? 'Tidak ada catatan'); ?></p>
                        </div>
                    </div>

                    <?php if($surat->file_surat_permohonan && $filePermohonanExists): ?>
                        <a href="/dudi/surat-pkl/<?php echo e($surat->id); ?>/download?type=surat-permohonan"
                            class="btn btn-info text-white">
                            <i class="fas fa-download me-2"></i>Download Surat Permohonan
                        </a>
                    <?php elseif($surat->file_surat_permohonan && !$filePermohonanExists): ?>
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            File tidak tersedia di server. Hubungi admin untuk upload ulang.
                        </div>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>Tidak ada file permohonan</button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Upload Balasan Permohonan -->
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Surat Balasan ke Admin</h5>
                </div>
                <div class="card-body">
                    <?php if($surat->file_balasan_permohonan): ?>
                        <!-- Sudah kirim balasan -->
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Balasan sudah dikirim pada:</strong>
                            <?php echo e($surat->tanggal_upload_balasan_permohonan ? $surat->tanggal_upload_balasan_permohonan->format('d M Y H:i') : '-'); ?>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <p class="mb-2"><strong>Catatan:</strong></p>
                                <p><?php echo e($surat->catatan_balasan_permohonan ?? 'Tidak ada catatan'); ?></p>
                            </div>
                        </div>

                        <?php if($surat->file_balasan_permohonan && $fileBalasanPermohonanExists): ?>
                            <a href="/dudi/surat-pkl/<?php echo e($surat->id); ?>/download?type=balasan-permohonan"
                                class="btn btn-secondary mb-3">
                                <i class="fas fa-file-pdf me-2"></i>Lihat Surat Balasan
                            </a>
                        <?php elseif($surat->file_balasan_permohonan && !$fileBalasanPermohonanExists): ?>
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                File tidak tersedia di server.
                            </div>
                        <?php endif; ?>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Jika ingin mengirim balasan baru, upload file baru di bawah ini.
                        </div>
                    <?php endif; ?>

                    <form id="formBalasanPermohonan" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="jenis_surat" value="permohonan">

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="file_balasan_permohonan" class="form-label">
                                    <i class="fas fa-file-upload me-1"></i>File Surat Balasan <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control" id="file_balasan_permohonan"
                                    name="file_surat_balasan" accept=".pdf,.doc,.docx" required>
                                <small class="text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="catatan_permohonan" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>Catatan (Opsional)
                                </label>
                                <textarea class="form-control" id="catatan_permohonan" name="catatan_dudi" rows="3"
                                    placeholder="Tambahkan catatan untuk admin..."><?php echo e($surat->catatan_dudi_permohonan ?? ''); ?></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Balasan ke Admin
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- Belum ada surat permohonan -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum Ada Surat Permohonan</h4>
                    <p class="text-muted">Admin belum mengirim surat permohonan untuk DUDI Anda.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Form submission
        document.getElementById('formBalasanPermohonan')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Validate file
            const fileInput = document.getElementById('file_balasan_permohonan');
            if (!fileInput.files.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Silakan pilih file surat balasan terlebih dahulu'
                });
                return;
            }

            // Validate file size (5MB)
            const maxSize = 5 * 1024 * 1024;
            if (fileInput.files[0].size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar!',
                    text: 'Ukuran file maksimal adalah 5MB'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Mengirim Balasan...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form
            fetch('/dudi/surat-pkl/upload-balasan', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                    return data;
                })
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message,
                        confirmButtonColor: '#e53e3e'
                    });
                });
        });

        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?php echo e(session('success')); ?>',
                timer: 3000
            });
        <?php endif; ?>

        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?php echo e(session('error')); ?>'
            });
        <?php endif; ?>
    </script>
</body>

</html>
<?php /**PATH C:\laragon\www\pkl-smktelkom\resources\views/dudi/surat-permohonan.blade.php ENDPATH**/ ?>