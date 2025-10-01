<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Dashboard Admin</h1>

        <!-- Menu Cards -->
        <div class="row justify-content-center">
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5>👤 Info Admin</h5>
                        <p><strong>{{ $data->nama_admin }}</strong></p>
                        <p>{{ $data->no_telpon }}</p>
                        <p class="text-muted">{{ $data->alamat }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100" onclick="toggleSidebar()" style="cursor: pointer; border: 2px solid #007bff;">
                    <div class="card-body text-center">
                        <h5>🏢 Kelola DUDI</h5>
                        <p>Manajemen data DUDI</p>
                        <small class="text-primary">Klik untuk membuka menu</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5>🚪 Logout</h5>
                        <p>Keluar dari sistem</p>
                        <a href="logout" class="btn btn-danger">Keluar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="card position-fixed top-50 start-0 translate-middle-y" id="sidebar"
        style="display: none; width: 250px; z-index: 1000; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📋 Menu Admin</h5>
            <button onclick="toggleSidebar()" class="btn btn-sm btn-outline-light">✖</button>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                <a href="/admin/dudi" class="list-group-item list-group-item-action">
                    🏢 Kelola DUDI
                </a>
                <a href="#siswa" class="list-group-item list-group-item-action">
                    👥 Kelola Siswa
                </a>
                <a href="#laporan" class="list-group-item list-group-item-action">
                    📄 Laporan PKL
                </a>
                <a href="#pengaturan" class="list-group-item list-group-item-action">
                    ⚙️ Pengaturan
                </a>
                <hr class="my-0">
                <a href="logout" class="list-group-item list-group-item-action text-danger">
                    🚪 Logout
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.style.display === 'none' || sidebar.style.display === '') {
                sidebar.style.display = 'block';
            } else {
                sidebar.style.display = 'none';
            }
        }
    </script>
</body>

</html>
