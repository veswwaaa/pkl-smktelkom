<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola DUDI - SMK Telkom</title>
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
        <div class="navbar-brand">
            <div class="telkom-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="brand-text">
                <h5>Telkom</h5>
                <small>Schools</small>
            </div>
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
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
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
            <a href="#" class="sidebar-item" title="Menu">
                <i class="fas fa-th"></i>
            </a>
            <a href="#" class="sidebar-item" title="Data Siswa">
                <i class="fas fa-users"></i>
            </a>
            <a href="/admin/dudi" class="sidebar-item active" title="Kelola DUDI">
                <i class="fas fa-building"></i>
            </a>
            <a href="#" class="sidebar-item" title="Tasks">
                <i class="fas fa-tasks"></i>
            </a>
            <a href="#" class="sidebar-item" title="Reports">
                <i class="fas fa-chart-bar"></i>
            </a>
            <a href="#" class="sidebar-item" title="Upload">
                <i class="fas fa-upload"></i>
            </a>
            <a href="#" class="sidebar-item" title="Share">
                <i class="fas fa-share-alt"></i>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <div>
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <h1>Kelola DUDI</h1>
                    <p>Kelola data DUDI yang bermitra dengan SMK Telkom Banjarbaru</p>
                </div>
            </div>
            <a href="/dashboard" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Ada kesalahan pada input:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Data Table -->
        <div class="data-table-card">
            <div class="table-header">
                <div class="table-header-content">
                    <div class="table-title">
                        <i class="fas fa-table"></i>
                        <h5>Daftar DUDI</h5>
                    </div>
                    <button class="add-btn" onclick="showAddModal()">
                        <i class="fas fa-plus"></i>
                        Tambah DUDI
                    </button>
                </div>
            </div>

            <div class="table-container">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="20%">Nama</th>
                            <th width="15%">NIS</th>
                            <th width="10%">Kelas</th>
                            <th width="15%">Jurusan</th>
                            <th width="15%">Status</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dudi as $index => $dudiItem)
                            <tr>
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            {{ substr($dudiItem->nama_dudi, 0, 1) }}
                                        </div>
                                        <div>
                                            <strong class="text-primary">{{ $dudiItem->nama_dudi }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="bg-light text-dark px-2 py-1 rounded">{{ $dudiItem->nomor_telpon }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $dudiItem->alamat }}</span>
                                </td>
                                <td>{{ $dudiItem->person_in_charge }}</td>
                                <td>
                                    <span class="status-badge status-active">
                                        Ditempatkan
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="action-btn btn-edit"
                                            onclick="editDudi({{ $dudiItem->id }}, '{{ $dudiItem->nama_dudi }}', '{{ $dudiItem->nomor_telpon }}', '{{ $dudiItem->alamat }}', '{{ $dudiItem->person_in_charge }}')"
                                            data-bs-toggle="tooltip"
                                            title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn btn-delete"
                                            onclick="deleteDudi({{ $dudiItem->id }}, '{{ $dudiItem->nama_dudi }}')"
                                            data-bs-toggle="tooltip"
                                            title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h6>Belum ada data DUDI</h6>
                                        <p>Silakan tambah data DUDI baru dengan mengklik tombol "Tambah DUDI"</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Add DUDI -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="addModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>
                        Tambah Data DUDI Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_dudi" class="form-label">
                                        <i class="fas fa-building me-1"></i>
                                        Nama DUDI
                                    </label>
                                    <input type="text" class="form-control" id="nama_dudi" name="nama_dudi" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nomor_telpon" class="form-label">
                                        <i class="fas fa-phone me-1"></i>
                                        Nomor Telepon
                                    </label>
                                    <input type="text" class="form-control" id="nomor_telpon" name="nomor_telpon" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Alamat
                                    </label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="person_in_charge" class="form-label">
                                        <i class="fas fa-user-tie me-1"></i>
                                        Person in Charge
                                    </label>
                                    <input type="text" class="form-control" id="person_in_charge" name="person_in_charge" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>
                                        Password Login
                                    </label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Batal
                    </button>
                    <button type="button" class="btn btn-primary" onclick="submitAdd()">
                        <i class="fas fa-save me-1"></i>
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit DUDI -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white" id="editModalLabel">
                        <i class="fas fa-edit me-2"></i>
                        Edit Data DUDI
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nama_dudi" class="form-label">
                                        <i class="fas fa-building me-1"></i>
                                        Nama DUDI
                                    </label>
                                    <input type="text" class="form-control" id="edit_nama_dudi" name="nama_dudi" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nomor_telpon" class="form-label">
                                        <i class="fas fa-phone me-1"></i>
                                        Nomor Telepon
                                    </label>
                                    <input type="text" class="form-control" id="edit_nomor_telpon" name="nomor_telpon" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_alamat" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Alamat
                                    </label>
                                    <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_person_in_charge" class="form-label">
                                        <i class="fas fa-user-tie me-1"></i>
                                        Person in Charge
                                    </label>
                                    <input type="text" class="form-control" id="edit_person_in_charge" name="person_in_charge" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Batal
                    </button>
                    <button type="button" class="btn btn-warning text-white" onclick="submitEdit()">
                        <i class="fas fa-save me-1"></i>
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/kelola-dudi-clean.js') }}"></script>

    <!-- Additional CSS for avatar circle -->
    <style>
        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(45deg, #e53e3e, #ff6b6b);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</body>

</html>
