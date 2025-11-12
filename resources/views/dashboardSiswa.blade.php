<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - PKL SMK Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard-siswa.css') }}">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-dark text-white min-vh-100 p-3">
                <h4 class="mb-4">
                    <i class="fas fa-graduation-cap me-2"></i>PKL SMK Telkom
                </h4>
                <hr class="bg-white">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="/dashboard" class="nav-link text-white active">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="/siswa/pengajuan-pkl" class="nav-link text-white">
                            <i class="fas fa-file-alt me-2"></i> Pengajuan PKL
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="/siswa/info-pkl" class="nav-link text-white">
                            <i class="fas fa-briefcase me-2"></i> Informasi PKL
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link text-white">
                            <i class="fas fa-file-upload me-2"></i> Upload Dokumen
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="/logout" class="nav-link text-white">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2>Dashboard Siswa</h2>
                        <p class="text-muted">Selamat datang, <strong>{{ $data->nama }}</strong></p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">NIS: {{ $data->nis }}</small><br>
                        <small class="text-muted">Kelas: {{ $data->kelas }} - {{ $data->jurusan }}</small>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card info-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-primary">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 text-muted">Status Siswa</h6>
                                        <h4 class="mb-0">Aktif</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card info-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-success">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 text-muted">Status PKL</h6>
                                        <h4 class="mb-0">
                                            @if ($data->status_penempatan == 'ditempatkan')
                                                Ditempatkan
                                            @elseif($data->status_penempatan == 'selesai')
                                                Selesai
                                            @else
                                                Belum PKL
                                            @endif
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card info-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-warning">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 text-muted">Angkatan</h6>
                                        <h4 class="mb-0">{{ $data->angkatan }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Menu Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="/siswa/pengajuan-pkl" class="quick-action-btn">
                                    <i class="fas fa-file-alt"></i>
                                    <span>Pengajuan PKL</span>
                                    <small>Ajukan tempat PKL</small>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#" class="quick-action-btn">
                                    <i class="fas fa-upload"></i>
                                    <span>Upload CV</span>
                                    <small>Upload CV & Portfolio</small>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#" class="quick-action-btn">
                                    <i class="fas fa-eye"></i>
                                    <span>Lihat DUDI</span>
                                    <small>Daftar DUDI tersedia</small>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#" class="quick-action-btn">
                                    <i class="fas fa-download"></i>
                                    <span>Download Surat</span>
                                    <small>Surat tugas & pernyataan</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Pengajuan PKL -->
                @if ($pengajuan)
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Status Pengajuan PKL</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Status Keseluruhan: </strong>
                                @if ($pengajuan->status == 'pending')
                                    <span class="badge bg-warning text-dark">PENDING</span> - Menunggu respon dari DUDI
                                @elseif($pengajuan->status == 'approved')
                                    <span class="badge bg-success">DITERIMA</span> - Selamat! Anda telah diterima PKL
                                @else
                                    <span class="badge bg-danger">DITOLAK</span> - Semua pilihan telah ditolak
                                @endif
                            </div>

                            <div class="row">
                                <!-- Pilihan 1 -->
                                @php
                                    $dudi1 = $pengajuan->dudiPilihan1 ?? $pengajuan->dudiMandiriPilihan1;
                                @endphp
                                @if ($dudi1)
                                    <div class="col-md-4 mb-3">
                                        <div
                                            class="card {{ $pengajuan->pilihan_aktif == '1' ? 'border-primary' : '' }}">
                                            <div
                                                class="card-header {{ $pengajuan->pilihan_aktif == '1' ? 'bg-primary text-white' : 'bg-light' }}">
                                                <h6 class="mb-0">
                                                    Pilihan 1
                                                    @if ($pengajuan->pilihan_aktif == '1')
                                                        <span class="badge bg-warning text-dark float-end">Sedang
                                                            Diproses</span>
                                                    @endif
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <h6>{{ $dudi1->nama_dudi }}</h6>
                                                <p class="text-muted small mb-2">{{ $dudi1->alamat ?? '-' }}</p>

                                                @if ($pengajuan->status_pilihan_1 == 'pending')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock"></i> Menunggu
                                                    </span>
                                                @elseif($pengajuan->status_pilihan_1 == 'approved')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Diterima
                                                    </span>
                                                    <br><small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($pengajuan->tanggal_response_pilihan_1)->format('d M Y') }}</small>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times"></i> Ditolak
                                                    </span>
                                                    <br><small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($pengajuan->tanggal_response_pilihan_1)->format('d M Y') }}</small>
                                                    @if ($pengajuan->catatan_pilihan_1)
                                                        <div class="alert alert-warning alert-sm mt-2 p-2">
                                                            <small><strong>Catatan:</strong>
                                                                {{ $pengajuan->catatan_pilihan_1 }}</small>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Pilihan 2 -->
                                @php
                                    $dudi2 = $pengajuan->dudiPilihan2 ?? $pengajuan->dudiMandiriPilihan2;
                                @endphp
                                @if ($dudi2)
                                    <div class="col-md-4 mb-3">
                                        <div
                                            class="card {{ $pengajuan->pilihan_aktif == '2' ? 'border-primary' : '' }}">
                                            <div
                                                class="card-header {{ $pengajuan->pilihan_aktif == '2' ? 'bg-primary text-white' : 'bg-light' }}">
                                                <h6 class="mb-0">
                                                    Pilihan 2
                                                    @if ($pengajuan->pilihan_aktif == '2')
                                                        <span class="badge bg-warning text-dark float-end">Sedang
                                                            Diproses</span>
                                                    @endif
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <h6>{{ $dudi2->nama_dudi }}</h6>
                                                <p class="text-muted small mb-2">{{ $dudi2->alamat ?? '-' }}</p>

                                                @if ($pengajuan->status_pilihan_2 == 'pending')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock"></i> Menunggu
                                                    </span>
                                                @elseif($pengajuan->status_pilihan_2 == 'approved')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Diterima
                                                    </span>
                                                    <br><small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($pengajuan->tanggal_response_pilihan_2)->format('d M Y') }}</small>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times"></i> Ditolak
                                                    </span>
                                                    <br><small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($pengajuan->tanggal_response_pilihan_2)->format('d M Y') }}</small>
                                                    @if ($pengajuan->catatan_pilihan_2)
                                                        <div class="alert alert-warning alert-sm mt-2 p-2">
                                                            <small><strong>Catatan:</strong>
                                                                {{ $pengajuan->catatan_pilihan_2 }}</small>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Pilihan 3 -->
                                @php
                                    $dudi3 = $pengajuan->dudiPilihan3 ?? $pengajuan->dudiMandiriPilihan3;
                                @endphp
                                @if ($dudi3)
                                    <div class="col-md-4 mb-3">
                                        <div
                                            class="card {{ $pengajuan->pilihan_aktif == '3' ? 'border-primary' : '' }}">
                                            <div
                                                class="card-header {{ $pengajuan->pilihan_aktif == '3' ? 'bg-primary text-white' : 'bg-light' }}">
                                                <h6 class="mb-0">
                                                    Pilihan 3
                                                    @if ($pengajuan->pilihan_aktif == '3')
                                                        <span class="badge bg-warning text-dark float-end">Sedang
                                                            Diproses</span>
                                                    @endif
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <h6>{{ $dudi3->nama_dudi }}</h6>
                                                <p class="text-muted small mb-2">{{ $dudi3->alamat ?? '-' }}</p>

                                                @if ($pengajuan->status_pilihan_3 == 'pending')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock"></i> Menunggu
                                                    </span>
                                                @elseif($pengajuan->status_pilihan_3 == 'approved')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Diterima
                                                    </span>
                                                    <br><small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($pengajuan->tanggal_response_pilihan_3)->format('d M Y') }}</small>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times"></i> Ditolak
                                                    </span>
                                                    <br><small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($pengajuan->tanggal_response_pilihan_3)->format('d M Y') }}</small>
                                                    @if ($pengajuan->catatan_pilihan_3)
                                                        <div class="alert alert-warning alert-sm mt-2 p-2">
                                                            <small><strong>Catatan:</strong>
                                                                {{ $pengajuan->catatan_pilihan_3 }}</small>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="alert alert-light mt-3">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <strong>Informasi:</strong> Pilihan yang sedang diproses ditandai dengan border biru.
                                Jika ditolak, sistem akan otomatis memproses pilihan berikutnya.
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quick Link ke Info PKL (jika sudah ditempatkan) -->
                @if ($data->status_penempatan == 'ditempatkan' || $data->status_penempatan == 'selesai')
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>PKL Anda Sudah Ditempatkan!
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="mb-2">
                                        <i class="fas fa-building text-primary me-2"></i>
                                        {{ $data->dudi ? $data->dudi->nama_dudi : '-' }}
                                    </h5>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                        {{ $data->dudi ? $data->dudi->alamat : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="/siswa/info-pkl" class="btn btn-success btn-lg">
                                        <i class="fas fa-arrow-right me-2"></i>
                                        Lihat Detail PKL
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                            <h4>Belum Ada Penempatan PKL</h4>
                            <p class="text-muted">Silakan ajukan tempat PKL melalui menu Pengajuan PKL</p>
                            <a href="/siswa/pengajuan-pkl" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Ajukan Sekarang
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/dashboard-siswa.js') }}"></script>
</body>

</html>
