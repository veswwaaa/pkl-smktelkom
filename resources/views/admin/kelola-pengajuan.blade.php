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
        <div class="navbar-brand">
            <div class="telkom-logo">
                <i class="fas fa-graduation-cap fa-2x text-danger"></i>
            </div>
            <div class="brand-text">
                <h5 class="mb-0">SMK Telkom Banjarbaru</h5>
                <small class="text-muted">Sistem Manajemen PKL</small>
            </div>
        </div>

        <div class="navbar-right">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>

            <div class="dropdown">
                <button class="profile-dropdown dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </span>
                    <span>{{ $data->nama_admin ?? 'Admin' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-home me-2"></i>Dashboard</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
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
            <a href="#" class="sidebar-item" title="Laporan">
                <i class="fas fa-chart-bar"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h3><i class="fas fa-clipboard-list text-primary me-2"></i>Kelola Pengajuan PKL</h3>
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
                        <button type="submit" class="btn btn-primary w-100">
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

                                            if ($pilihanAktif == '1') {
                                                $dudi = $item->dudiPilihan1;
                                                $dudiMandiri = $item->dudiMandiriPilihan1;
                                                $isDudiMandiri =
                                                    $item->dudiPilihan1 == null && $item->dudiMandiriPilihan1 != null;
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
                                                    $item->dudiPilihan2 == null && $item->dudiMandiriPilihan2 != null;
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
                                                    $item->dudiPilihan3 == null && $item->dudiMandiriPilihan3 != null;
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
                                                    <i class="fas fa-exclamation-circle"></i> Akun DUDI belum dibuat
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
                                                {{-- DUDI Mandiri belum punya akun, tampilkan tombol Buat Akun DUDI --}}
                                                <button class="btn btn-sm btn-success"
                                                    title="Buat Akun DUDI & Approve"
                                                    onclick="confirmCreateDudiAccount({{ $dudiMandiri->id }}, '{{ $dudiMandiri->nama_dudi }}', {{ $item->id }}, '{{ $item->siswa->nama }}')">
                                                    <i class="fas fa-user-plus"></i> Buat Akun
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    title="Tolak (DUDI Tidak Setuju)"
                                                    onclick="confirmRejectDudiMandiri({{ $item->id }}, '{{ $item->siswa->nama }}', '{{ $dudiMandiri->nama_dudi }}')">
                                                    <i class="fas fa-times-circle"></i> Tolak
                                                </button>
                                            @else
                                                {{-- DUDI Sekolah atau DUDI Mandiri sudah punya akun --}}
                                                <button class="btn btn-sm btn-success" title="Approve & Kirim ke DUDI"
                                                    onclick="confirmApprove({{ $item->id }}, '{{ $item->siswa->nama }}', '{{ $dudi->nama_dudi ?? '' }}')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" title="Reject"
                                                    onclick="confirmReject({{ $item->id }}, '{{ $item->siswa->nama }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        @endif

                                        <button class="btn btn-sm btn-danger" title="Hapus"
                                            onclick="confirmDelete({{ $item->id }}, '{{ $item->siswa->nama }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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

        // View Detail
        function viewDetail(id) {
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();

            fetch(`/admin/pengajuan-pkl/${id}/detail`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const p = data.data;
                        const siswa = p.siswa;

                        // Debug - cek data
                        console.log('Data lengkap:', p);
                        console.log('DUDI Pilihan 1:', p.dudi_pilihan1);
                        console.log('DUDI Pilihan 2:', p.dudi_pilihan2);
                        console.log('DUDI Pilihan 3:', p.dudi_pilihan3);

                        // Get pilihan DUDI - gunakan snake_case sesuai JSON Laravel
                        const pilihan1 = p.dudi_pilihan1 ? p.dudi_pilihan1.nama_dudi : (p.dudi_mandiri_pilihan1 ?
                            p.dudi_mandiri_pilihan1.nama_dudi + ' (Mandiri)' : '-');
                        const pilihan2 = p.dudi_pilihan2 ? p.dudi_pilihan2.nama_dudi : (p.dudi_mandiri_pilihan2 ?
                            p.dudi_mandiri_pilihan2.nama_dudi + ' (Mandiri)' : '-');
                        const pilihan3 = p.dudi_pilihan3 ? p.dudi_pilihan3.nama_dudi : (p.dudi_mandiri_pilihan3 ?
                            p.dudi_mandiri_pilihan3.nama_dudi + ' (Mandiri)' : '-');

                        console.log('Pilihan 1 nama:', pilihan1);
                        console.log('Pilihan 2 nama:', pilihan2);
                        console.log('Pilihan 3 nama:', pilihan3);

                        // Function untuk membuat HTML detail DUDI Mandiri
                        function getDudiMandiriDetailHTML(dudiMandiri) {
                            if (!dudiMandiri) return '';

                            return `
                                <div class="dudi-mandiri-detail mt-2 p-3 border-start border-info border-3 bg-light">
                                    <div class="mb-2">
                                        <i class="fas fa-info-circle text-info me-1"></i>
                                        <strong class="text-info">Detail DUDI Mandiri:</strong>
                                    </div>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td width="35%" class="text-muted"><i class="fas fa-map-marker-alt me-1"></i> Alamat:</td>
                                            <td><strong>${dudiMandiri.alamat || '-'}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-phone me-1"></i> No. Telepon:</td>
                                            <td><strong>${dudiMandiri.nomor_telepon || '-'}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-user-tie me-1"></i> Person in Charge:</td>
                                            <td><strong>${dudiMandiri.person_in_charge || '-'}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            `;
                        }

                        document.getElementById('detailContent').innerHTML = `
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-primary"><i class="fas fa-building me-2"></i>Pilihan DUDI</h6>
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <th width="20%">Pilihan 1</th>
                                            <td>
                                                ${pilihan1} ${p.pilihan_aktif == '1' ? '<span class="badge bg-success">Aktif</span>' : ''}
                                                ${getDudiMandiriDetailHTML(p.dudi_mandiri_pilihan1)}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Pilihan 2</th>
                                            <td>
                                                ${pilihan2} ${p.pilihan_aktif == '2' ? '<span class="badge bg-success">Aktif</span>' : ''}
                                                ${getDudiMandiriDetailHTML(p.dudi_mandiri_pilihan2)}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Pilihan 3</th>
                                            <td>
                                                ${pilihan3} ${p.pilihan_aktif == '3' ? '<span class="badge bg-success">Aktif</span>' : ''}
                                                ${getDudiMandiriDetailHTML(p.dudi_mandiri_pilihan3)}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Pengajuan</th>
                                            <td>${new Date(p.tanggal_pengajuan).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td><span class="badge bg-${p.status == 'approved' ? 'success' : p.status == 'rejected' ? 'danger' : 'warning'}">${p.status.toUpperCase()}</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-primary"><i class="fas fa-cog me-2"></i>Ubah Pilihan Aktif</h6>
                                    <form id="formChangePilihan-${p.id}" onsubmit="changePilihan(event, ${p.id})">
                                        <div class="input-group">
                                            <select name="pilihan_aktif" class="form-select" id="selectPilihan-${p.id}">
                                                <option value="1" ${p.pilihan_aktif == '1' ? 'selected' : ''} ${pilihan1 === '-' ? 'disabled' : ''}>
                                                    Pilihan 1${pilihan1 !== '-' ? ': ' + pilihan1 : ' (Tidak diisi)'}
                                                </option>
                                                <option value="2" ${p.pilihan_aktif == '2' ? 'selected' : ''} ${pilihan2 === '-' ? 'disabled' : ''}>
                                                    Pilihan 2${pilihan2 !== '-' ? ': ' + pilihan2 : ' (Tidak diisi)'}
                                                </option>
                                                <option value="3" ${p.pilihan_aktif == '3' ? 'selected' : ''} ${pilihan3 === '-' ? 'disabled' : ''}>
                                                    Pilihan 3${pilihan3 !== '-' ? ': ' + pilihan3 : ' (Tidak diisi)'}
                                                </option>
                                            </select>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-sync-alt"></i> Ubah
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        `;
                    } else {
                        document.getElementById('detailContent').innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> ${data.message}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('detailContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan saat memuat data
                        </div>
                    `;
                });
        }

        // Change Pilihan Aktif
        function changePilihan(event, id) {
            event.preventDefault();

            const selectElement = document.getElementById(`selectPilihan-${id}`);
            const pilihanAktif = selectElement.value;

            Swal.fire({
                title: 'Ubah Pilihan Aktif?',
                text: `Pilihan aktif akan diubah ke Pilihan ${pilihanAktif}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Ubah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Mengubah...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(`/admin/pengajuan-pkl/${id}/change-pilihan`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                pilihan_aktif: pilihanAktif
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Pilihan aktif berhasil diubah',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload page to refresh data
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat mengubah pilihan',
                                confirmButtonColor: '#e53e3e'
                            });
                        });
                }
            });
        }

        // Confirm Approve
        function confirmApprove(id, nama, namaDudi) {
            Swal.fire({
                title: 'Approve & Kirim ke DUDI?',
                html: `
                    <div class="text-start">
                        <p>Anda akan menyetujui pengajuan PKL dari:</p>
                        <strong>${nama}</strong>
                        <p class="mt-2">Pengajuan akan dikirim ke:</p>
                        <strong class="text-primary">${namaDudi}</strong>
                        <hr>
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle"></i> Siswa akan otomatis ditempatkan ke DUDI dan pengajuan akan dikirim ke akun DUDI untuk diproses lebih lanjut.
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check"></i> Ya, Approve & Kirim!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/pengajuan-pkl/${id}/approve`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Confirm Reject
        function confirmReject(id, nama) {
            Swal.fire({
                title: 'Tolak Pengajuan?',
                html: `Anda akan menolak pengajuan PKL dari:<br><strong>${nama}</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/pengajuan-pkl/${id}/reject`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Confirm Create DUDI Account (untuk DUDI Mandiri)
        function confirmCreateDudiAccount(dudiMandiriId, namaDudi, pengajuanId, namaSiswa) {
            Swal.fire({
                title: 'Buat Akun DUDI & Approve?',
                html: `
                    <div class="text-start">
                        <p>Anda akan membuat akun untuk DUDI Mandiri:</p>
                        <strong>${namaDudi}</strong>
                        <p class="text-muted small mt-2">Diajukan oleh: ${namaSiswa}</p>
                        <hr>
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle"></i> <strong>Yang akan dilakukan:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Membuat data DUDI di sistem</li>
                                <li>Membuat akun login untuk DUDI</li>
                                <li>Approve pengajuan PKL siswa</li>
                                <li>Mengirim pengajuan ke DUDI</li>
                            </ul>
                        </div>
                        <div class="alert alert-warning small">
                            <i class="fas fa-exclamation-triangle"></i> Pastikan Anda sudah menghubungi DUDI dan mendapat persetujuan!
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-user-plus"></i> Ya, Buat Akun & Approve',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                width: '600px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Sedang membuat akun DUDI dan approve pengajuan PKL',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form untuk approve DUDI Mandiri
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/dudi-mandiri/${dudiMandiriId}/approve`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    // Tambahkan pengajuan_id untuk auto-approve setelah create akun
                    const pengajuanInput = document.createElement('input');
                    pengajuanInput.type = 'hidden';
                    pengajuanInput.name = 'pengajuan_id';
                    pengajuanInput.value = pengajuanId;

                    form.appendChild(csrfInput);
                    form.appendChild(pengajuanInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Confirm Reject DUDI Mandiri (DUDI tidak setuju)
        function confirmRejectDudiMandiri(pengajuanId, namaSiswa, namaDudi) {
            Swal.fire({
                title: 'Tolak Pengajuan DUDI Mandiri?',
                html: `
                    <div class="text-start">
                        <p>Anda akan menolak pengajuan PKL karena DUDI tidak setuju:</p>
                        <strong>Siswa:</strong> ${namaSiswa}<br>
                        <strong>DUDI Mandiri:</strong> ${namaDudi}
                        <hr>
                        <div class="alert alert-warning small">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Yang akan terjadi:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Pengajuan PKL siswa akan ditolak</li>
                                <li>Sistem akan otomatis pindah ke pilihan berikutnya (jika ada)</li>
                                <li>DUDI Mandiri tidak akan dibuatkan akun</li>
                            </ul>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Catatan (Opsional):</label>
                            <textarea id="catatanReject" class="form-control" rows="3" placeholder="Alasan penolakan dari DUDI..."></textarea>
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-times-circle"></i> Ya, Tolak!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                width: '600px',
                preConfirm: () => {
                    return {
                        catatan: document.getElementById('catatanReject').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Sedang menolak pengajuan PKL',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form untuk reject pengajuan
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/pengajuan-pkl/${pengajuanId}/reject`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    // Tambahkan catatan jika ada
                    const catatanInput = document.createElement('input');
                    catatanInput.type = 'hidden';
                    catatanInput.name = 'catatan';
                    catatanInput.value = result.value.catatan || 'DUDI Mandiri tidak menyetujui';

                    form.appendChild(csrfInput);
                    form.appendChild(catatanInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Confirm Delete
        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus Pengajuan?',
                html: `Anda akan menghapus pengajuan PKL dari:<br><strong>${nama}</strong><br><br><small class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/pengajuan-pkl/${id}`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>

</html>
