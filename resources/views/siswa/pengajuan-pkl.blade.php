<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan PKL - SMK Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard-siswa-new.css') }}">
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="hamburger-menu" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="telkom-logo">
                <img src="{{ asset('img/telkom-logo.png') }}" alt="Telkom Schools" onerror="this.style.display='none'">
            </div>
        </div>

        <div class="navbar-right">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
            </button>
            <div class="dropdown">
                <div class="user-avatar" data-bs-toggle="dropdown">
                    {{ substr($data->nama, 0, 1) }}
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header">{{ $data->nama }}</h6>
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

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Left Sidebar -->
    <div class="left-sidebar" id="leftSidebar">
        <div class="sidebar-menu">
            <a href="/dashboard" class="sidebar-item" title="Dashboard">
                <i class="fas fa-th-large"></i>
            </a>
            <a href="/siswa/pengajuan-pkl" class="sidebar-item active" title="Pengajuan PKL">
                <i class="fas fa-file-alt"></i>
            </a>
            <a href="/siswa/status-pengajuan" class="sidebar-item" title="Status Pengajuan PKL">
                <i class="fas fa-tasks"></i>
            </a>
            <a href="/siswa/info-pkl" class="sidebar-item" title="Info PKL">
                <i class="fas fa-info-circle"></i>
            </a>
            <a href="/siswa/dokumen-pkl" class="sidebar-item" title="Dokumen PKL">
                <i class="fas fa-folder-open"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Pengajuan PKL</h2>

        <!-- Success/Error Messages -->
        <div id="successMessage" data-message="{{ session('success') }}" style="display:none;"></div>
        <div id="errorMessage" data-message="{{ session('error') }}" style="display:none;"></div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" id="pengajuanTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="data-individu-tab" data-bs-toggle="tab"
                    data-bs-target="#data-individu" type="button">
                    Data Pengajuan DUDI Mandiri
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="data-pkl-tab" data-bs-toggle="tab" data-bs-target="#data-pkl"
                    type="button">
                    Data Pengajuan PKL
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pengajuanTabsContent">
            <!-- Tab 1: Data Pengajuan DUDI Mandiri (Sekarang tampil pertama) -->
            <div class="tab-pane fade show active" id="data-individu" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Data Pengajuan DUDI Mandiri</h5>
                        <p class="text-muted">Tambahkan DUDI yang belum terdaftar di sistem sekolah</p>

                        <form action="/siswa/dudi-mandiri" method="POST" id="formDudiMandiri">
                            @csrf

                            <div class="mb-3">
                                <label for="nama_dudi" class="form-label">Nama DUDI</label>
                                <input type="text" class="form-control" id="nama_dudi" name="nama_dudi" required>
                            </div>

                            <div class="mb-3">
                                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="person_in_charge" class="form-label">Nama Person (PIC)</label>
                                <input type="text" class="form-control" id="person_in_charge"
                                    name="person_in_charge" required>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="4" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Data Pengajuan PKL (Sekarang tampil kedua) -->
            <div class="tab-pane fade" id="data-pkl" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Data Pengajuan PKL</h5>

                        @if ($pengajuan)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Anda sudah mengajukan PKL. Status:
                                <strong>{{ ucfirst($pengajuan->status) }}</strong>
                            </div>
                        @else
                            <!-- Info Siswa -->
                            <div class="alert alert-primary mb-4">
                                <h6 class="mb-2"><i class="fas fa-user me-2"></i>Informasi Pengaju</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>NIS:</strong> {{ $data->nis }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Nama:</strong> {{ $data->nama }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Kelas:</strong> {{ $data->kelas }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Jurusan:</strong> {{ $data->jurusan }}
                                    </div>
                                </div>
                            </div>

                            <form action="/siswa/pengajuan-pkl" method="POST" id="formPengajuanPkl">
                                @csrf

                                <!-- Pilihan 1 -->
                                <div class="mb-3">
                                    <label class="form-label">Pilihan 1</label>
                                    <select class="form-select" id="pilihan1" name="pilihan_1" required>
                                        <option value="">Pilih DUDI</option>
                                        <optgroup label="🏫 DUDI Sekolah">
                                            @foreach ($dudiSekolah as $dudi)
                                                <option value="sekolah-{{ $dudi->id }}">
                                                    {{ $dudi->nama_dudi }}</option>
                                            @endforeach
                                        </optgroup>
                                        @if ($dudiMandiriApproved->count() > 0)
                                            <optgroup label="👨‍🎓 DUDI Mandiri (Approved)">
                                                @foreach ($dudiMandiriApproved as $dudi)
                                                    <option value="sekolah-{{ $dudi->id }}">
                                                        {{ $dudi->nama_dudi }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                        @if ($dudiMandiri->count() > 0)
                                            <optgroup label="⏳ DUDI Mandiri Anda (Belum Approved)">
                                                @foreach ($dudiMandiri as $dudi)
                                                    <option value="mandiri-{{ $dudi->id }}">
                                                        {{ $dudi->nama_dudi }} (Pilihan Mandiri)</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                </div>

                                <!-- Pilihan 2 -->
                                <div class="mb-3">
                                    <label class="form-label">Pilihan 2</label>
                                    <select class="form-select" id="pilihan2" name="pilihan_2" required>
                                        <option value="">Pilih DUDI</option>
                                        <optgroup label="🏫 DUDI Sekolah">
                                            @foreach ($dudiSekolah as $dudi)
                                                <option value="sekolah-{{ $dudi->id }}">
                                                    {{ $dudi->nama_dudi }}</option>
                                            @endforeach
                                        </optgroup>
                                        @if ($dudiMandiriApproved->count() > 0)
                                            <optgroup label="👨‍🎓 DUDI Mandiri (Approved)">
                                                @foreach ($dudiMandiriApproved as $dudi)
                                                    <option value="sekolah-{{ $dudi->id }}">
                                                        {{ $dudi->nama_dudi }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                        @if ($dudiMandiri->count() > 0)
                                            <optgroup label="⏳ DUDI Mandiri Anda (Belum Approved)">
                                                @foreach ($dudiMandiri as $dudi)
                                                    <option value="mandiri-{{ $dudi->id }}">
                                                        {{ $dudi->nama_dudi }} (Menunggu Persetujuan)</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                </div>

                                <!-- Pilihan 3 -->
                                <div class="mb-3">
                                    <label class="form-label">Pilihan 3</label>
                                    <select class="form-select" id="pilihan3" name="pilihan_3" required>
                                        <option value="">Pilih DUDI</option>
                                        <optgroup label="🏫 DUDI Sekolah">
                                            @foreach ($dudiSekolah as $dudi)
                                                <option value="sekolah-{{ $dudi->id }}">
                                                    {{ $dudi->nama_dudi }}</option>
                                            @endforeach
                                        </optgroup>
                                        @if ($dudiMandiriApproved->count() > 0)
                                            <optgroup label="👨‍🎓 DUDI Mandiri (Approved)">
                                                @foreach ($dudiMandiriApproved as $dudi)
                                                    <option value="sekolah-{{ $dudi->id }}">
                                                        {{ $dudi->nama_dudi }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                        @if ($dudiMandiri->count() > 0)
                                            <optgroup label="⏳ DUDI Mandiri Anda (Belum Disetujui Admin)">
                                                @foreach ($dudiMandiri as $dudi)
                                                    <option value="mandiri-{{ $dudi->id }}">
                                                        {{ $dudi->nama_dudi }} (Menunggu Persetujuan)</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/pengajuan-pkl.js') }}"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('leftSidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 768) toggleSidebar();
            });
        });
    </script>
</body>

</html>
