<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Wali Kelas - SMK Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/wali-kelas.css') }}" rel="stylesheet">
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
                <span class="notification-badge">0</span>
            </button>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <button class="profile-dropdown" type="button" data-bs-toggle="dropdown">
                    <div class="profile-avatar">W</div>
                    <i class="fas fa-chevron-down text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
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
            <a href="#" class="sidebar-item active" title="Dashboard">
                <i class="fas fa-th-large"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Header -->
        <div class="welcome-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1>Selamat Datang Di Dashboard PKL</h1>
                    <p>Kelola program Praktik Kerja Lapangan SMK Telkom Banjarbaru dengan mudah dan efisien</p>
                </div>
                <div class="user-avatars">
                    <div class="user-avatar avatar-orange">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-avatar avatar-green">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="user-avatar avatar-gray">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-avatar avatar-blue">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Siswa Section -->
        <div class="content-card">
            <div class="card-header-custom">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users text-light me-2"></i>
                    <h5 class="mb-0 text-light">Daftar Siswa</h5>
                </div>
            </div>

            {{-- <!-- Filter Section -->
            <div class="filter-section">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select class="form-select" id="filterKelas">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelasList as $kls)
                                <option value="{{ $kls }}">{{ $kls }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterJurusan">
                            <option value="">Semua Jurusan</option>
                            @foreach ($jurusanList as $jrs)
                                <option value="{{ $jrs }}">{{ $jrs }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="belum">Belum Ditempatkan</option>
                            <option value="ditempatkan">Sudah Ditempatkan</option>
                            <option value="selesai">Selesai PKL</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="searchSiswa" placeholder="Cari nama atau NIS...">
                    </div>
                </div>
            </div> --}}

            <!-- Table Section -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jenis Kelamin</th>
                            <th>Angkatan</th>
                            <th>Jurusan</th>
                            <th>Status</th>
                            <th>DUDI</th>
                        </tr>
                    </thead>
                    <tbody id="siswaTableBody">
                        @forelse($siswa as $index => $s)
                            <tr data-kelas="{{ $s->kelas }}" data-jurusan="{{ $s->jurusan }}"
                                data-status="{{ $s->status_penempatan ?? 'belum' }}"
                                data-search="{{ strtolower($s->nama . ' ' . $s->nis) }}">
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge bg-primary">{{ $s->nis }}</span></td>
                                <td>{{ $s->nama }}</td>
                                <td>{{ $s->kelas }}</td>
                                <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ $s->angkatan }}</td>
                                <td>{{ $s->jurusan }}</td>
                                <td>
                                    @if ($s->status_penempatan == 'ditempatkan')
                                        <span class="badge bg-success">Ditempatkan</span>
                                    @elseif($s->status_penempatan == 'selesai')
                                        <span class="badge bg-info">Selesai</span>
                                    @else
                                        <span class="badge bg-warning">Belum Ditempatkan</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($s->dudi)
                                        <span class="text-success">
                                            <i class="fas fa-building me-1"></i>{{ $s->dudi->nama_dudi }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    Belum ada data siswa
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Summary Info -->
            <div class="summary-section">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="summary-info">
                                <h6>Total Siswa</h6>
                                <h4>{{ $siswa->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="summary-info">
                                <h6>Ditempatkan</h6>
                                <h4>{{ $siswa->where('status_penempatan', 'ditempatkan')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="summary-info">
                                <h6>Belum Ditempatkan</h6>
                                <h4>{{ $siswa->where('status_penempatan', 'belum')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-card">
                            <div class="summary-icon bg-info">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="summary-info">
                                <h6>Selesai</h6>
                                <h4>{{ $siswa->where('status_penempatan', 'selesai')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterKelas = document.getElementById('filterKelas');
            const filterJurusan = document.getElementById('filterJurusan');
            const filterStatus = document.getElementById('filterStatus');
            const searchSiswa = document.getElementById('searchSiswa');
            const tableRows = document.querySelectorAll('#siswaTableBody tr[data-kelas]');

            function filterTable() {
                const kelasValue = filterKelas.value.toLowerCase();
                const jurusanValue = filterJurusan.value.toLowerCase();
                const statusValue = filterStatus.value.toLowerCase();
                const searchValue = searchSiswa.value.toLowerCase();

                tableRows.forEach(row => {
                    const kelas = row.dataset.kelas.toLowerCase();
                    const jurusan = row.dataset.jurusan.toLowerCase();
                    const status = row.dataset.status.toLowerCase();
                    const searchData = row.dataset.search.toLowerCase();

                    const kelasMatch = !kelasValue || kelas === kelasValue;
                    const jurusanMatch = !jurusanValue || jurusan === jurusanValue;
                    const statusMatch = !statusValue || status === statusValue;
                    const searchMatch = !searchValue || searchData.includes(searchValue);

                    if (kelasMatch && jurusanMatch && statusMatch && searchMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update row numbers
                let visibleIndex = 1;
                tableRows.forEach(row => {
                    if (row.style.display !== 'none') {
                        row.querySelector('td:first-child').textContent = visibleIndex++;
                    }
                });
            }

            filterKelas.addEventListener('change', filterTable);
            filterJurusan.addEventListener('change', filterTable);
            filterStatus.addEventListener('change', filterTable);
            searchSiswa.addEventListener('input', filterTable);
        });
    </script>
</body>

</html>
