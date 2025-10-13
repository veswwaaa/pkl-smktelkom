<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard PKL - SMK Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard-admin.css') }}" rel="stylesheet">
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
                    <li><a class="dropdown-item text-danger" href="logout"><i
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
            <a href="/admin/dudi" class="sidebar-item" title="Kelola DUDI">
                <i class="fas fa-building"></i>
            </a>
             <a href="/admin/siswa" class="sidebar-item" title="kelola Siswa">
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

        <!-- Stats Cards -->
        <div class="stats-row">
            <!-- Total Siswa -->
            <div class="stat-card border-red">
                <div class="stat-icon icon-red">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">2</div>
                <div class="stat-label">Total Siswa</div>
                <div class="stat-change positive">+12 bulan dari lalu</div>
                <i class="fas fa-chevron-right stat-arrow"></i>
            </div>

            <!-- Total DUDI -->
            <div class="stat-card border-green">
                <div class="stat-icon icon-green">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-number">2</div>
                <div class="stat-label">Total DUDI</div>
                <div class="stat-change positive">+5 mitra baru</div>
                <i class="fas fa-chevron-right stat-arrow"></i>
            </div>

            <!-- Siswa Ditempatkan -->
            <div class="stat-card border-blue">
                <div class="stat-icon icon-blue">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number">187</div>
                <div class="stat-label">Siswa Ditempatkan</div>
                <div class="stat-change positive">93.5% dari total</div>
                <i class="fas fa-chevron-right stat-arrow"></i>
            </div>

            <!-- Menunggu Penempatan -->
            <div class="stat-card border-orange">
                <div class="stat-icon icon-orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number">32</div>
                <div class="stat-label">Menunggu Penempatan</div>
                <div class="stat-change negative">perlu tindak lanjut</div>
                <i class="fas fa-chevron-right stat-arrow"></i>
            </div>
        </div>

        <!-- Content Row -->
        <div class="content-row">
            <!-- Activities Section -->
            <div class="activities-card">
                <div class="activities-header">
                    <div class="activities-title">
                        <i class="fas fa-calendar-alt"></i>
                        <h5>Aktivitas Terkini</h5>
                    </div>
                    <span class="activities-subtitle">Kegiatan terbaru dalam sistem PKL (Hari Ini)</span>
                </div>

                @forelse($activities as $activity)
                    <div class="activity-item">
                        @php
                            $dotColor = match ($activity->type) {
                                'login' => 'blue',
                                'create' => 'green',
                                'update' => 'orange',
                                'delete' => 'red',
                                'success' => 'green',
                                'warning' => 'orange',
                                'info' => 'blue',
                                default => 'gray',
                            };
                        @endphp
                        <div class="activity-dot {{ $dotColor }}"></div>
                        <div class="activity-content">
                            <h6>{{ $activity->title }}</h6>
                            <p>{{ $activity->description }}</p>
                            <span class="activity-time">
                                <i class="fas fa-user me-1"></i>{{ $activity->username }} •
                                {{ $activity->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="activity-item">
                        <div class="activity-dot gray"></div>
                        <div class="activity-content">
                            <h6>Belum Ada Aktivitas</h6>
                            <p>Belum ada aktivitas yang tercatat hari ini</p>
                            <span class="activity-time">-</span>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Right Sidebar -->
            <div class="right-sidebar">
                <!-- Calendar -->
                <div class="calendar-card">
                    <div class="calendar-header">
                        <i class="fas fa-calendar text-primary"></i>
                        <h6>Kalender</h6>
                    </div>
                    <div class="calendar-widget">
                        <iframe
                            src="https://calendar.google.com/calendar/embed?height=300&wkst=1&bgcolor=%23ffffff&ctz=Asia%2FMakassar&src=id.indonesian%23holiday%40group.v.calendar.google.com&color=%23039BE5&showTitle=0&showCalendars=0&showTabs=0&showPrint=0&showDate=1&showNav=1"
                            class="calendar-iframe" frameborder="0" scrolling="no">
                        </iframe>
                    </div>
                </div>

                <!-- Notes & Agenda -->
                <div class="notes-card">
                    <div class="notes-header">
                        <i class="fas fa-sticky-note text-warning"></i>
                        <h6>Notes & Agenda</h6>
                    </div>

                    <div class="note-item">
                        <h6>Rapat Koordinasi PKL</h6>
                        <p>Rapat dengan pihak DUDI terkait evaluasi siswa PKL - 15:00 WIB</p>
                    </div>

                    <div class="note-item">
                        <h6>Deadline Laporan</h6>
                        <p>Pengumpulan laporan akhir PKL siswa kelas XII - 3 hari lagi</p>
                    </div>

                    <div class="note-item">
                        <h6>Kunjungan Industri</h6>
                        <p>Survey tempat PKL baru di kawasan industri Banjarbaru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar interactions
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.addEventListener('click', function(e) {
                document.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Card hover effects
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('click', function() {
                console.log('Card clicked:', this.querySelector('.stat-label').textContent);
            });
        });

        // Mobile sidebar toggle (for future use)
        function toggleSidebar() {
            const sidebar = document.querySelector('.left-sidebar');
            sidebar.classList.toggle('show');
        }

        // Responsive behavior
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.querySelector('.left-sidebar').classList.remove('show');
            }
        });
    </script>
</body>

</html>
