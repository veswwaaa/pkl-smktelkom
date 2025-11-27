<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permohonan - <?php echo e($dudi->nama_dudi); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/kelola-dudi.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dudi-pages.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/shared-components.css')); ?>" rel="stylesheet">
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
            <a href="/dudi/dashboard" class="sidebar-item" title="Dashboard">
                <i class="fas fa-th-large"></i>
            </a>
            <a href="/dudi/surat-permohonan" class="sidebar-item active" title="Surat Permohonan">
                <i class="fas fa-file-export"></i>
            </a>
            <a href="/dudi/surat-pengajuan" class="sidebar-item" title="Surat Pengajuan">
                <i class="fas fa-file-invoice"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid py-4">
            <div class="page-header mb-4">
                <h4 class="mb-0"><strong>Surat Permohonan Data</strong></h4>
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

                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi:</strong> Isi form di bawah ini untuk memberikan informasi penerimaan PKL.
                            Surat balasan akan di-generate otomatis berdasarkan data yang Anda isi.
                        </div>

                        <!-- Checkbox Jurusan -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Jurusan yang Diterima <span
                                        class="text-danger">*</span></h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">Pilih jurusan yang bisa diterima untuk PKL di perusahaan
                                    Anda:</p>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input jurusan-checkbox" type="checkbox"
                                                name="jurusan[]" value="RPL" id="jurusan_rpl">
                                            <label class="form-check-label" for="jurusan_rpl">
                                                <strong>RPL</strong> - Rekayasa Perangkat Lunak
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input jurusan-checkbox" type="checkbox"
                                                name="jurusan[]" value="DKV" id="jurusan_dkv">
                                            <label class="form-check-label" for="jurusan_dkv">
                                                <strong>DKV</strong> - Desain Komunikasi Visual
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input jurusan-checkbox" type="checkbox"
                                                name="jurusan[]" value="ANM" id="jurusan_anm">
                                            <label class="form-check-label" for="jurusan_anm">
                                                <strong>ANM</strong> - Animasi
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input jurusan-checkbox" type="checkbox"
                                                name="jurusan[]" value="TKJ" id="jurusan_tkj">
                                            <label class="form-check-label" for="jurusan_tkj">
                                                <strong>TKJ</strong> - Teknik Komputer dan Jaringan
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input jurusan-checkbox" type="checkbox"
                                                name="jurusan[]" value="TJAT" id="jurusan_tjat">
                                            <label class="form-check-label" for="jurusan_tjat">
                                                <strong>TJAT</strong> - Teknik Jaringan Akses Telekomunikasi
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-danger" id="error-jurusan" style="display: none;">Pilih minimal 1
                                    jurusan</small>
                            </div>
                        </div>

                        <!-- Detail per Jurusan (akan muncul dinamis) -->
                        <div id="detailJurusanContainer"></div>

                        <!-- Catatan -->
                        <div class="col-12 mb-3">
                            <label for="catatan_permohonan" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Catatan Tambahan (Opsional)
                            </label>
                            <textarea class="form-control" id="catatan_permohonan" name="catatan_dudi" rows="3"
                                placeholder="Tambahkan catatan atau persyaratan tambahan..."><?php echo e($surat->catatan_dudi_permohonan ?? ''); ?></textarea>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Handle checkbox jurusan - show/hide detail form
        document.querySelectorAll('.jurusan-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const jurusan = this.value;
                const container = document.getElementById('detailJurusanContainer');

                if (this.checked) {
                    // Tambah form detail untuk jurusan ini
                    const detailDiv = document.createElement('div');
                    detailDiv.id = `detail-${jurusan}`;
                    detailDiv.className = 'card mb-4';
                    detailDiv.innerHTML = `
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-briefcase me-2"></i>Detail untuk Jurusan ${jurusan}</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="jobdesk_${jurusan}" class="form-label">
                                    <strong>Jobdesk / Tugas Siswa PKL <span class="text-danger">*</span></strong>
                                </label>
                                <textarea class="form-control" id="jobdesk_${jurusan}" name="jobdesk[${jurusan}]" rows="4"
                                    placeholder="Contoh: Membuat website, testing aplikasi, dokumentasi, dll..." required></textarea>
                                <small class="text-muted">Jelaskan tugas dan tanggung jawab siswa ${jurusan} selama PKL</small>
                            </div>
                            <div class="mb-3">
                                <label for="kuota_${jurusan}" class="form-label">
                                    <strong>Kuota Penerimaan <span class="text-danger">*</span></strong>
                                </label>
                                <input type="number" class="form-control" id="kuota_${jurusan}" name="kuota[${jurusan}]"
                                    min="1" max="100" placeholder="Masukkan jumlah siswa yang bisa diterima" required>
                                <small class="text-muted">Jumlah siswa ${jurusan} yang bisa diterima untuk PKL</small>
                            </div>
                        </div>
                    `;
                    container.appendChild(detailDiv);
                } else {
                    // Hapus form detail untuk jurusan ini
                    const detailDiv = document.getElementById(`detail-${jurusan}`);
                    if (detailDiv) {
                        detailDiv.remove();
                    }
                }
            });
        });

        // Form submission
        document.getElementById('formBalasanPermohonan')?.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validasi: minimal 1 jurusan harus dipilih
            const checkedJurusan = document.querySelectorAll('.jurusan-checkbox:checked');
            if (checkedJurusan.length === 0) {
                document.getElementById('error-jurusan').style.display = 'block';
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Silakan pilih minimal 1 jurusan yang diterima'
                });
                return;
            }
            document.getElementById('error-jurusan').style.display = 'none';

            // Validasi: semua field jobdesk dan kuota harus diisi
            let isValid = true;
            checkedJurusan.forEach(checkbox => {
                const jurusan = checkbox.value;
                const jobdesk = document.getElementById(`jobdesk_${jurusan}`);
                const kuota = document.getElementById(`kuota_${jurusan}`);

                if (!jobdesk || !jobdesk.value.trim()) {
                    isValid = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: `Jobdesk untuk jurusan ${jurusan} harus diisi`
                    });
                    return false;
                }

                if (!kuota || !kuota.value || parseInt(kuota.value) < 1) {
                    isValid = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: `Kuota untuk jurusan ${jurusan} harus diisi minimal 1`
                    });
                    return false;
                }
            });

            if (!isValid) return;

            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Debug: Log form data
            console.log('Form Data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
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
                        // Jika ada error validasi, tampilkan detail errornya
                        if (data.errors) {
                            let errorMessages = '';
                            for (let field in data.errors) {
                                errorMessages += data.errors[field].join(', ') + '\n';
                            }
                            throw new Error(errorMessages || 'Validasi gagal');
                        }
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
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: error.message.replace(/\n/g, '<br>'),
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

    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('leftSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
    </script>
</body>

</html>
<?php /**PATH C:\laragon\www\pkl-smktelkom\resources\views/dudi/surat-permohonan.blade.php ENDPATH**/ ?>