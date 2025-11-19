<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat PKL - {{ $dudi->nama_dudi }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/kelola-dudi.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .surat-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 20px;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-diterima {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-ditolak {
            background-color: #f8d7da;
            color: #842029;
        }

        .nav-pills .nav-link {
            border-radius: 10px;
            padding: 12px 25px;
            margin-right: 10px;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex align-items-center justify-content-between">
        <div class="navbar-brand">
            <div class="telkom-logo">
                <i class="fas fa-building fa-2x text-danger"></i>
            </div>
            <div class="brand-text">
                <h5 class="mb-0">DUDI - {{ Session::get('nama_dudi') }}</h5>
                <small class="text-muted">Surat PKL</small>
            </div>
        </div>

        <div class="navbar-right">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
            </button>
            <div class="dropdown">
                <button class="profile-btn dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle fa-2x"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto sidebar">
                <ul class="menu-list">
                    <li>
                        <a href="/dashboard" data-bs-toggle="tooltip" title="Dashboard">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li>
                        <a href="/dudi/lamaran-pkl" data-bs-toggle="tooltip" title="Lamaran PKL">
                            <i class="fas fa-file-alt"></i>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/dudi/surat-pkl" data-bs-toggle="tooltip" title="Surat PKL">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col main-content">
                <h3 class="mb-4"><i class="fas fa-envelope-open-text me-2"></i>Manajemen Surat PKL</h3>

                <!-- Tabs Navigation -->
                <ul class="nav nav-pills mb-4" id="suratTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pengajuan-tab" data-bs-toggle="pill"
                            data-bs-target="#pengajuan" type="button" role="tab">
                            <i class="fas fa-file-import me-2"></i>Surat Pengajuan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="permohonan-tab" data-bs-toggle="pill" data-bs-target="#permohonan"
                            type="button" role="tab">
                            <i class="fas fa-file-signature me-2"></i>Surat Permohonan
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="suratTabContent">
                    <!-- Surat Pengajuan Tab -->
                    <div class="tab-pane fade show active" id="pengajuan" role="tabpanel">
                        @if ($surat && $surat->file_surat_pengajuan)
                            <!-- Download Surat Pengajuan -->
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-download me-2"></i>Download Surat Pengajuan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Tanggal Dikirim:</strong></p>
                                            <p>{{ $surat->tanggal_upload_pengajuan ? $surat->tanggal_upload_pengajuan->format('d M Y H:i') : '-' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Catatan dari Admin:</strong></p>
                                            <p>{{ $surat->catatan_admin_pengajuan ?? 'Tidak ada catatan' }}</p>
                                        </div>
                                    </div>

                                    @if ($surat->file_surat_pengajuan && $filePengajuanExists)
                                        <a href="/dudi/surat-pkl/{{ $surat->id }}/download?jenis=pengajuan&type=surat"
                                            class="btn btn-primary">
                                            <i class="fas fa-download me-2"></i>Download Surat Pengajuan
                                        </a>
                                    @elseif ($surat->file_surat_pengajuan && !$filePengajuanExists)
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            File tidak tersedia di server. Hubungi admin untuk upload ulang.
                                        </div>
                                    @else
                                        <button class="btn btn-secondary" disabled>Tidak ada file pengajuan</button>
                                    @endif
                                </div>
                            </div>

                            <!-- Upload Balasan Pengajuan -->
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Surat Balasan Pengajuan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if ($surat->file_surat_balasan)
                                        <!-- Sudah kirim balasan -->
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Balasan sudah dikirim pada:</strong>
                                            {{ $surat->tanggal_upload_balasan ? $surat->tanggal_upload_balasan->format('d M Y H:i') : '-' }}
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <p class="mb-2"><strong>Catatan:</strong></p>
                                                <p>{{ $surat->catatan_dudi_pengajuan ?? 'Tidak ada catatan' }}</p>
                                            </div>
                                        </div>

                                        @if ($surat->file_surat_balasan && $fileBalasanPengajuanExists)
                                            <a href="/dudi/surat-pkl/{{ $surat->id }}/download?jenis=pengajuan&type=balasan"
                                                class="btn btn-secondary mb-3">
                                                <i class="fas fa-file-pdf me-2"></i>Lihat Surat Balasan
                                            </a>
                                        @elseif ($surat->file_surat_balasan && !$fileBalasanPengajuanExists)
                                            <div class="alert alert-warning mb-3">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                File tidak tersedia di server.
                                            </div>
                                        @endif

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Jika ingin mengirim balasan baru, upload file baru di bawah ini.
                                        </div>
                                    @endif

                                    <form id="formBalasanPengajuan" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="jenis_surat" value="pengajuan">

                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="file_balasan_pengajuan" class="form-label">
                                                    <i class="fas fa-file-upload me-1"></i>File Surat Balasan <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="file" class="form-control"
                                                    id="file_balasan_pengajuan" name="file_surat_balasan"
                                                    accept=".pdf,.doc,.docx" required>
                                                <small class="text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label for="catatan_pengajuan" class="form-label">
                                                    <i class="fas fa-sticky-note me-1"></i>Catatan (Opsional)
                                                </label>
                                                <textarea class="form-control" id="catatan_pengajuan" name="catatan_dudi" rows="3"
                                                    placeholder="Tambahkan catatan untuk admin...">{{ $surat->catatan_dudi_pengajuan ?? '' }}</textarea>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-paper-plane me-2"></i>Kirim Balasan Pengajuan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Belum ada surat pengajuan -->
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                    <h4 class="text-muted">Belum Ada Surat Pengajuan</h4>
                                    <p class="text-muted">Admin belum mengirim surat pengajuan PKL untuk DUDI Anda.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Surat Permohonan Tab -->
                    <div class="tab-pane fade" id="permohonan" role="tabpanel">
                        @if ($surat && $surat->file_surat_permohonan)
                            <!-- Download Surat Permohonan -->
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-download me-2"></i>Download Surat Permohonan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Tanggal Dikirim:</strong></p>
                                            <p>{{ $surat->tanggal_upload_permohonan ? $surat->tanggal_upload_permohonan->format('d M Y H:i') : '-' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Catatan dari Admin:</strong></p>
                                            <p>{{ $surat->catatan_admin_permohonan ?? 'Tidak ada catatan' }}</p>
                                        </div>
                                    </div>

                                    @if ($surat->file_surat_permohonan && $filePermohonanExists)
                                        <a href="/dudi/surat-pkl/{{ $surat->id }}/download?jenis=permohonan&type=surat"
                                            class="btn btn-info text-white">
                                            <i class="fas fa-download me-2"></i>Download Surat Permohonan
                                        </a>
                                    @elseif ($surat->file_surat_permohonan && !$filePermohonanExists)
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            File tidak tersedia di server. Hubungi admin untuk upload ulang.
                                        </div>
                                    @else
                                        <button class="btn btn-secondary" disabled>Tidak ada file permohonan</button>
                                    @endif
                                </div>
                            </div>

                            <!-- Upload Balasan Permohonan -->
                            <div class="card">
                                <div class="card-header bg-warning">
                                    <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Surat Balasan
                                        Permohonan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if ($surat->file_balasan_permohonan)
                                        <!-- Sudah kirim balasan -->
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Balasan sudah dikirim pada:</strong>
                                            {{ $surat->tanggal_upload_balasan_permohonan ? $surat->tanggal_upload_balasan_permohonan->format('d M Y H:i') : '-' }}
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <p class="mb-2"><strong>Catatan:</strong></p>
                                                <p>{{ $surat->catatan_dudi_permohonan ?? 'Tidak ada catatan' }}</p>
                                            </div>
                                        </div>

                                        @if ($surat->file_balasan_permohonan && $fileBalasanPermohonanExists)
                                            <a href="/dudi/surat-pkl/{{ $surat->id }}/download?jenis=permohonan&type=balasan"
                                                class="btn btn-secondary mb-3">
                                                <i class="fas fa-file-pdf me-2"></i>Lihat Surat Balasan
                                            </a>
                                        @elseif ($surat->file_balasan_permohonan && !$fileBalasanPermohonanExists)
                                            <div class="alert alert-warning mb-3">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                File tidak tersedia di server.
                                            </div>
                                        @endif

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Jika ingin mengirim balasan baru, upload file baru di bawah ini.
                                        </div>
                                    @endif

                                    <form id="formBalasanPermohonan" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="jenis_surat" value="permohonan">

                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="file_balasan_permohonan" class="form-label">
                                                    <i class="fas fa-file-upload me-1"></i>File Surat Balasan <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="file" class="form-control"
                                                    id="file_balasan_permohonan" name="file_surat_balasan"
                                                    accept=".pdf,.doc,.docx" required>
                                                <small class="text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label for="catatan_permohonan" class="form-label">
                                                    <i class="fas fa-sticky-note me-1"></i>Catatan (Opsional)
                                                </label>
                                                <textarea class="form-control" id="catatan_permohonan" name="catatan_dudi" rows="3"
                                                    placeholder="Tambahkan catatan untuk admin...">{{ $surat->catatan_dudi_permohonan ?? '' }}</textarea>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-warning btn-lg">
                                            <i class="fas fa-paper-plane me-2"></i>Kirim Balasan Permohonan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Belum ada surat permohonan -->
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                    <h4 class="text-muted">Belum Ada Surat Permohonan</h4>
                                    <p class="text-muted">Admin belum mengirim surat permohonan untuk DUDI Anda.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Initialize tooltips -->
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>

    <script>
        // Handler for Balasan Pengajuan form
        document.getElementById('formBalasanPengajuan')?.addEventListener('submit', function(e) {
            e.preventDefault();
            uploadBalasan(this, 'pengajuan');
        });

        // Handler for Balasan Permohonan form
        document.getElementById('formBalasanPermohonan')?.addEventListener('submit', function(e) {
            e.preventDefault();
            uploadBalasan(this, 'permohonan');
        });

        function uploadBalasan(form, jenisSurat) {
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Validate file
            const fileInput = form.querySelector('input[type="file"]');
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
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}'
            });
        @endif
    </script>
</body>

</html>
