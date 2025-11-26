<?php $__env->startSection('title', 'Surat DUDI'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-header">
        <div class="page-title">
            <div>
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <h1>Surat Balasan Dudi</h1>
                <p>Kelola surat pengajuan dan balasan dari DUDI</p>
            </div>
        </div>
        <a href="/admin/dudi" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="container-fluid">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Informasi:</strong> Halaman ini menampilkan semua surat yang telah dikirim ke DUDI dan balasan dari
            DUDI.
        </div>

        <ul class="nav nav-tabs mb-3" id="suratTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pengajuan-tab" data-bs-toggle="tab" data-bs-target="#pengajuan"
                    type="button" role="tab">
                    <i class="fas fa-paper-plane me-2"></i>Surat Pengajuan PKL
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="permohonan-tab" data-bs-toggle="tab" data-bs-target="#permohonan"
                    type="button" role="tab">
                    <i class="fas fa-file-upload me-2"></i>Surat Permohonan Data
                </button>
            </li>
        </ul>

        <div class="tab-content" id="suratTabContent">
            <div class="tab-pane fade show active" id="pengajuan" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="searchPengajuan" class="form-control"
                                        placeholder="Cari nama DUDI...">
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Ketik nama DUDI untuk mencari
                                </small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="tablePengajuan">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama DUDI</th>
                                        <th>Surat Pengajuan</th>
                                        <th>Status Balasan</th>
                                        <th>Surat Balasan</th>
                                        <th>Catatan DUDI</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $suratList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $surat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td>
                                                <strong><?php echo e($surat->dudi->nama_dudi); ?></strong><br>
                                                <small class="text-muted"><?php echo e($surat->dudi->alamat); ?></small>
                                            </td>
                                            <td>
                                                <?php if($surat->file_surat_pengajuan): ?>
                                                    <?php if($surat->file_pengajuan_exists): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Terkirim
                                                        </span><br>
                                                        <small class="text-muted">
                                                            <?php if($surat->tanggal_upload_pengajuan): ?>
                                                                <?php echo e($surat->tanggal_upload_pengajuan->format('d M Y H:i')); ?>

                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>File Hilang
                                                        </span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Belum Upload</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($surat->status_balasan_pengajuan): ?>
                                                    <?php if($surat->status_balasan_pengajuan == 'terkirim'): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Dibalas
                                                        </span>
                                                    <?php elseif($surat->status_balasan_pengajuan == 'diterima'): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Diterima
                                                        </span>
                                                    <?php elseif($surat->status_balasan_pengajuan == 'ditolak'): ?>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle me-1"></i>Ditolak
                                                        </span>
                                                    <?php endif; ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?php if($surat->tanggal_upload_balasan_pengajuan): ?>
                                                            <?php echo e($surat->tanggal_upload_balasan_pengajuan->format('d M Y H:i')); ?>

                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i>Menunggu Balasan
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($surat->file_balasan_pengajuan): ?>
                                                    <?php if($surat->file_balasan_pengajuan_exists): ?>
                                                        <a href="/admin/surat-dudi/<?php echo e($surat->id); ?>/download?type=balasan-pengajuan"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>File Hilang
                                                        </span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($surat->catatan_balasan_pengajuan): ?>
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#catatanPengajuanModal<?php echo e($surat->id); ?>">
                                                        <i class="fas fa-eye me-1"></i>Lihat
                                                    </button>

                                                    <div class="modal fade" id="catatanPengajuanModal<?php echo e($surat->id); ?>"
                                                        tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-info text-white">
                                                                    <h5 class="modal-title">
                                                                        <i class="fas fa-sticky-note me-2"></i>Catatan DUDI
                                                                    </h5>
                                                                    <button type="button" class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p><strong>DUDI:</strong> <?php echo e($surat->dudi->nama_dudi); ?>

                                                                    </p>
                                                                    <hr>
                                                                    <p><?php echo e($surat->catatan_balasan_pengajuan); ?></p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete(<?php echo e($surat->id); ?>, '<?php echo e($surat->dudi->nama_dudi); ?>')"
                                                    title="Hapus Surat">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada surat pengajuan yang dikirim ke DUDI</h5>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="permohonan" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-warning text-dark">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="searchPermohonan" class="form-control"
                                        placeholder="Cari nama DUDI...">
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Ketik nama DUDI untuk mencari
                                </small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="tablePermohonan">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama DUDI</th>
                                        <th>Surat Permohonan</th>
                                        <th>Status Balasan</th>
                                        <th>Surat Balasan</th>
                                        <th>Catatan DUDI</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $suratList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $surat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td>
                                                <strong><?php echo e($surat->dudi->nama_dudi); ?></strong><br>
                                                <small class="text-muted"><?php echo e($surat->dudi->alamat); ?></small>
                                            </td>
                                            <td>
                                                <?php if($surat->file_surat_permohonan): ?>
                                                    <?php if($surat->file_permohonan_exists): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Terkirim
                                                        </span><br>
                                                        <small class="text-muted">
                                                            <?php if($surat->tanggal_upload_permohonan): ?>
                                                                <?php echo e($surat->tanggal_upload_permohonan->format('d M Y H:i')); ?>

                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>File Hilang
                                                        </span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Belum Upload</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($surat->status_balasan_permohonan): ?>
                                                    <?php if($surat->status_balasan_permohonan == 'terkirim'): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Dibalas
                                                        </span>
                                                    <?php elseif($surat->status_balasan_permohonan == 'diterima'): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Diterima
                                                        </span>
                                                    <?php elseif($surat->status_balasan_permohonan == 'ditolak'): ?>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle me-1"></i>Ditolak
                                                        </span>
                                                    <?php endif; ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?php if($surat->tanggal_upload_balasan_permohonan): ?>
                                                            <?php echo e($surat->tanggal_upload_balasan_permohonan->format('d M Y H:i')); ?>

                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i>Menunggu Balasan
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($surat->file_balasan_permohonan): ?>
                                                    <?php if($surat->file_balasan_permohonan_exists): ?>
                                                        <a href="/admin/surat-dudi/<?php echo e($surat->id); ?>/download?type=balasan-permohonan"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>File Hilang
                                                        </span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($surat->catatan_balasan_permohonan): ?>
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#catatanPermohonanModal<?php echo e($surat->id); ?>">
                                                        <i class="fas fa-eye me-1"></i>Lihat
                                                    </button>

                                                    <div class="modal fade"
                                                        id="catatanPermohonanModal<?php echo e($surat->id); ?>" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-info text-white">
                                                                    <h5 class="modal-title">
                                                                        <i class="fas fa-sticky-note me-2"></i>Catatan DUDI
                                                                    </h5>
                                                                    <button type="button"
                                                                        class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p><strong>DUDI:</strong> <?php echo e($surat->dudi->nama_dudi); ?>

                                                                    </p>
                                                                    <hr>
                                                                    <p><?php echo e($surat->catatan_balasan_permohonan); ?></p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete(<?php echo e($surat->id); ?>, '<?php echo e($surat->dudi->nama_dudi); ?>')"
                                                    title="Hapus Surat">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada surat permohonan yang dikirim ke DUDI</h5>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteForm" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.getElementById('searchPengajuan').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#tablePengajuan tbody tr');

            tableRows.forEach(function(row) {
                const namaDudi = row.querySelector('td:nth-child(2)');
                if (namaDudi) {
                    const text = namaDudi.textContent.toLowerCase();
                    row.style.display = text.includes(searchValue) ? '' : 'none';
                }
            });
        });

        document.getElementById('searchPermohonan').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#tablePermohonan tbody tr');

            tableRows.forEach(function(row) {
                const namaDudi = row.querySelector('td:nth-child(2)');
                if (namaDudi) {
                    const text = namaDudi.textContent.toLowerCase();
                    row.style.display = text.includes(searchValue) ? '' : 'none';
                }
            });
        });

        function confirmDelete(id, namaDudi) {
            Swal.fire({
                title: 'Hapus Surat?',
                html: 'Apakah Anda yakin ingin menghapus surat untuk DUDI <strong>' + namaDudi +
                    '</strong>?<br><small class="text-muted">File surat pengajuan dan balasan akan dihapus permanen.</small>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = '/admin/surat-dudi/' + id;
                    form.submit();
                }
            });
        }

        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?php echo e(session('success')); ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?php echo e(session('error')); ?>'
            });
        <?php endif; ?>
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\pkl-smktelkom\resources\views/admin/surat-dudi.blade.php ENDPATH**/ ?>