<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Siswa - SMK Telkom Banjarbaru</title>
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
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="logout"><i
                                class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
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
            <a href="/admin/dudi" class="sidebar-item" title="Kelola DUDI">
                <i class="fas fa-building"></i>
            </a>
            <a href="/admin/siswa" class="sidebar-item active" title="Kelola Siswa">
                <i class="fas fa-users"></i>
            </a>
            <a href="#" class="sidebar-item" title="Menu">
                <i class="fas fa-th"></i>
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


        <!-- Main Content -->
        <div class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="page-title">
                    <div>
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h1>Kelola Siswa</h1>
                        <p>Kelola data siswa PKL SMK Telkom Banjarbaru</p>
                    </div>
                </div>
                <a href="/dashboard" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>



            <div class="container mt-4">
                {{-- headerrrrr --}}
                <div class="row mb-4" style="display: none;">
                    <div class="col-md-8">
                        <h2><i class="fas fa-users text-primary"></i>Kelola Siswa</h2>
                        <p class="text-muted">Manajemen data siswa PKL SMK Telkom</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="/dashboard" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                {{-- alert Messages - Hidden untuk SweetAlert --}}
                <div style="display: none;">
                    @if (session('success'))
                        <div id="success-message" data-message="{{ session('success') }}"></div>
                    @endif

                    @if (session('error'))
                        <div id="error-message" data-message="{{ session('error') }}"></div>
                    @endif

                    @if ($errors->any())
                        <div id="validation-errors" data-errors="{{ json_encode($errors->all()) }}"></div>
                    @endif
                </div>

                {{-- add Button --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSiswaModal">
                            <i class="fas fa-plus"></i> Tambah Siswa
                        </button>
                        <button class="btn btn-success ms-2" data-bs-toggle="modal"
                            data-bs-target="#importSiswaModal">
                            <i class="fas fa-file-excel"></i> Import Excel
                        </button>
                    </div>
                </div>

                {{-- tabel siswa --}}
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>NO</th>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Angkatan</th>
                                        <th>Jrusan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($siswa as $index => $siswaItem)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><span class="badge bg-info">{{ $siswaItem->nis }}</span></td>
                                            <td><strong>{{ $siswaItem->nama }}</strong></td>
                                            <td>{{ $siswaItem->kelas }}</td>
                                            <td>{{ $siswaItem->jenis_kelamin }}</td>
                                            <td>{{ $siswaItem->angkatan }}</td>
                                            <td><span class="badge bg-success">{{ $siswaItem->jurusan }}</span></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" title="Edit"
                                                    onclick="editSiswa({{ $siswaItem->id }},'{{ $siswaItem->nis }}', '{{ $siswaItem->nama }}','{{ $siswaItem->kelas }}','{{ $siswaItem->jenis_kelamin }}','{{ $siswaItem->angkatan }}','{{ $siswaItem->jurusan }}')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" title="Delete"
                                                    onclick="confirmDelete({{ $siswaItem->id }}, '{{ $siswaItem->nama }}', '{{ $siswaItem->nis }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <div class="py-4">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">Belum ada data siswa</h5>
                                                    <p class="text-muted">Klik "Tambah Siswa" untuk menambah data</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Modal Add Siswa --}}
            <div class="modal fade" id="addSiswaModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-user-plus"></i> Tambah Siswa Baru
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <form action="/admin/siswa" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nis" class="form-label">
                                                <i class="fas fa-id-card text-primary"></i> NIS
                                            </label>
                                            <input type="text" class="form-control" id="nis" name="nis"
                                                required placeholder="Masukkan NIS siswa">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nama" class="form-label">
                                                <i class="fas fa-user text-primary"></i> Nama Lengkap
                                            </label>
                                            <input type="text" class="form-control" id="nama" name="nama"
                                                required placeholder="Masukkan nama lengkap">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="kelas" class="form-label">
                                                <i class="fas fa-school text-primary"></i> Kelas
                                            </label>
                                            <input type="text" class="form-control" id="kelas" name="kelas"
                                                required placeholder="Contoh: XII E">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="jenis_kelamin" class="form-label">
                                                <i class="fas fa-venus-mars text-primary"></i> Jenis Kelamin
                                            </label>
                                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin"
                                                required>
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="angkatan" class="form-label">
                                                <i class="fas fa-calendar text-primary"></i> Angkatan
                                            </label>
                                            <input type="text" class="form-control" id="angkatan"
                                                name="angkatan" required placeholder="Contoh: angkatan 26">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="jurusan" class="form-label">
                                                <i class="fas fa-graduation-cap text-primary"></i> Jurusan
                                            </label>
                                            {{-- buat ngedit option jurusannya --}}
                                            <select class="form-select" id="jurusan" name="jurusan" required>
                                                <option value="">Pilih Jurusan</option>
                                                <option value="RPL">RPL (Rekayasa Perangkat Lunak)</option>
                                                <option value="TKJ">TKJ (Teknik Komputer & Jaringan)</option>
                                                <option value="TJKT">TJKT (Teknik Jaringan Komputer
                                                    danTelekomunikasi)</option>
                                                <option value="DKV">DKV (Desain Komunikasi Visual)</option>
                                                <option value="Animasi">Animasi</option>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Info:</strong> Password akan otomatis di-generate dengan format:
                                    <code>dummy@NIS</code>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Siswa
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Edit Siswa --}}
            <div class="modal fade" id="editSiswaModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-edit"></i> Edit Data Siswa
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <form id="editSiswaForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_nis" class="form-label">
                                                <i class="fas fa-id-card text-warning"></i> NIS
                                            </label>
                                            <input type="text" class="form-control" id="edit_nis" name="nis"
                                                required placeholder="Masukkan NIS siswa">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_nama" class="form-label">
                                                <i class="fas fa-user text-warning"></i> Nama Lengkap
                                            </label>
                                            <input type="text" class="form-control" id="edit_nama" name="nama"
                                                required placeholder="Masukkan nama lengkap">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_kelas" class="form-label">
                                                <i class="fas fa-school text-warning"></i> Kelas
                                            </label>
                                            <input type="text" class="form-control" id="edit_kelas"
                                                name="kelas" required placeholder="Contoh: XII RPL 1">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_jenis_kelamin" class="form-label">
                                                <i class="fas fa-venus-mars text-warning"></i> Jenis Kelamin
                                            </label>
                                            <select class="form-select" id="edit_jenis_kelamin" name="jenis_kelamin"
                                                required>
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_angkatan" class="form-label">
                                                <i class="fas fa-calendar text-warning"></i> Angkatan
                                            </label>
                                            <input type="text" class="form-control" id="edit_angkatan"
                                                name="angkatan" required placeholder="Contoh: 2025">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_jurusan" class="form-label">
                                                <i class="fas fa-graduation-cap text-warning"></i> Jurusan
                                            </label>
                                            <select class="form-select" id="edit_jurusan" name="jurusan" required>
                                                <option value="">Pilih Jurusan</option>
                                                <option value="RPL">RPL (Rekayasa Perangkat Lunak)</option>
                                                <option value="TKJ">TKJ (Teknik Komputer & Jaringan)</option>
                                                <option value="TJKT">TJKT (Teknik Jaringan Komputer
                                                    danTelekomunikasi)</option>
                                                <option value="DKV">DKV (Desain Komunikasi Visual)</option>
                                                <option value="ANM">ANM (Animasi)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Perhatian:</strong> Jika NIS diubah, username login siswa juga akan berubah
                                    mengikuti NIS baru.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Import Excel --}}
            <div class="modal fade" id="importSiswaModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-file-excel"></i> Import Data Siswa dari Excel
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <form action="/admin/siswa/import" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="file" class="form-label">
                                        <i class="fas fa-upload text-success"></i> Pilih File Excel
                                    </label>
                                    <input type="file" class="form-control" id="file" name="file"
                                        accept=".xlsx,.xls,.csv" required>
                                    <small class="text-muted">Format: .xlsx, .xls, atau .csv (Max: 2MB)</small>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Format Excel yang Diperlukan:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Baris pertama harus berisi header kolom</li>
                                        <li>Kolom yang wajib ada:
                                            <ul>
                                                <li><code>nis</code> (9 digit angka)</li>
                                                <li><code>nama</code> (nama lengkap)</li>
                                                <li><code>kelas</code> (contoh: XII E)</li>
                                                <li><code>jenis_kelamin</code> (Laki-laki/Perempuan)</li>
                                                <li><code>angkatan</code> (tahun angkatan)</li>
                                                <li><code>jurusan</code> (RPL/TKJ/DKV/dll)</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Catatan:</strong>
                                    <ul class="mb-0">
                                        <li>Password otomatis: <code>dummy@NIS</code></li>
                                        <li>Data dengan NIS yang sudah ada akan dilewati</li>
                                        <li>Akun login siswa akan otomatis dibuat</li>
                                    </ul>
                                </div>

                                <div class="alert alert-success">
                                    <i class="fas fa-download"></i>
                                    <strong>Download Template Excel:</strong><br>
                                    <a href="#" class="btn btn-sm btn-outline-success mt-2">
                                        <i class="fas fa-file-download"></i> Download Template
                                    </a>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-upload"></i> Import Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- modal import dari excel --}}




        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- SweetAlert Notifications -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Success Message
                const successMessage = document.getElementById('success-message');
                if (successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: successMessage.getAttribute('data-message'),
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }

                // Error Message
                const errorMessage = document.getElementById('error-message');
                if (errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage.getAttribute('data-message'),
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33'
                    });
                }

                // Validation Errors
                const validationErrors = document.getElementById('validation-errors');
                if (validationErrors) {
                    const errors = JSON.parse(validationErrors.getAttribute('data-errors'));
                    let errorText = '';
                    errors.forEach(function(error, index) {
                        errorText += (index + 1) + '. ' + error + '\n';
                    });

                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi Gagal',
                        text: errorText,
                        confirmButtonText: 'Perbaiki',
                        confirmButtonColor: '#f39c12'
                    });
                }
            });

            // Function untuk konfirmasi delete
            function confirmDelete(id, nama, nis) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    html: `Anda akan menghapus siswa:<br><strong>${nama}</strong><br>NIS: <strong>${nis}</strong><br><br>Data yang dihapus tidak dapat dikembalikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form delete
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            }

            // Function untuk membuka modal edit siswa
            function openEditSiswaModal(id) {
                // Reset form
                document.getElementById('editSiswaForm').reset();

                // Get siswa data by id
                const siswa = @json($siswa);
                const siswaItem = siswa.find(item => item.id === id);

                // Set form values
                document.getElementById('edit_nis').value = siswaItem.nis;
                document.getElementById('edit_nama').value = siswaItem.nama;
                document.getElementById('edit_kelas').value = siswaItem.kelas;
                document.getElementById('edit_angkatan').value = siswaItem.angkatan;
                document.getElementById('edit_jurusan').value = siswaItem.jurusan;
                document.getElementById('edit_jenis_kelamin').value = siswaItem.jenis_kelamin;

                // Set form action
                document.getElementById('editSiswaForm').action = '/admin/siswa/' + id;

                // Show modal
                const editSiswaModal = new bootstrap.Modal(document.getElementById('editSiswaModal'));
                editSiswaModal.show();
            }

            // Function untuk edit siswa
            function editSiswa(id, nis, nama, kelas, jenis_kelamin, angkatan, jurusan) {
                // Set form action URL
                document.getElementById('editSiswaForm').action = '/admin/siswa/' + id;

                // Isi field form dengan data siswa
                document.getElementById('edit_nis').value = nis;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_kelas').value = kelas;
                document.getElementById('edit_jenis_kelamin').value = jenis_kelamin;
                document.getElementById('edit_angkatan').value = angkatan;
                document.getElementById('edit_jurusan').value = jurusan;

                // Tampilkan modal
                var editModal = new bootstrap.Modal(document.getElementById('editSiswaModal'));
                editModal.show();
            }
        </script>

        <!-- Hidden Delete Forms -->
        @foreach ($siswa as $siswaItem)
            <form id="delete-form-{{ $siswaItem->id }}" action="/admin/siswa/{{ $siswaItem->id }}" method="POST"
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
</body>

</html>
