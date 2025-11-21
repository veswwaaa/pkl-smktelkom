@extends('layouts.admin')

@section('title', 'Surat DUDI')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <div>
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <h1>Surat Balasan Dudi</h1>
                <p>Kelola surat pengajuan dan balasan dari DUDI</p>
            </div>
        </div>
        <a href="/admin/dudi" class="back-btn">
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

        <!-- Action Buttons -->
        {{-- <div class="row mb-4">
            <div class="col-md-6">
                <a href="/admin/surat-pengajuan" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-paper-plane me-2"></i>Upload Surat Pengajuan PKL
                    <br><small>Kirim daftar siswa PKL ke DUDI</small>
                </a>
            </div>
            <div class="col-md-6">
                <a href="/admin/surat-permohonan" class="btn btn-warning btn-lg w-100 text-dark">
                    <i class="fas fa-file-upload me-2"></i>Upload Surat Permohonan Data
                    <br><small>Minta data jurusan/jobdesk dari DUDI</small>
                </a>
            </div>
        </div> --}}

        <!-- Info Card -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Informasi:</strong> Halaman ini menampilkan semua surat yang telah dikirim ke DUDI dan balasan dari
            DUDI.
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="suratTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pengajuan-tab" data-bs-toggle="tab" data-bs-target="#pengajuan"
                    type="button" role="tab">
                    <i class="fas fa-paper-plane me-2"></i>Surat Pengajuan PKL
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="permohonan-tab" data-bs-toggle="tab" data-bs-target="#permohonan"
                    type="button" role="tab">
                    <i class="fas fa-file-upload me-2"></i>Surat Permohonan Data
                </button>
            </li>
        </ul>

        <div class="tab-content" id="suratTabContent">
            <!-- Tab Pengajuan -->
            <div class="tab-pane fade show active" id="pengajuan" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Search Box -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="searchPengajuan" class="form-control"
                                        placeholder="Cari nama DUDI...">
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Ketik nama DUDI untuk mencari
                                </small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="tablePengajuan">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama DUDI</th>
                                        <th>Surat Pengajuan</th>
                                        <th>Status Balasan</th>
                                        <th>Surat Balasan</th>
                                        <th>Catatan DUDI</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($suratList as $index => $surat)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $surat->dudi->nama_dudi }}</strong><br>
                                                <small class="text-muted">{{ $surat->dudi->alamat }}</small>
                                            </td>
                                            <td>
                                                @if ($surat->file_surat_pengajuan)
                                                    @if ($surat->file_pengajuan_exists)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Terkirim
                                                        </span><br>
                                                        <small class="text-muted">
                                                            {{ $surat->tanggal_upload_pengajuan?->format('d M Y H:i') ?? '-' }}
                                                        </small>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>File Hilang
                                                        </span><br>
                                                        <small class="text-muted">Upload ulang diperlukan</small>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Belum Upload</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($surat->status_balasan_pengajuan)
                                                    @if ($surat->status_balasan_pengajuan == 'diterima')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Diterima
                                                        </span>
                                                    @elseif($surat->status_balasan_pengajuan == 'ditolak')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle me-1"></i>Ditolak
                                                        </span>
                                                    @endif
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $surat->tanggal_upload_balasan_pengajuan?->format('d M Y H:i') ?? '-' }}
                                                    </small>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i>Menunggu Balasan
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($surat->file_balasan_pengajuan)
                                                    @if ($surat->file_balasan_pengajuan_exists)
                                                        <a href="/admin/surat-dudi/{{ $surat->id }}/download?type=balasan-pengajuan"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>File Hilang
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($surat->catatan_balasan_pengajuan)
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#catatanPengajuanModal{{ $surat->id }}">
                                                        <i class="fas fa-eye me-1"></i>Lihat
                                                    </button>

                                                    <!-- Modal Catatan -->
                                                    <div class="modal fade" id="catatanPengajuanModal{{ $surat->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-info text-white">
                                                                    <h5 class="modal-title">
                                                                        <i class="fas fa-sticky-note me-2"></i>Catatan DUDI
                                                                    </h5>
                                                                    <button type="button"
                                                                        class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p><strong>DUDI:</strong> {{ $surat->dudi->nama_dudi }}
                                                                    </p>
                                                                    <hr>
                                                                    <p>{{ $surat->catatan_balasan_pengajuan }}</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete({{ $surat->id }}, '{{ $surat->dudi->nama_dudi }}')"
                                                    title="Hapus Surat">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada surat pengajuan yang dikirim ke DUDI</h5>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Permohonan -->
            <div class="tab-pane fade" id="permohonan" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Search Box -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-warning text-dark">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="searchPermohonan" class="form-control"
                                        placeholder="Cari nama DUDI...">
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Ketik nama DUDI untuk mencari
                                </small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="tablePermohonan">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama DUDI</th>
                                        <th>Surat Permohonan</th>
                                        <th>Status Balasan</th>
                                        <th>Surat Balasan</th>
                                        <th>Catatan DUDI</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($suratList as $index => $surat)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $surat->dudi->nama_dudi }}</strong><br>
                                                <small class="text-muted">{{ $surat->dudi->alamat }}</small>
                                            </td>
                                            <td>
                                                @if ($surat->file_surat_permohonan)
                                                    @if ($surat->file_permohonan_exists)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Terkirim
                                                        </span><br>
                                                        <small class="text-muted">
                                                            {{ $surat->tanggal_upload_permohonan?->format('d M Y H:i') ?? '-' }}
                                                        </small>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>File Hilang
                                                        </span><br>
                                                        <small class="text-muted">Upload ulang diperlukan</small>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Belum Upload</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($surat->status_balasan_permohonan)
                                                    @if ($surat->status_balasan_permohonan == 'diterima')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Diterima
                                                        </span>
                                                    @elseif($surat->status_balasan_permohonan == 'ditolak')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle me-1"></i>Ditolak
                                                        </span>
                                                    @endif
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $surat->tanggal_upload_balasan_permohonan?->format('d M Y H:i') ?? '-' }}
                                                    </small>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i>Menunggu Balasan
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($surat->file_balasan_permohonan)
                                                    @if ($surat->file_balasan_permohonan_exists)
                                                        <a href="/admin/surat-dudi/{{ $surat->id }}/download?type=balasan-permohonan"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>File Hilang
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($surat->catatan_balasan_permohonan)
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#catatanPermohonanModal{{ $surat->id }}">
                                                        <i class="fas fa-eye me-1"></i>Lihat
                                                    </button>

                                                    <!-- Modal Catatan -->
                                                    <div class="modal fade"
                                                        id="catatanPermohonanModal{{ $surat->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-info text-white">
                                                                    <h5 class="modal-title">
                                                                        <i class="fas fa-sticky-note me-2"></i>Catatan DUDI
                                                                    </h5>
                                                                    <button type="button"
                                                                        class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p><strong>DUDI:</strong> {{ $surat->dudi->nama_dudi }}
                                                                    </p>
                                                                    <hr>
                                                                    <p>{{ $surat->catatan_balasan_permohonan }}</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete({{ $surat->id }}, '{{ $surat->dudi->nama_dudi }}')"
                                                    title="Hapus Surat">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada surat permohonan yang dikirim ke DUDI</h5>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@else
    <span class="text-muted">-</span>
    @endif
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-danger"
            onclick="confirmDelete({{ $surat->id }}, '{{ $surat->dudi->nama_dudi }}')" title="Hapus Surat">
            <i class="fas fa-trash"></i>
        </button>
    </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-4">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada surat yang dikirim ke DUDI</h5>
        </td>
    </tr>
    @endforelse
    </tbody>
    </tbody>
    </table>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    <!-- Delete Form (hidden) -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        // Search functionality for Pengajuan
        document.getElementById('searchPengajuan').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#tablePengajuan tbody tr');

            tableRows.forEach(function(row) {
                const namaDudi = row.querySelector('td:nth-child(2)');
                if (namaDudi) {
                    const text = namaDudi.textContent.toLowerCase();
                    row.style.display = text.includes(searchValue) ? '' : 'none';
                }
            });
        });

        // Search functionality for Permohonan
        document.getElementById('searchPermohonan').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#tablePermohonan tbody tr');

            tableRows.forEach(function(row) {
                const namaDudi = row.querySelector('td:nth-child(2)');
                if (namaDudi) {
                    const text = namaDudi.textContent.toLowerCase();
                    row.style.display = text.includes(searchValue) ? '' : 'none';
                }
            });
        });

        function confirmDelete(id, namaDudi) {
            Swal.fire({
                title: 'Hapus Surat?',
                html: `Apakah Anda yakin ingin menghapus surat untuk DUDI <strong>${namaDudi}</strong>?<br><small class="text-muted">File surat pengajuan dan balasan akan dihapus permanen.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/admin/surat-dudi/${id}`;
                    form.submit();
                }
            });
        }

        // Show success/error messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
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
@endpush
