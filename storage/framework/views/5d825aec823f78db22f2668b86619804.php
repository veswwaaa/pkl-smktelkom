 <!DOCTYPE html>
 <html lang="id">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Dashboard PKL - SMK Telkom Banjarbaru</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link rel="stylesheet" href="<?php echo e(asset('css/dashboard-siswa-new.css')); ?>">
 </head>

 <body>
     <!-- Top Navbar -->
     <nav class="top-navbar">
         <div class="telkom-logo">
             <img src="<?php echo e(asset('img/telkom-logo.png')); ?>" alt="Telkom Schools" onerror="this.style.display='none'">
             <h5>Telkom Schools</h5>
         </div>

         <div class="navbar-right">
             <button class="notification-btn" onclick="window.location.href='/siswa/pengajuan-pkl'">
                 <i class="fas fa-bell"></i>
                 <?php if($pengajuan && $pengajuan->status == 'pending'): ?>
                     <span class="notification-badge">1</span>
                 <?php endif; ?>
             </button>
             <div class="dropdown">
                 <div class="user-avatar" data-bs-toggle="dropdown">
                     <?php echo e(substr($data->nama, 0, 1)); ?>

                 </div>
                 <ul class="dropdown-menu dropdown-menu-end">
                     <li>
                         <h6 class="dropdown-header"><?php echo e($data->nama); ?></h6>
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

     <!-- Left Sidebar -->
     <div class="left-sidebar">
         <div class="sidebar-menu">
             <a href="/dashboard" class="sidebar-item active" title="Dashboard">
                 <i class="fas fa-th-large"></i>
             </a>
             <a href="/siswa/status" class="sidebar-item" title="Status & Info Siswa">
                 <i class="fas fa-user-circle"></i>
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
             <a href="#" class="sidebar-item" title="Download Surat"
                 onclick="alert('Fitur dalam pengembangan'); return false;">
                 <i class="fas fa-file-download"></i>
             </a>
             <a href="/logout" class="sidebar-item" title="Logout">
                 <i class="fas fa-sign-out-alt"></i>
             </a>
         </div>
     </div>

     <!-- Main Content -->
     <div class="main-content">
         <!-- Welcome Banner -->
         <div class="welcome-banner">
             <div class="welcome-content">
                 <h2 class="welcome-title">Selamat Datang di Dashboard PKL</h2>
                 <p class="welcome-subtitle">Kelola program Praktik Kerja Lapangan SMK Telkom Banjarbaru dengan mudah
                     dan
                     efisien</p>
                 <div class="illustration-avatars">
                     <div class="avatar-circle" style="background: #4CAF50;">A</div>
                     <div class="avatar-circle" style="background: #2196F3;">B</div>
                     <div class="avatar-circle" style="background: #8BC34A;">C</div>
                     <div class="avatar-circle" style="background: #E0E0E0; color: #999;">D</div>
                 </div>
             </div>
         </div>

         <!-- Pilihan PKL yang Tersedia -->
         <h3 class="section-title">Pilihan PKL yang Tersedia</h3>

         <div class="row">
             <?php $__empty_1 = true; $__currentLoopData = $dudiTersedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dudi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                 <div class="col-md-4">
                     <div class="dudi-card">
                         <div class="dudi-logo">
                             <i class="fas fa-building"></i>
                         </div>
                         <h4 class="dudi-name"><?php echo e($dudi->nama_dudi); ?></h4>
                         <p class="dudi-description">
                             <?php echo e(Str::limit($dudi->jobdesk, 100)); ?>

                         </p>

                         <div class="dudi-contact">
                             <?php if($dudi->nomor_telpon): ?>
                                 <div class="contact-item">
                                     <i class="fas fa-phone"></i>
                                     <span><?php echo e($dudi->nomor_telpon); ?></span>
                                 </div>
                             <?php endif; ?>
                             <?php if($dudi->alamat): ?>
                                 <div class="contact-item">
                                     <i class="fas fa-map-marker-alt"></i>
                                     <span><?php echo e(Str::limit($dudi->alamat, 50)); ?></span>
                                 </div>
                             <?php endif; ?>
                         </div>

                         <?php if($dudi->jurusan_diterima && count($dudi->jurusan_diterima) > 0): ?>
                             <div class="jurusan-badges">
                                 <?php $__currentLoopData = $dudi->jurusan_diterima; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <span class="jurusan-badge">
                                         <i class="fas fa-check-circle me-1"></i><?php echo e($jurusan); ?>

                                     </span>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                             </div>
                         <?php endif; ?>

                         <?php if($dudi->jobdesk): ?>
                             <div class="jobdesk-section">
                                 <div class="jobdesk-title">
                                     <i class="fas fa-briefcase me-2"></i>Jobdesk Siswa PKL:
                                 </div>
                                 <div class="jobdesk-text">
                                     <?php echo e($dudi->jobdesk); ?>

                                 </div>
                             </div>
                         <?php endif; ?>
                     </div>
                 </div>
             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                 <div class="col-12">
                     <div class="empty-state">
                         <i class="fas fa-inbox"></i>
                         <h5>Belum Ada DUDI Tersedia</h5>
                         <p class="text-muted">Saat ini belum ada DUDI yang membuka lowongan PKL.</p>
                     </div>
                 </div>
             <?php endif; ?>
         </div>
     </div>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
     <script src="<?php echo e(asset('js/dashboard-siswa.js')); ?>"></script>

 </body>

 </html>
<?php /**PATH C:\laragon\www\pkl-smktelkom\resources\views/dashboardSiswa.blade.php ENDPATH**/ ?>