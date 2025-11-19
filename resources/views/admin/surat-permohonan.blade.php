@extends('layouts.admin')

@section('title', 'Upload Surat Permohonan Data')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <div>
                <i class="fas fa-file-upload"></i>
            </div>
            <div>
                <h1>Upload Surat Permohonan Data</h1>
                <p>Kirim surat permohonan data jurusan/jobdesk ke DUDI</p>
            </div>
        </div>
        <a href="/admin/surat-dudi" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="container-fluid">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Info Card -->
        <div class="alert alert-warning">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Informasi:</strong> Surat Permohonan digunakan untuk meminta data ke DUDI tentang:
            <ul class="mb-0 mt-2">
                <li>Jurusan apa saja yang diterima untuk PKL</li>
                <li>Jobdesk/tugas yang akan dikerjakan siswa PKL</li>
                <li>Kuota penerimaan siswa PKL</li>
            </ul>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Form Upload Surat Permohonan</h5>
            </div>
            <div class="card-body">
                <form action="/admin/surat-permohonan/kirim" method="POST" enctype="multipart/form-data"
                    id="formSuratPermohonan">
                    @csrf

                    <!-- Pilih DUDI -->
                    <div class="mb-4">
                        <label for="id_dudi" class="form-label">
                            <strong>Pilih DUDI Tujuan <span class="text-danger">*</span></strong>
                        </label>
                        <select class="form-select" id="id_dudi" name="id_dudi" required>
                            <option value="">-- Pilih DUDI --</option>
                            @foreach ($dudis as $dudi)
                                <option value="{{ $dudi->id }}">
                                    {{ $dudi->nama_dudi }}
                                    @if ($dudi->jenis_dudi == 'mandiri')
                                        <span class="badge bg-info">Mandiri</span>
                                    @else
                                        <span class="badge bg-success">Sekolah</span>
                                    @endif
                                    - {{ $dudi->bidang_usaha ?? 'Bidang Usaha tidak diketahui' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih DUDI yang akan diminta data profil penerimaan PKL</small>
                    </div>

                    <!-- Upload Template Surat (Opsional) -->
                    <div class="mb-4">
                        <label class="form-label">
                            <strong>Template Surat (Opsional)</strong>
                        </label>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="useTemplate" name="use_template">
                                    <label class="form-check-label" for="useTemplate">
                                        <strong>Upload Template Surat Sendiri</strong>
                                    </label>
                                </div>

                                <!-- Auto Generate Info (Default) -->
                                <div id="autoGenerateInfo">
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-magic me-2"></i>
                                        <strong>Surat PDF Akan Dibuat Otomatis</strong>
                                        <br><small>Sistem akan membuat file PDF surat permohonan data secara
                                            otomatis.</small>
                                    </div>
                                </div>

                                <!-- Upload Template Section (Hidden by default) -->
                                <div id="uploadTemplateSection" style="display: none;">
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <small>Upload template surat dalam format Word (.docx) atau PDF. Template akan
                                            digunakan sebagai dasar surat permohonan.</small>
                                    </div>
                                    <input class="form-control" type="file" id="template_file" name="template_file"
                                        accept=".pdf,.doc,.docx">
                                    <small class="text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data yang Diminta -->
                    <div class="mb-4">
                        <label class="form-label">
                            <strong>Data yang Diminta dari DUDI</strong>
                        </label>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked disabled>
                                    <label class="form-check-label">
                                        <strong>Jurusan yang Diterima</strong>
                                        <br><small class="text-muted">Jurusan siswa apa saja yang bisa PKL</small>
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" checked disabled>
                                    <label class="form-check-label">
                                        <strong>Jobdesk Siswa PKL</strong>
                                        <br><small class="text-muted">Tugas dan tanggung jawab siswa selama PKL</small>
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" checked disabled>
                                    <label class="form-check-label">
                                        <strong>Kuota Penerimaan</strong>
                                        <br><small class="text-muted">Jumlah siswa yang bisa diterima</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">DUDI akan diminta mengirim balasan surat berisi data-data tersebut</small>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-4">
                        <label for="catatan" class="form-label">
                            <strong>Catatan (Opsional)</strong>
                        </label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3"
                            placeholder="Tambahkan catatan atau permintaan khusus untuk DUDI..."></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/surat-dudi" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-warning text-dark">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Surat Permohonan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle template upload section
        document.getElementById('useTemplate').addEventListener('change', function() {
            const autoInfo = document.getElementById('autoGenerateInfo');
            const uploadSection = document.getElementById('uploadTemplateSection');
            const templateFile = document.getElementById('template_file');

            if (this.checked) {
                autoInfo.style.display = 'none';
                uploadSection.style.display = 'block';
                templateFile.required = true;
            } else {
                autoInfo.style.display = 'block';
                uploadSection.style.display = 'none';
                templateFile.required = false;
                templateFile.value = '';
            }
        });

        // File size validation for template
        document.getElementById('template_file')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.size > 5 * 1024 * 1024) { // 5MB
                alert('⚠️ Ukuran file maksimal 5MB!');
                e.target.value = '';
            }
        });

        // Form validation
        document.getElementById('formSuratPermohonan').addEventListener('submit', function(e) {
            const dudiSelect = document.getElementById('id_dudi');
            const dudiText = dudiSelect.options[dudiSelect.selectedIndex].text;

            if (!confirm(`Kirim surat permohonan data ke:\n${dudiText}\n\nLanjutkan?`)) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection
