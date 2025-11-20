<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengajuan PKL - SMK Telkom Banjarbaru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/kelola-dudi.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex align-items-center justify-content-between">
        <!-- Logo dan Brand -->
        <div class="telkom-logo">
            <img src="{{ asset('img/telkom-logo.png') }}" alt="Telkom Logo" height="40">
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
                    <div class="profile-avatar">A</div>
                    <i class="fas fa-chevron-down text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="/logout"><i
                                class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Left Sidebar -->
    <div class="left-sidebar">
        <div class="sidebar-menu">
            <a href="/dashboard" class="sidebar-item" title="Dashboard">
                <i class="fas fa-home"></i>
            </a>
            <a href="/admin/dudi" class="sidebar-item" title="Kelola DUDI">
                <i class="fas fa-building"></i>
            </a>
            <a href="/admin/siswa" class="sidebar-item" title="Kelola Siswa">
                <i class="fas fa-users"></i>
            </a>
            <a href="/admin/pengajuan-pkl" class="sidebar-item active" title="Pengajuan PKL">
                <i class="fas fa-clipboard-list"></i>
            </a>
            <a href="/admin/surat-permohonan"
            class="sidebar-item {{ request()->is('admin/surat-permohonan*') ? 'active' : '' }}"
            title="Surat Permohonan Data">
            <i class="fas fa-file-invoice"></i>
            </a>
            <a href="/admin/surat-pengajuan"
                class="sidebar-item {{ request()->is('admin/surat-pengajuan*') ? 'active' : '' }}"
                title="Surat Pengajuan PKL">
                <i class="fas fa-file-export"></i>
            </a>
            <a href="/admin/surat-dudi" class="sidebar-item" title="Surat DUDI">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h3><i class="fas fa-clipboard-list text-danger me-2"></i>Kelola Pengajuan PKL</h3>
                <p class="text-muted mb-0">Kelola pengajuan PKL dari siswa</p>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="/admin/pengajuan-pkl" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach (['XIIA', 'XIIB', 'XIIC', 'XIID', 'XIIE', 'XIIF', 'XIIG'] as $kelas)
                                <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                    {{ $kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cari (Nama/NIS)</label>
                        <input type="text" name="search" class="form-control" placeholder="Ketik nama atau NIS..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Pilihan Aktif</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengajuan as $index => $item)
                                @if ($item->siswa)
                                    <tr>
                                        <td>{{ $pengajuan->firstItem() + $index }}</td>
                                        <td>{{ $item->siswa->nis }}</td>
                                        <td>{{ $item->siswa->nama }}</td>
                                        <td>{{ $item->siswa->kelas }}</td>
                                        <td><span class="badge bg-info">{{ $item->siswa->jurusan }}</span></td>
                                        <td>
                                            @php
                                                $pilihanAktif = $item->pilihan_aktif;
                                                $dudi = null;
                                                $dudiMandiri = null;
                                                $isDudiMandiri = false;
                                                $dudiMandiriHasAccount = false;

                                                // Definisikan nama untuk semua pilihan
                                                $pilihan1Nama = $item->dudiPilihan1
                                                    ? $item->dudiPilihan1->nama_dudi
                                                    : ($item->dudiMandiriPilihan1
                                                        ? $item->dudiMandiriPilihan1->nama_dudi . ' (Mandiri)'
                                                        : '-');
                                                $pilihan2Nama = $item->dudiPilihan2
                                                    ? $item->dudiPilihan2->nama_dudi
                                                    : ($item->dudiMandiriPilihan2
                                                        ? $item->dudiMandiriPilihan2->nama_dudi . ' (Mandiri)'
                                                        : '-');
                                                $pilihan3Nama = $item->dudiPilihan3
                                                    ? $item->dudiPilihan3->nama_dudi
                                                    : ($item->dudiMandiriPilihan3
                                                        ? $item->dudiMandiriPilihan3->nama_dudi . ' (Mandiri)'
                                                        : '-');

                                                if ($pilihanAktif == '1') {
                                                    $dudi = $item->dudiPilihan1;
                                                    $dudiMandiri = $item->dudiMandiriPilihan1;
                                                    $isDudiMandiri =
                                                        $item->dudiPilihan1 == null &&
                                                        $item->dudiMandiriPilihan1 != null;
                                                    if ($isDudiMandiri && $dudiMandiri) {
                                                        $dudiMandiriHasAccount = $dudiMandiri->id_dudi != null;
                                                        // Jika DUDI Mandiri sudah punya akun, ambil data DUDI-nya
                                                        if ($dudiMandiriHasAccount) {
                                                            $dudi = $dudiMandiri->dudi;
                                                        } else {
                                                            $dudi = $dudiMandiri;
                                                        }
                                                    }
                                                } elseif ($pilihanAktif == '2') {
                                                    $dudi = $item->dudiPilihan2;
                                                    $dudiMandiri = $item->dudiMandiriPilihan2;
                                                    $isDudiMandiri =
                                                        $item->dudiPilihan2 == null &&
                                                        $item->dudiMandiriPilihan2 != null;
                                                    if ($isDudiMandiri && $dudiMandiri) {
                                                        $dudiMandiriHasAccount = $dudiMandiri->id_dudi != null;
                                                        if ($dudiMandiriHasAccount) {
                                                            $dudi = $dudiMandiri->dudi;
                                                        } else {
                                                            $dudi = $dudiMandiri;
                                                        }
                                                    }
                                                } else {
                                                    $dudi = $item->dudiPilihan3;
                                                    $dudiMandiri = $item->dudiMandiriPilihan3;
                                                    $isDudiMandiri =
                                                        $item->dudiPilihan3 == null &&
                                                        $item->dudiMandiriPilihan3 != null;
                                                    if ($isDudiMandiri && $dudiMandiri) {
                                                        $dudiMandiriHasAccount = $dudiMandiri->id_dudi != null;
                                                        if ($dudiMandiriHasAccount) {
                                                            $dudi = $dudiMandiri->dudi;
                                                        } else {
                                                            $dudi = $dudiMandiri;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <strong>{{ $dudi->nama_dudi ?? '-' }}</strong>
                                            @if ($isDudiMandiri)
                                                <span class="badge bg-warning text-dark ms-1"
                                                    style="font-size: 0.65rem;">Mandiri</span>
                                                @if (!$dudiMandiriHasAccount)
                                                    <br><small class="text-danger" style="font-size: 0.75rem;">
                                                        <i class="fas fa-exclamation-circle"></i> Akun DUDI belum
                                                        dibuat
                                                    </small>
                                                @else
                                                    <br><small class="text-success" style="font-size: 0.75rem;">
                                                        <i class="fas fa-check-circle"></i> Akun sudah dibuat
                                                    </small>
                                                @endif
                                            @endif
                                            <br>
                                            <small class="badge bg-primary" style="font-size: 0.7rem;">Pilihan
                                                {{ $pilihanAktif }}</small>
                                        </td>
                                        <td>{{ date('d M Y', strtotime($item->tanggal_pengajuan)) }}</td>
                                        <td>
                                            @if ($item->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($item->status == 'diproses')
                                                <span class="badge bg-info">Diproses</span>
                                            @elseif($item->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="Lihat Detail"
                                                onclick="viewDetail({{ $item->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            @if ($item->status == 'pending' || $item->status == 'diproses')
                                                @if ($isDudiMandiri && !$dudiMandiriHasAccount)
                                                    {{-- DUDI Mandiri belum punya akun, tampilkan tombol Buat Akun & Tidak Bersedia --}}
                                                    <button class="btn btn-sm btn-success"
                                                        title="DUDI bersedia jadi tempat PKL, buat akun"
                                                        onclick="confirmCreateDudiAccount({{ $dudiMandiri->id }}, '{{ $dudiMandiri->nama_dudi }}', {{ $item->id }}, '{{ $item->siswa->nama }}')">
                                                        <i class="fas fa-check-circle"></i> DUDI Bersedia
                                                    </button>
                                                    <button class="btn btn-sm btn-danger"
                                                        title="DUDI tidak bersedia jadi tempat PKL"
                                                        onclick="confirmDudiTidakBersedia({{ $item->id }}, '{{ $item->siswa->nama }}', '{{ $dudiMandiri->nama_dudi }}')">
                                                        <i class="fas fa-times-circle"></i> Tidak Bersedia
                                                    </button>
                                                @elseif ($isDudiMandiri && $dudiMandiriHasAccount)
                                                    {{-- DUDI Mandiri sudah punya akun - tampilkan tombol approve dan tolak --}}
                                                    <button class="btn btn-sm btn-success"
                                                        title="DUDI menyetujui siswa"
                                                        onclick="confirmApprove({{ $item->id }}, '{{ $item->siswa->nama }}', '{{ $dudi->nama_dudi ?? '' }}')">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" title="DUDI tidak setuju"
                                                        onclick="confirmRejectDudiMandiri({{ $item->id }}, '{{ $item->siswa->nama }}', '{{ $dudi->nama_dudi ?? '' }}')">
                                                        <i class="fas fa-times-circle"></i> DUDI Tolak
                                                    </button>
                                                @else
                                                    {{-- Tampilkan button approve/reject normal --}}
                                                    <button class="btn btn-sm btn-success"
                                                        title="Approve & Kirim ke DUDI"
                                                        onclick="confirmApprove({{ $item->id }}, '{{ $item->siswa->nama }}', '{{ $dudi->nama_dudi ?? '' }}')">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                    <button class="btn btn-sm btn-warning" title="Reject"
                                                        onclick="confirmReject({{ $item->id }}, '{{ $item->siswa->nama }}')">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                @endif
                                            @endif

                                            <button class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="confirmDelete({{ $item->id }}, '{{ $item->siswa->nama }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada pengajuan PKL</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan {{ $pengajuan->firstItem() ?? 0 }} - {{ $pengajuan->lastItem() ?? 0 }} dari
                        {{ $pengajuan->total() }} data
                    </div>
                    <div>
                        {{ $pengajuan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle"></i> Detail Pengajuan PKL
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p>Loading...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/kelola-pengajuan.js') }}"></script>

    <!-- SweetAlert Notifications -->
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                html: '{!! session('success') !!}',
                timer: 5000,
                showConfirmButton: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#e53e3e'
            });
        @endif
    </script>
</body>

</html>
