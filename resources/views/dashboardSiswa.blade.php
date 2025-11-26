 <!DOCTYPE html>
 <html lang="id">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Dashboard PKL - SMK Telkom Banjarbaru</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link rel="stylesheet" href="{{ asset('css/dashboard-siswa-new.css') }}">
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 </head>

 <body>
     <!-- Top Navbar -->
     <nav class="top-navbar">
         <div class="d-flex align-items-center gap-3">
             <!-- Hamburger Menu (Mobile Only) -->
             <button class="hamburger-menu" id="hamburgerMenu" onclick="toggleSidebar()">
                 <i class="fas fa-bars"></i>
             </button>

             <div class="telkom-logo">
                 <img src="{{ asset('img/telkom-logo.png') }}" alt="Telkom Schools" onerror="this.style.display='none'">
             </div>
         </div>

         <div class="navbar-right">
             <button class="notification-btn" onclick="window.location.href='/siswa/pengajuan-pkl'">
                 <i class="fas fa-bell"></i>
                 @if ($pengajuan && $pengajuan->status == 'pending')
                     <span class="notification-badge">1</span>
                 @endif
             </button>
             <div class="dropdown">
                 <div class="profile-dropdown" data-bs-toggle="dropdown">
                     <div class="user-avatar">
                         {{ substr($data->nama, 0, 1) }}
                     </div>
                     <i class="fas fa-chevron-down text-muted"></i>
                 </div>
                 <ul class="dropdown-menu dropdown-menu-end">
                     <li>
                         <h6 class="dropdown-header">{{ $data->nama }}</h6>
                     </li>
                     <li>
                         <hr class="dropdown-divider">
                     </li>
                     <li><a class="dropdown-item" href="#" onclick="confirmLogout(event)"><i
                                 class="fas fa-sign-out-alt me-2"></i>Logout</a>
                     </li>
                 </ul>
             </div>
         </div>
     </nav>

     <!-- Overlay untuk mobile -->
     <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

     <!-- Left Sidebar -->
     <div class="left-sidebar" id="leftSidebar">
         <div class="sidebar-menu">
             <a href="/dashboard" class="sidebar-item active" title="Dashboard">
                 <i class="fas fa-th-large"></i>
             </a>
             <a href="/siswa/pengajuan-pkl" class="sidebar-item" title="Pengajuan PKL">
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

         <!-- Pilihan PKL yang Tersedia -->
         <div class="d-flex justify-content-between align-items-center mb-3">
             <h3 class="section-title mb-0">Pilihan PKL yang Tersedia untuk Jurusan {{ $data->jurusan }}</h3>
             <span class="badge" style="background: var(--primary-red); padding: 0.5rem 1rem; font-size: 0.9rem;">
                 <i class="fas fa-building me-1"></i>{{ $dudiTersedia->count() }} DUDI
             </span>
         </div>

         <div class="row">
             @forelse($dudiTersedia as $dudi)
                 <div class="col-md-4">
                     <div class="dudi-card"
                         onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 8px 20px rgba(0, 0, 0, 0.15)';"
                         onmouseout="this.style.transform=''; this.style.boxShadow='';"
                         style="transition: all 0.3s ease;">
                         <div class="dudi-logo">
                             <i class="fas fa-building"></i>
                         </div>
                         <h4 class="dudi-name">{{ $dudi->nama_dudi }}</h4>
                         <p class="dudi-description">
                             {{ Str::limit($dudi->jobdesk, 100) }}
                         </p>

                         <div class="dudi-contact">
                             @if ($dudi->nomor_telpon)
                                 <div class="contact-item">
                                     <i class="fas fa-phone"></i>
                                     <span>{{ $dudi->nomor_telpon }}</span>
                                 </div>
                             @endif
                             @if ($dudi->alamat)
                                 <div class="contact-item">
                                     <i class="fas fa-map-marker-alt"></i>
                                     <span>{{ Str::limit($dudi->alamat, 50) }}</span>
                                 </div>
                             @endif
                         </div>

                         @if ($dudi->jurusan_diterima && count($dudi->jurusan_diterima) > 0)
                             <div class="jurusan-badges">
                                 @foreach ($dudi->jurusan_diterima as $jurusan)
                                     @php
                                         $badgeColor = '';
                                         switch ($jurusan) {
                                             case 'RPL':
                                                 $badgeColor = 'background: #3b82f6; color: white;';
                                                 break;
                                             case 'TKJ':
                                                 $badgeColor = 'background: #06b6d4; color: white;';
                                                 break;
                                             case 'ANM':
                                                 $badgeColor = 'background: #f59e0b; color: white;';
                                                 break;
                                             case 'DKV':
                                                 $badgeColor = 'background: #ef4444; color: white;';
                                                 break;
                                             case 'TJKT':
                                                 $badgeColor = 'background: #10b981; color: white;';
                                                 break;
                                             default:
                                                 $badgeColor = 'background: #6b7280; color: white;';
                                         }
                                     @endphp
                                     <span class="jurusan-badge" style="{{ $badgeColor }}">
                                         <i class="fas fa-check-circle"></i>{{ $jurusan }}
                                     </span>
                                 @endforeach
                             </div>
                             @endif @if ($dudi->jobdesk)
                                 <div class="jobdesk-section">
                                     <div class="jobdesk-title">
                                         <i class="fas fa-briefcase me-2"></i>Jobdesk Siswa PKL:
                                     </div>
                                     <div class="jobdesk-text">
                                         {{ $dudi->jobdesk }}
                                     </div>
                                 </div>
                             @endif
                     </div>
                 </div>
             @empty
                 <div class="col-12">
                     <div class="empty-state">
                         <i class="fas fa-inbox"></i>
                         <h5>Belum Ada DUDI Tersedia untuk Jurusan {{ $data->jurusan }}</h5>
                         <p class="text-muted">Saat ini belum ada DUDI yang membuka lowongan PKL untuk jurusan Anda.
                         </p>
                     </div>
                 </div>
             @endforelse
         </div>
     </div>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
     <script src="{{ asset('js/dashboard-siswa.js') }}"></script>
     <script>
         // Toggle Sidebar untuk Mobile
         function toggleSidebar() {
             const sidebar = document.getElementById('leftSidebar');
             const overlay = document.getElementById('sidebarOverlay');

             sidebar.classList.toggle('show');
             overlay.classList.toggle('show');
         }

         // Close sidebar when clicking on menu item (mobile)
         document.querySelectorAll('.sidebar-item').forEach(item => {
             item.addEventListener('click', function() {
                 if (window.innerWidth <= 768) {
                     toggleSidebar();
                 }
             });
         });

         function confirmLogout(event) {
             event.preventDefault();
             Swal.fire({
                 title: 'Konfirmasi Logout',
                 text: 'Apakah Anda yakin ingin keluar?',
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#e31e24',
                 cancelButtonColor: '#6c757d',
                 confirmButtonText: 'Ya, Logout',
                 cancelButtonText: 'Batal'
             }).then((result) => {
                 if (result.isConfirmed) {
                     window.location.href = '/logout';
                 }
             });
         }
     </script>

 </body>

 </html>
