<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola DUDI - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Tambah CSRF token di head -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4 text-center">🏢 Kelola DUDI</h1>

                <!-- Alert Success -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Alert Error dari Session -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>❌ Error:</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Alert Error dari Validation -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>⚠️ Ada kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form Tambah DUDI -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📝 Tambah DUDI Baru</h5>
                    </div>
                    <div class="card-body">
                        <form action="/admin/dudi/store" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_dudi" class="form-label">Nama DUDI</label>
                                        <input type="text" class="form-control" id="nama_dudi" name="nama_dudi"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nomor_telpon" class="form-label">Nomor Telepon</label>
                                        <input type="text" class="form-control" id="nomor_telpon" name="nomor_telpon"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="person_in_charge" class="form-label">Person in Charge</label>
                                        <input type="text" class="form-control" id="person_in_charge"
                                            name="person_in_charge" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password Login</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-plus-circle"></i> Tambah DUDI
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Tabel Daftar DUDI -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">📊 Daftar DUDI</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="20%">Nama DUDI</th>
                                        <th width="15%">Telepon</th>
                                        <th width="25%">Alamat</th>
                                        <th width="15%">PIC</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dudi as $index => $dudiItem)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                            <td>
                                                <strong class="text-primary">{{ $dudiItem->nama_dudi }}</strong>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-light text-dark">{{ $dudiItem->nomor_telpon }}</span>
                                            </td>
                                            <td>
                                                <small
                                                    class="text-muted">{{ Str::limit($dudiItem->alamat, 40) }}</small>
                                            </td>
                                            <td>{{ $dudiItem->person_in_charge }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-warning btn-sm" title="Edit DUDI"
                                                        onclick="editDudi({{ $dudiItem->id }}, '{{ $dudiItem->nama_dudi }}', '{{ $dudiItem->nomor_telpon }}', '{{ $dudiItem->alamat }}', '{{ $dudiItem->person_in_charge }}')">
                                                        ✏️ Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" title="Hapus DUDI"
                                                        onclick="deleteDudi({{ $dudiItem->id }}, '{{ $dudiItem->nama_dudi }}')">
                                                        🗑️ Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-muted">
                                                    <h6>📭 Belum ada data DUDI</h6>
                                                    <small>Silakan tambah DUDI baru menggunakan form di atas</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tombol Kembali -->
                <div class="mt-4 text-center">
                    <a href="/dashboard" class="btn btn-secondary btn-lg">
                        ← Kembali ke Dashboard
                    </a>
                </div>
                <!-- Modal Edit DUDI -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-white">
                                <h5 class="modal-title" id="editModalLabel">✏️ Edit Data DUDI</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editForm" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="edit_nama_dudi" class="form-label">Nama DUDI</label>
                                                <input type="text" class="form-control" id="edit_nama_dudi"
                                                    name="nama_dudi" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="edit_nomor_telpon" class="form-label">Nomor
                                                    Telepon</label>
                                                <input type="text" class="form-control" id="edit_nomor_telpon"
                                                    name="nomor_telpon" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="edit_alamat" class="form-label">Alamat</label>
                                                <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="edit_person_in_charge" class="form-label">Person in
                                                    Charge</label>
                                                <input type="text" class="form-control" id="edit_person_in_charge"
                                                    name="person_in_charge" required>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-warning" onclick="submitEdit()">Update
                                    DUDI</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ganti script di bagian bawah -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scriptKelola-dudi.js') }}"></script>
</body>

</html>
