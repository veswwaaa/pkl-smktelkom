@extends('layouts.admin')

@section('title', 'Dokumen Siswa')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/shared-components.css') }}">
    <style>
        /* Status Badges dengan Warna Jelas */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
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

        /* Progress Bar */
        .progress-bar-container {
            height: 8px;
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #ee1c25, #c41820);
            transition: width 0.3s ease;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div class="page-title">
            <div>
                <i class="fas fa-folder-open"></i>
            </div>
            <div>
                <h1>Dokumen Siswa PKL</h1>
                <p>Kelola dokumen PKL siswa</p>
            </div>
        </div>
        <a href="/dashboard" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="container-fluid">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x mb-2" style="color: #ee1c25;"></i>
                        <h5 class="mb-0">{{ $dokumenList->count() }}</h5>
                        <small class="text-muted">Total Siswa</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-file-upload fa-2x mb-2" style="color: #28a745;"></i>
                        <h5 class="mb-0">{{ $dokumenList->where('status_cv_portofolio', 'sudah')->count() }}</h5>
                        <small class="text-muted">CV & Portofolio</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-2x mb-2" style="color: #17a2b8;"></i>
                        <h5 class="mb-0">{{ $dokumenList->where('status_surat_pernyataan', 'terkirim')->count() }}</h5>
                        <small class="text-muted">Surat Pernyataan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-file-signature fa-2x mb-2" style="color: #6610f2;"></i>
                        <h5 class="mb-0">{{ $dokumenList->where('status_surat_tugas', 'terkirim')->count() }}</h5>
                        <small class="text-muted">Surat Tugas</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableDokumenSiswa" class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>CV & Portofolio</th>
                                <th>Surat Pernyataan</th>
                                <th>Eviden</th>
                                <th>Surat Tugas</th>
                                <th>Progress</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dokumenList as $index => $dokumen)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $dokumen->siswa->nis }}</td>
                                    <td>{{ $dokumen->siswa->nama }}</td>
                                    <td>{{ $dokumen->siswa->kelas }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $dokumen->status_cv_portofolio == 'sudah' ? 'success' : 'warning' }}">
                                            {{ $dokumen->status_cv_portofolio == 'sudah' ? 'Sudah' : 'Belum' }}
                                        </span>
                                        @if ($dokumen->status_cv_portofolio == 'sudah')
                                            <br>
                                            <small
                                                class="text-muted">{{ $dokumen->tanggal_upload_cv_portofolio->format('d/m/Y') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $dokumen->status_surat_pernyataan == 'terkirim' ? 'info' : 'secondary' }}">
                                            {{ $dokumen->status_surat_pernyataan == 'terkirim' ? 'Terkirim' : 'Belum' }}
                                        </span>
                                        @if ($dokumen->status_surat_pernyataan == 'terkirim')
                                            <br>
                                            <small
                                                class="text-muted">{{ $dokumen->tanggal_kirim_surat_pernyataan->format('d/m/Y') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $dokumen->status_eviden == 'sudah' ? 'success' : 'warning' }}">
                                            {{ $dokumen->status_eviden == 'sudah' ? 'Sudah' : 'Belum' }}
                                        </span>
                                        @if ($dokumen->status_eviden == 'sudah')
                                            <br>
                                            <small
                                                class="text-muted">{{ $dokumen->tanggal_upload_eviden->format('d/m/Y') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $dokumen->status_surat_tugas == 'terkirim' ? 'info' : 'secondary' }}">
                                            {{ $dokumen->status_surat_tugas == 'terkirim' ? 'Terkirim' : 'Belum' }}
                                        </span>
                                        @if ($dokumen->status_surat_tugas == 'terkirim')
                                            <br>
                                            <small
                                                class="text-muted">{{ $dokumen->tanggal_kirim_surat_tugas->format('d/m/Y') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $progress = 0;
                                            if ($dokumen->status_cv_portofolio == 'sudah') {
                                                $progress += 25;
                                            }
                                            if ($dokumen->status_surat_pernyataan == 'terkirim') {
                                                $progress += 25;
                                            }
                                            if ($dokumen->status_eviden == 'sudah') {
                                                $progress += 25;
                                            }
                                            if ($dokumen->status_surat_tugas == 'terkirim') {
                                                $progress += 25;
                                            }
                                            $progressColor =
                                                $progress == 100 ? 'success' : ($progress >= 50 ? 'info' : 'warning');
                                        @endphp
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-{{ $progressColor }}" role="progressbar"
                                                style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted d-block text-center mt-1">{{ $progress }}%</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-action"
                                            onclick="viewDetail({{ $dokumen->id }})">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Dokumen -->
    <div class="modal fade" id="modalDetailDokumen" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-folder-open me-2" style="color: #ee1c25;"></i>
                        Detail Dokumen Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalDetailContent">
                    <!-- Content akan diisi via JavaScript -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tableDokumenSiswa').DataTable({
                order: [
                    [2, 'asc']
                ],
                pageLength: 25,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                }
            });
        });

        function viewDetail(dokumenId) {
            // Ambil data dokumen berdasarkan ID
            const dokumen = @json($dokumenList);
            const data = dokumen.find(d => d.id === dokumenId);

            if (!data) return;

            let content = `
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Informasi Siswa</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="150">NIS</td>
                            <td>: ${data.siswa.nis}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>: ${data.siswa.nama}</td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td>: ${data.siswa.kelas}</td>
                        </tr>
                        <tr>
                            <td>Jurusan</td>
                            <td>: ${data.siswa.jurusan}</td>
                        </tr>
                    </table>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-file-upload me-2"></i>CV & Portofolio
                    </h6>
                    ${data.status_cv_portofolio === 'sudah' ? `
                                                            <div class="alert alert-success mb-3">
                                                                <i class="fas fa-check-circle me-2"></i>
                                                                Diupload pada ${new Date(data.tanggal_upload_cv_portofolio).toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'})}
                                                            </div>
                                                            <a href="/admin/dokumen-siswa/${data.id}/download/cv" class="btn btn-sm btn-primary me-2" target="_blank">
                                                                <i class="fas fa-download me-1"></i> Download CV
                                                            </a>
                                                            <a href="/admin/dokumen-siswa/${data.id}/download/portofolio" class="btn btn-sm btn-primary" target="_blank">
                                                                <i class="fas fa-download me-1"></i> Download Portofolio
                                                            </a>
                                                        ` : `
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                Siswa belum mengupload CV dan Portofolio
                                                            </div>
                                                        `}
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-envelope me-2"></i>Surat Pernyataan
                    </h6>
                    ${data.status_surat_pernyataan === 'terkirim' ? `
                                                            <div class="alert alert-info mb-3">
                                                                <i class="fas fa-check-circle me-2"></i>
                                                                Terkirim pada ${new Date(data.tanggal_kirim_surat_pernyataan).toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric'})}
                                                            </div>
                                                            <a href="/admin/dokumen-siswa/${data.id}/download/surat_pernyataan" class="btn btn-sm btn-primary" target="_blank">
                                                                <i class="fas fa-download me-1"></i> Download Surat Pernyataan
                                                            </a>
                                                        ` : `
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                Surat pernyataan belum dikirim ke siswa
                                                            </div>
                                                            <button class="btn btn-sm btn-success" onclick="kirimSuratPernyataan(${data.id})" ${data.status_cv_portofolio !== 'sudah' ? 'disabled' : ''}>
                                                                <i class="fas fa-paper-plane me-1"></i> Kirim Surat Pernyataan
                                                            </button>
                                                        `}
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-camera me-2"></i>Eviden (Jawaban + Foto)
                    </h6>
                    ${data.status_eviden === 'sudah' ? `
                                                            <div class="alert alert-success mb-3">
                                                                <i class="fas fa-check-circle me-2"></i>
                                                                Diupload pada ${new Date(data.tanggal_upload_eviden).toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric'})}
                                                            </div>
                                                            <div class="mb-3">
                                                                <strong>Jawaban Siswa:</strong>
                                                                <p class="text-muted mt-2">${data.jawaban_surat_pernyataan || '-'}</p>
                                                            </div>
                                                            <a href="/admin/dokumen-siswa/${data.id}/download/surat_pernyataan_siswa" class="btn btn-sm btn-primary me-2 mb-2" target="_blank">
                                                                <i class="fas fa-download me-1"></i> Download Surat Pernyataan Siswa
                                                            </a>
                                                            <a href="/admin/dokumen-siswa/${data.id}/download/foto_ortu" class="btn btn-sm btn-primary mb-2" target="_blank">
                                                                <i class="fas fa-download me-1"></i> Download Foto dengan Ortu
                                                            </a>
                                                        ` : `
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                Siswa belum mengupload eviden
                                                            </div>
                                                        `}
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-file-signature me-2"></i>Surat Tugas
                    </h6>
                    ${data.status_surat_tugas === 'terkirim' ? `
                                                            <div class="alert alert-info mb-3">
                                                                <i class="fas fa-check-circle me-2"></i>
                                                                Terkirim pada ${new Date(data.tanggal_kirim_surat_tugas).toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric'})}
                                                            </div>
                                                            <a href="/admin/dokumen-siswa/${data.id}/download/surat_tugas" class="btn btn-sm btn-primary" target="_blank">
                                                                <i class="fas fa-download me-1"></i> Download Surat Tugas
                                                            </a>
                                                        ` : `
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                Surat tugas belum dikirim ke siswa
                                                            </div>
                                                            <button class="btn btn-sm btn-success" onclick="kirimSuratTugas(${data.id})" ${data.status_eviden !== 'sudah' ? 'disabled' : ''}>
                                                                <i class="fas fa-paper-plane me-1"></i> Kirim Surat Tugas
                                                            </button>
                                                        `}
                </div>
            `;

            document.getElementById('modalDetailContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('modalDetailDokumen')).show();
        }

        function kirimSuratPernyataan(dokumenId) {
            Swal.fire({
                title: 'Kirim Surat Pernyataan?',
                text: 'Surat pernyataan akan digenerate dan dikirim ke siswa',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/dokumen-siswa/${dokumenId}/kirim-surat-pernyataan`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#ee1c25'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan',
                                icon: 'error',
                                confirmButtonColor: '#ee1c25'
                            });
                        }
                    });
                }
            });
        }

        function kirimSuratTugas(dokumenId) {
            Swal.fire({
                title: 'Kirim Surat Tugas?',
                text: 'Surat tugas akan digenerate dan dikirim ke siswa',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/dokumen-siswa/${dokumenId}/kirim-surat-tugas`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#ee1c25'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan',
                                icon: 'error',
                                confirmButtonColor: '#ee1c25'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
