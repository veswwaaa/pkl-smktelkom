{{-- <!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lamaran PKL Masuk - DUDI</title>
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
        <div class="d-flex align-items-center gap-3">
            <!-- Hamburger Menu (Mobile Only) -->
            <button class="hamburger-menu" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <div class="telkom-logo">
                <img src="{{ asset('img/telkom-logo.png') }}" alt="Telkom Logo" height="40">
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
                    <div class="profile-avatar">D</div>
                    <i class="fas fa-chevron-down text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <hr class="dropdown-divider">
                    <li><a class="dropdown-item text-danger" href="/logout"><i
                                class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Overlay untuk mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Left Sidebar -->
    <div class="left-sidebar" id="leftSidebar">
        <div class="sidebar-menu">
            <a href="/dudi/dashboard" class="sidebar-item" title="Dashboard">
                <i class="fas fa-th-large"></i>
            </a>
            <a href="/dudi/surat-pengajuan" class="sidebar-item" title="Surat Pengajuan">
                <i class="fas fa-file-invoice"></i>
            </a>
            <a href="/dudi/surat-permohonan" class="sidebar-item" title="Surat Permohonan">
                <i class="fas fa-file-export"></i>
            </a>
            <a href="/dudi/kelola-lamaran" class="sidebar-item active" title="Kelola Lamaran">
                <i class="fas fa-envelope"></i>
            </a>
            <a href="/dudi/surat-pkl" class="sidebar-item" title="Surat PKL">
                <i class="fas fa-clipboard-list"></i>
            </a>
        </div>
    </div>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/dudi/lamaran-pkl" data-bs-toggle="tooltip" title="Lamaran PKL">
                            <i class="fas fa-file-alt"></i>
                        </a>
                    </li>
                    <li>
                        <a href="/dudi/surat-pkl" data-bs-toggle="tooltip" title="Surat PKL">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col main-content">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>Lamaran PKL Masuk
                        </h5>
                        <span class="badge bg-primary">{{ $lamaran->total() }} Lamaran</span>
                    </div>

                    <div class="card-body">
                        @if ($lamaran->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada lamaran masuk</h5>
                                <p class="text-muted">Lamaran dari siswa akan muncul di sini</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th>Jurusan</th>
                                            <th>Pilihan Ke</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lamaran as $index => $item)
                                            <tr>
                                                <td>{{ $lamaran->firstItem() + $index }}</td>
                                                <td>{{ $item->siswa->nis }}</td>
                                                <td><strong>{{ $item->siswa->nama }}</strong></td>
                                                <td>{{ $item->siswa->kelas }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $item->siswa->jurusan }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        Pilihan {{ $item->pilihan_aktif }}
                                                    </span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        onclick="confirmApprove({{ $item->id }}, '{{ $item->siswa->nama }}')">
                                                        <i class="fas fa-check"></i> Terima
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="showRejectModal({{ $item->id }}, '{{ $item->siswa->nama }}')">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    Menampilkan {{ $lamaran->firstItem() ?? 0 }} - {{ $lamaran->lastItem() ?? 0 }}
                                    dari
                                    {{ $lamaran->total() }} data
                                </div>
                                <div>
                                    {{ $lamaran->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle"></i> Tolak Lamaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Anda akan menolak lamaran dari: <strong id="rejectNama"></strong></p>
                        <div class="mb-3">
                            <label class="form-label">Catatan Penolakan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Berikan alasan penolakan untuk siswa..."></textarea>
                            <small class="text-muted">Catatan ini akan dilihat oleh siswa</small>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Jika siswa memiliki pilihan lain, pengajuan akan otomatis dipindahkan ke pilihan berikutnya.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Tolak Lamaran
                        </button>
                    </div>
                </form>
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
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
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

        // Confirm Approve
        function confirmApprove(id, nama) {
            Swal.fire({
                title: 'Terima Lamaran?',
                html: `Anda akan menerima lamaran dari:<br><strong>${nama}</strong><br><br>Siswa akan ditempatkan di perusahaan Anda.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Terima',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/dudi/lamaran-pkl/${id}/approve`;

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

        // Show Reject Modal
        function showRejectModal(id, nama) {
            document.getElementById('rejectNama').textContent = nama;
            document.getElementById('rejectForm').action = `/dudi/lamaran-pkl/${id}/reject`;

            const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
            modal.show();
        }

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>

    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('leftSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
    </script>
</body>

</html> --}}
