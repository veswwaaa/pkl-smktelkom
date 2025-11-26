<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen - PKL SMK Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard-siswa-new.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="telkom-logo">
            <img src="{{ asset('img/telkom-logo.png') }}" alt="Telkom Schools" onerror="this.style.display='none'">
        </div>

        <div class="navbar-right">
            <button class="notification-btn" onclick="window.location.href='/siswa/pengajuan-pkl'">
                <i class="fas fa-bell"></i>
            </button>
            <div class="dropdown">
                <div class="user-avatar" data-bs-toggle="dropdown">
                    {{ substr($siswa->nama, 0, 1) }}
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header">{{ $siswa->nama }}</h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                    </li>
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
            <a href="/siswa/pengajuan-pkl" class="sidebar-item" title="Pengajuan PKL">
                <i class="fas fa-file-alt"></i>
            </a>
            <a href="/siswa/status-pengajuan" class="sidebar-item" title="Status Pengajuan PKL">
                <i class="fas fa-tasks"></i>
            </a>
            <a href="/siswa/info-pkl" class="sidebar-item" title="Info PKL">
                <i class="fas fa-info-circle"></i>
            </a>
            <a href="/siswa/upload-dokumen" class="sidebar-item active" title="Upload Dokumen">
                <i class="fas fa-upload"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="row mb-4">
            <div class="col-12">
                <h2><i class="fas fa-upload me-2 text-primary"></i>Upload Dokumen PKL</h2>
                <p class="text-muted">Upload dokumen-dokumen yang diperlukan untuk PKL Anda</p>
            </div>
        </div>

        <!-- Informasi Siswa -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Siswa</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p class="mb-2"><strong>NIS:</strong></p>
                        <p>{{ $siswa->nis }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-2"><strong>Nama:</strong></p>
                        <p>{{ $siswa->nama }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-2"><strong>Kelas:</strong></p>
                        <p>{{ $siswa->kelas }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-2"><strong>Jurusan:</strong></p>
                        <p>{{ $siswa->jurusan }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($pengajuan && $pengajuan->status == 'approved')
            <!-- Upload CV -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-file-pdf me-2"></i>Upload CV / Curriculum Vitae</h5>
                </div>
                <div class="card-body">
                    @if ($pengajuan->cv_file)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>CV sudah diupload</strong>
                        </div>
                        <div class="mb-3">
                            <p class="mb-2"><strong>File CV:</strong></p>
                            <a href="/siswa/upload-dokumen/download/cv" class="btn btn-sm btn-secondary">
                                <i class="fas fa-download me-2"></i>Download CV
                            </a>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Jika ingin mengupload CV baru, upload file baru di bawah ini.
                        </div>
                    @endif

                    <form id="formCV" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="cv_file" class="form-label">
                                <i class="fas fa-file-upload me-1"></i>File CV <span class="text-danger">*</span>
                            </label>
                            <input type="file" class="form-control" id="cv_file" name="cv_file"
                                accept=".pdf,.doc,.docx" required>
                            <small class="text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload me-2"></i>Upload CV
                        </button>
                    </form>
                </div>
            </div>

            <!-- Upload Surat Balasan -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Upload Surat Balasan dari DUDI</h5>
                </div>
                <div class="card-body">
                    @if ($pengajuan->surat_balasan)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Surat balasan sudah diupload</strong>
                        </div>
                        <div class="mb-3">
                            <p class="mb-2"><strong>File Surat Balasan:</strong></p>
                            <a href="/siswa/upload-dokumen/download/surat" class="btn btn-sm btn-secondary">
                                <i class="fas fa-download me-2"></i>Download Surat Balasan
                            </a>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Jika ingin mengupload surat balasan baru, upload file baru di bawah ini.
                        </div>
                    @endif

                    <form id="formSurat" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="surat_balasan" class="form-label">
                                <i class="fas fa-file-upload me-1"></i>File Surat Balasan <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="file" class="form-control" id="surat_balasan" name="surat_balasan"
                                accept=".pdf,.doc,.docx" required>
                            <small class="text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                            <div class="alert alert-warning mt-2 mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <small>Upload surat balasan resmi dari DUDI yang menyatakan penerimaan atau penolakan
                                    PKL</small>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info text-white">
                            <i class="fas fa-upload me-2"></i>Upload Surat Balasan
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-exclamation-circle fa-4x text-warning mb-3"></i>
                    <h4 class="text-muted">Pengajuan PKL Belum Disetujui</h4>
                    <p class="text-muted">Anda dapat mengupload dokumen setelah pengajuan PKL Anda disetujui oleh
                        admin.</p>
                    <a href="/siswa/pengajuan-pkl" class="btn btn-primary mt-3">
                        <i class="fas fa-file-alt me-2"></i>Lihat Status Pengajuan
                    </a>
                </div>
            </div>
        @endif

        <!-- Kembali ke Dashboard -->
        <div class="mt-4">
            <a href="/dashboard" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Upload CV
        document.getElementById('formCV')?.addEventListener('submit', function(e) {
            e.preventDefault();
            uploadFile('cv', 'cv_file', 'CV');
        });

        // Upload Surat Balasan
        document.getElementById('formSurat')?.addEventListener('submit', function(e) {
            e.preventDefault();
            uploadFile('surat', 'surat_balasan', 'Surat Balasan');
        });

        function uploadFile(type, inputId, displayName) {
            const formData = new FormData();
            const fileInput = document.getElementById(inputId);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Validate file
            if (!fileInput.files.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Silakan pilih file terlebih dahulu'
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

            formData.append('file', fileInput.files[0]);
            formData.append('type', type);

            // Show loading
            Swal.fire({
                title: 'Mengupload ' + displayName + '...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form
            fetch('/siswa/upload-dokumen', {
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
