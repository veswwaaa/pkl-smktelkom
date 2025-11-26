<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen PKL - SMK Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard-siswa-new.css') }}">
    <style>
        .document-card {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .document-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .document-card.active {
            border-color: #ee1c25;
            background-color: #fff5f5;
        }

        .document-card.completed {
            border-color: #28a745;
            background-color: #f0fff4;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.belum {
            background-color: #ffc107;
            color: #000;
        }

        .status-badge.sudah {
            background-color: #28a745;
            color: #fff;
        }

        .status-badge.terkirim {
            background-color: #17a2b8;
            color: #fff;
        }

        .upload-area {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-area:hover {
            border-color: #ee1c25;
            background-color: #fff5f5;
        }

        .upload-area.drag-over {
            border-color: #ee1c25;
            background-color: #ffe5e5;
        }

        .file-preview {
            margin-top: 15px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 6px;
            display: none;
        }

        .file-preview.show {
            display: block;
        }

        .btn-telkom {
            background-color: #ee1c25;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-telkom:hover {
            background-color: #c41820;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(238, 28, 37, 0.3);
        }

        .timeline-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
            position: relative;
        }

        .timeline-step::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 40px;
            height: calc(100% + 10px);
            width: 2px;
            background-color: #dee2e6;
        }

        .timeline-step:last-child::before {
            display: none;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0;
            z-index: 1;
            position: relative;
        }

        .timeline-icon.active {
            background-color: #ee1c25;
            color: white;
        }

        .timeline-icon.completed {
            background-color: #28a745;
            color: white;
        }

        .timeline-content {
            flex: 1;
        }
    </style>
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="telkom-logo">
            <img src="{{ asset('img/telkom-logo.png') }}" alt="Telkom Schools" onerror="this.style.display='none'">
            <h5>Telkom Schools</h5>
        </div>

        <div class="navbar-right">
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
            <a href="/siswa/status" class="sidebar-item" title="Status PKL">
                <i class="fas fa-tasks"></i>
            </a>
            <a href="/siswa/status-pengajuan" class="sidebar-item" title="Status Pengajuan">
                <i class="fas fa-file-alt"></i>
            </a>
            <a href="/siswa/dokumen-pkl" class="sidebar-item active" title="Dokumen PKL">
                <i class="fas fa-folder-open"></i>
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="page-title">
                        <i class="fas fa-folder-open me-2" style="color: #ee1c25;"></i>
                        Dokumen PKL
                    </h2>
                    <p class="text-muted">Kelola dokumen yang diperlukan untuk PKL Anda</p>
                </div>
            </div>

            <!-- Timeline Alur Dokumen -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="fas fa-route me-2" style="color: #ee1c25;"></i>
                                Alur Dokumen PKL
                            </h5>

                            <div class="timeline-step">
                                <div
                                    class="timeline-icon {{ $dokumen->status_cv_portofolio == 'sudah' ? 'completed' : 'active' }}">
                                    <i class="fas fa-file-upload"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">1. Upload CV & Portofolio</h6>
                                    <p class="text-muted mb-2">Upload CV dan portofolio Anda untuk proses PKL</p>
                                    <span class="status-badge {{ $dokumen->status_cv_portofolio }}">
                                        {{ $dokumen->status_cv_portofolio == 'sudah' ? 'Sudah Upload' : 'Belum Upload' }}
                                    </span>
                                </div>
                            </div>

                            <div class="timeline-step">
                                <div
                                    class="timeline-icon {{ $dokumen->status_surat_pernyataan == 'terkirim' ? 'completed' : '' }}">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">2. Surat Pernyataan dari Admin</h6>
                                    <p class="text-muted mb-2">Menunggu surat pernyataan dari admin</p>
                                    <span class="status-badge {{ $dokumen->status_surat_pernyataan }}">
                                        {{ $dokumen->status_surat_pernyataan == 'terkirim' ? 'Sudah Terkirim' : 'Belum Terkirim' }}
                                    </span>
                                </div>
                            </div>

                            <div class="timeline-step">
                                <div class="timeline-icon {{ $dokumen->status_eviden == 'sudah' ? 'completed' : '' }}">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">3. Upload Eviden (Jawaban + Foto)</h6>
                                    <p class="text-muted mb-2">Upload jawaban dan foto dengan orang tua</p>
                                    <span class="status-badge {{ $dokumen->status_eviden }}">
                                        {{ $dokumen->status_eviden == 'sudah' ? 'Sudah Upload' : 'Belum Upload' }}
                                    </span>
                                </div>
                            </div>

                            <div class="timeline-step">
                                <div
                                    class="timeline-icon {{ $dokumen->status_surat_tugas == 'terkirim' ? 'completed' : '' }}">
                                    <i class="fas fa-file-signature"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">4. Surat Tugas dari Admin</h6>
                                    <p class="text-muted mb-2">Menunggu surat tugas dari admin</p>
                                    <span class="status-badge {{ $dokumen->status_surat_tugas }}">
                                        {{ $dokumen->status_surat_tugas == 'terkirim' ? 'Sudah Terkirim' : 'Belum Terkirim' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload CV & Portofolio -->
            <div class="row">
                <div class="col-12">
                    <div
                        class="document-card {{ $dokumen->status_cv_portofolio == 'sudah' ? 'completed' : 'active' }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="fas fa-file-pdf me-2"></i>
                                CV & Portofolio
                            </h5>
                            <span class="status-badge {{ $dokumen->status_cv_portofolio }}">
                                {{ $dokumen->status_cv_portofolio == 'sudah' ? 'Sudah Upload' : 'Belum Upload' }}
                            </span>
                        </div>

                        @if ($dokumen->status_cv_portofolio == 'sudah')
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-check-circle me-2"></i>
                                Dokumen sudah diupload pada
                                {{ $dokumen->tanggal_upload_cv_portofolio->format('d F Y, H:i') }}
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="fw-bold"><i class="fas fa-file-alt me-2"></i>CV</h6>
                                            <p class="text-muted mb-2">{{ basename($dokumen->file_cv) }}</p>
                                            <a href="{{ asset('storage/' . $dokumen->file_cv) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="fw-bold"><i class="fas fa-folder me-2"></i>Portofolio</h6>
                                            <p class="text-muted mb-2">{{ basename($dokumen->file_portofolio) }}</p>
                                            <a href="{{ asset('storage/' . $dokumen->file_portofolio) }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-telkom" onclick="showUploadForm()">
                                <i class="fas fa-sync-alt me-2"></i>
                                Upload Ulang
                            </button>
                        @endif

                        <form id="formUploadDokumen"
                            style="display: {{ $dokumen->status_cv_portofolio == 'sudah' ? 'none' : 'block' }};">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-file-alt me-2"></i>CV *
                                    </label>
                                    <div class="upload-area" onclick="document.getElementById('file_cv').click()">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #ee1c25;"></i>
                                        <p class="mb-1">Klik untuk upload CV</p>
                                        <small class="text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                                    </div>
                                    <input type="file" id="file_cv" name="file_cv" class="d-none"
                                        accept=".pdf,.doc,.docx" onchange="previewFile(this, 'preview_cv')">
                                    <div id="preview_cv" class="file-preview"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-folder me-2"></i>Portofolio *
                                    </label>
                                    <div class="upload-area"
                                        onclick="document.getElementById('file_portofolio').click()">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #ee1c25;"></i>
                                        <p class="mb-1">Klik untuk upload Portofolio</p>
                                        <small class="text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                                    </div>
                                    <input type="file" id="file_portofolio" name="file_portofolio" class="d-none"
                                        accept=".pdf,.doc,.docx" onchange="previewFile(this, 'preview_portofolio')">
                                    <div id="preview_portofolio" class="file-preview"></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                @if ($dokumen->status_cv_portofolio == 'sudah')
                                    <button type="button" class="btn btn-secondary" onclick="hideUploadForm()">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </button>
                                @endif
                                <button type="submit" class="btn btn-telkom" id="btnSubmit">
                                    <i class="fas fa-upload me-2"></i>
                                    Upload Dokumen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Download Surat Pernyataan -->
            <div class="row mt-4">
                <div class="col-12">
                    <div
                        class="document-card {{ $dokumen->status_surat_pernyataan == 'terkirim' ? 'completed' : '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="fas fa-envelope me-2"></i>
                                Surat Pernyataan
                            </h5>
                            <span class="status-badge {{ $dokumen->status_surat_pernyataan }}">
                                {{ $dokumen->status_surat_pernyataan == 'terkirim' ? 'Sudah Terkirim' : 'Belum Terkirim' }}
                            </span>
                        </div>

                        @if ($dokumen->status_surat_pernyataan == 'terkirim')
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-check-circle me-2"></i>
                                Surat pernyataan terkirim pada
                                {{ $dokumen->tanggal_kirim_surat_pernyataan->format('d F Y') }}
                            </div>

                            <a href="/siswa/dokumen-pkl/download/surat_pernyataan" class="btn btn-telkom"
                                target="_blank">
                                <i class="fas fa-download me-2"></i>
                                Download Surat Pernyataan
                            </a>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Menunggu admin mengirimkan surat pernyataan. Pastikan Anda sudah mengupload CV dan
                                Portofolio.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upload Eviden -->
            @if ($dokumen->status_surat_pernyataan == 'terkirim')
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="document-card {{ $dokumen->status_eviden == 'sudah' ? 'completed' : 'active' }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-camera me-2"></i>
                                    Eviden (Jawaban + Foto dengan Orang Tua)
                                </h5>
                                <span class="status-badge {{ $dokumen->status_eviden }}">
                                    {{ $dokumen->status_eviden == 'sudah' ? 'Sudah Upload' : 'Belum Upload' }}
                                </span>
                            </div>

                            @if ($dokumen->status_eviden == 'sudah')
                                <div class="alert alert-success mb-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Eviden sudah diupload pada
                                    {{ $dokumen->tanggal_upload_eviden->format('d F Y, H:i') }}
                                </div>

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-file-pdf me-2"></i>Surat Pernyataan:
                                        </h6>
                                        @if ($dokumen->file_surat_pernyataan_siswa)
                                            <a href="{{ asset('storage/' . $dokumen->file_surat_pernyataan_siswa) }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> Lihat Surat Pernyataan
                                            </a>
                                        @else
                                            <p class="text-muted">Belum ada file</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-comment-dots me-2"></i>Jawaban Anda:
                                        </h6>
                                        <p class="text-muted">{{ $dokumen->jawaban_eviden }}</p>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-image me-2"></i>Foto dengan Orang
                                            Tua:</h6>
                                        <img src="{{ asset('storage/' . $dokumen->file_foto_dengan_ortu) }}"
                                            alt="Foto dengan Orang Tua" class="img-fluid rounded"
                                            style="max-height: 400px; object-fit: contain;">
                                    </div>
                                </div>

                                <button type="button" class="btn btn-telkom" onclick="showEvidenForm()">
                                    <i class="fas fa-sync-alt me-2"></i>
                                    Upload Ulang
                                </button>
                            @endif

                            <form id="formUploadEviden"
                                style="display: {{ $dokumen->status_eviden == 'sudah' ? 'none' : 'block' }};">
                                @csrf
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Instruksi:</strong> Silakan upload surat pernyataan yang sudah diisi &
                                    ditandatangani, jawab pertanyaan, dan upload foto bersama orang tua/wali.
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-file-pdf me-2"></i>
                                        Surat Pernyataan yang Sudah Diisi & Ditandatangani *
                                    </label>
                                    <div class="upload-area"
                                        onclick="document.getElementById('file_surat_pernyataan_siswa').click()">
                                        <i class="fas fa-file-upload fa-3x mb-3" style="color: #ee1c25;"></i>
                                        <p class="mb-1">Klik untuk upload surat pernyataan</p>
                                        <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Max: 5MB)</small>
                                    </div>
                                    <input type="file" id="file_surat_pernyataan_siswa"
                                        name="file_surat_pernyataan_siswa" class="d-none" accept=".pdf,image/*"
                                        onchange="previewFile(this, 'preview_surat_pernyataan_siswa')">
                                    <div id="preview_surat_pernyataan_siswa" class="file-preview"></div>
                                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Download surat
                                        pernyataan di atas, isi & tandatangani, lalu scan/foto dan upload di
                                        sini</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-comment-dots me-2"></i>
                                        Apakah orang tua Anda sudah mengetahui dan menyetujui Anda untuk melaksanakan
                                        PKL?
                                    </label>
                                    <textarea class="form-control" name="jawaban_surat_pernyataan" rows="4"
                                        placeholder="Tuliskan jawaban Anda di sini...">{{ $dokumen->jawaban_surat_pernyataan ?? '' }}</textarea>
                                    <small class="text-muted">Contoh: "Ya, orang tua saya sudah mengetahui dan
                                        menyetujui saya untuk melaksanakan PKL di..."</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-camera me-2"></i>
                                        Foto dengan Orang Tua/Wali *
                                    </label>
                                    <div class="upload-area"
                                        onclick="document.getElementById('file_foto_ortu').click()">
                                        <i class="fas fa-image fa-3x mb-3" style="color: #ee1c25;"></i>
                                        <p class="mb-1">Klik untuk upload foto</p>
                                        <small class="text-muted">Format: JPG, JPEG, PNG (Max: 5MB)</small>
                                    </div>
                                    <input type="file" id="file_foto_ortu" name="file_foto_ortu" class="d-none"
                                        accept="image/*" onchange="previewImage(this, 'preview_foto_ortu')">
                                    <div id="preview_foto_ortu" class="file-preview"></div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    @if ($dokumen->status_eviden == 'sudah')
                                        <button type="button" class="btn btn-secondary" onclick="hideEvidenForm()">
                                            <i class="fas fa-times me-2"></i>Batal
                                        </button>
                                    @endif
                                    <button type="submit" class="btn btn-telkom" id="btnSubmitEviden">
                                        <i class="fas fa-upload me-2"></i>
                                        Upload Eviden
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Section 4: Surat Tugas --}}
            @if ($dokumen->status_surat_tugas == 'terkirim')
                <div class="col-12 mb-4">
                    <div class="document-card completed">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="fas fa-file-signature me-2"></i>
                                Surat Tugas PKL
                            </h5>
                            <span class="status-badge terkirim">Sudah Terkirim</span>
                        </div>

                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Surat tugas telah dikirim pada
                            {{ $dokumen->tanggal_kirim_surat_tugas->format('d F Y, H:i') }}
                        </div>

                        <p class="text-muted mb-3">
                            <i class="fas fa-file-pdf me-2"></i>
                            Nomor: {{ $dokumen->nomor_surat_tugas }}
                        </p>

                        <a href="/siswa/dokumen-pkl/download/surat_tugas" class="btn btn-telkom" target="_blank">
                            <i class="fas fa-download me-2"></i>
                            Download Surat Tugas
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function previewFile(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];

            if (file) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                const fileName = file.name;

                preview.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-pdf me-2" style="color: #ee1c25;"></i>
                            <span class="fw-bold">${fileName}</span>
                            <small class="text-muted ms-2">(${fileSize} MB)</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile('${input.id}', '${previewId}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                preview.classList.add('show');
            }
        }

        function clearFile(inputId, previewId) {
            document.getElementById(inputId).value = '';
            document.getElementById(previewId).classList.remove('show');
            document.getElementById(previewId).innerHTML = '';
        }

        function showUploadForm() {
            document.getElementById('formUploadDokumen').style.display = 'block';
        }

        function hideUploadForm() {
            document.getElementById('formUploadDokumen').style.display = 'none';
        }

        // Handle form submit
        document.getElementById('formUploadDokumen').addEventListener('submit', function(e) {
            e.preventDefault();

            const fileCV = document.getElementById('file_cv').files[0];
            const filePortofolio = document.getElementById('file_portofolio').files[0];

            if (!fileCV || !filePortofolio) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Mohon upload kedua file (CV dan Portofolio)',
                });
                return;
            }

            const formData = new FormData();
            formData.append('file_cv', fileCV);
            formData.append('file_portofolio', filePortofolio);
            formData.append('_token', '{{ csrf_token() }}');

            const btnSubmit = document.getElementById('btnSubmit');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...';

            fetch('/siswa/dokumen-pkl/upload', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#ee1c25'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Gagal mengupload dokumen',
                        confirmButtonColor: '#ee1c25'
                    });
                })
                .finally(() => {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Dokumen';
                });
        });

        // Preview image function
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];

            if (file) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                const fileName = file.name;
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.innerHTML = `
                        <div class="text-center">
                            <img src="${e.target.result}" class="img-fluid rounded mb-2" style="max-height: 300px; object-fit: contain;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-image me-2" style="color: #ee1c25;"></i>
                                    <span class="fw-bold">${fileName}</span>
                                    <small class="text-muted ms-2">(${fileSize} MB)</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile('${input.id}', '${previewId}')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    preview.classList.add('show');
                }

                reader.readAsDataURL(file);
            }
        }

        function showEvidenForm() {
            document.getElementById('formUploadEviden').style.display = 'block';
        }

        function hideEvidenForm() {
            document.getElementById('formUploadEviden').style.display = 'none';
        }

        // Handle eviden form submit
        const formEviden = document.getElementById('formUploadEviden');
        if (formEviden) {
            formEviden.addEventListener('submit', function(e) {
                e.preventDefault();

                const fileSuratPernyataan = document.getElementById('file_surat_pernyataan_siswa').files[0];
                const jawaban = document.querySelector('[name="jawaban_surat_pernyataan"]').value;
                const fileFoto = document.getElementById('file_foto_ortu').files[0];

                if (!fileSuratPernyataan) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Mohon upload surat pernyataan yang sudah diisi',
                    });
                    return;
                }

                if (!fileFoto) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Mohon upload foto dengan orang tua',
                    });
                    return;
                }

                const formData = new FormData();
                formData.append('file_surat_pernyataan_siswa', fileSuratPernyataan);
                formData.append('jawaban_surat_pernyataan', jawaban);
                formData.append('file_foto_ortu', fileFoto);
                formData.append('_token', '{{ csrf_token() }}');

                const btnSubmitEviden = document.getElementById('btnSubmitEviden');
                btnSubmitEviden.disabled = true;
                btnSubmitEviden.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...';

                fetch('/siswa/dokumen-pkl/upload-eviden', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                confirmButtonColor: '#ee1c25'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: error.message || 'Gagal mengupload eviden',
                            confirmButtonColor: '#ee1c25'
                        });
                    })
                    .finally(() => {
                        btnSubmitEviden.disabled = false;
                        btnSubmitEviden.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Eviden';
                    });
            });
        }
    </script>
</body>

</html>
