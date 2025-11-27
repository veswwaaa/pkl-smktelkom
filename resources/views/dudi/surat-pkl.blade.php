{{-- <!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat PKL - {{ $dudi->nama_dudi }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/kelola-dudi.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dudi-pages.css') }}" rel="stylesheet">
    <link href="{{ asset('css/shared-components.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <img src="{{ asset('img/telkom-logo.png') }}" alt="Telkom Logo" height="40">
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
            <a href="/dudi/surat-permohonan" class="sidebar-item" title="Surat Permohonan">
                <i class="fas fa-file-export"></i>
            </a>
            <a href="/dudi/surat-pengajuan" class="sidebar-item" title="Surat Pengajuan">
                <i class="fas fa-file-invoice"></i>
            </a>
            <a href="/dudi/surat-pkl" class="sidebar-item active" title="Surat PKL">
                <i class="fas fa-clipboard-list"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid py-4">
            <div class="page-header mb-4">
                <h4 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i>Manajemen Surat PKL</h4>
            </div>

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

</html> --}}
