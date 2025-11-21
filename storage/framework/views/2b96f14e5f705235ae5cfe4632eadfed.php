<?php $__env->startSection('title', 'Upload Surat Pengajuan PKL'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <div>
                <i class="fas fa-file-upload"></i>
            </div>
            <div>
                <h1>Upload Surat Pengajuan PKL</h1>
                <p>Kirim surat pengajuan siswa PKL ke DUDI</p>
            </div>
        </div>
        <a href="/admin/surat-dudi" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="container-fluid">
        <!-- Alert Messages -->
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

        <!-- Info Card -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Informasi:</strong> Surat Pengajuan berisi daftar nama siswa yang akan PKL di DUDI. Pilih DUDI tujuan,
            checklist siswa yang akan dikirim, dan upload file surat.
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="data-table-card">
                <div class="table-header">
                    <div class="table-header-content">
                        <div class="table-title">
                            <i class="fas fa-upload me-2"></i>
                            <h5>Form Update Surat Pengajuan</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="/admin/surat-pengajuan/kirim" method="POST" enctype="multipart/form-data"
                        id="formSuratPengajuan">
                        <?php echo csrf_field(); ?>

                        <!-- Pilih DUDI -->
                        <div class="mb-4">
                            <label for="id_dudi" class="form-label">
                                <strong>Pilih DUDI Tujuan <span class="text-danger">*</span></strong>
                            </label>
                            <select class="form-select" id="id_dudi" name="id_dudi" required>
                                <option value="">-- Pilih DUDI --</option>
                                <?php $__currentLoopData = $dudis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dudi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dudi->id); ?>">
                                        <?php echo e($dudi->nama_dudi); ?>

                                        <?php if($dudi->jenis_dudi == 'mandiri'): ?>
                                            (Mandiri)
                                        <?php else: ?>
                                            (Sekolah)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted">Pilih DUDI terlebih dahulu untuk melihat siswa yang mendaftar</small>
                        </div>

                        <!-- Nomor Surat -->
                        <div class="mb-4">
                            <label for="nomor_surat" class="form-label">
                                <strong>Nomor Surat <span class="text-danger">*</span></strong>
                            </label>
                            <input type="text" class="form-control <?php $__errorArgs = ['nomor_surat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="nomor_surat" name="nomor_surat" required placeholder="Masukkan nomor surat"
                                value="<?php echo e(old('nomor_surat')); ?>">
                            <?php $__errorArgs = ['nomor_surat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Nomor surat akan ditampilkan di template PDF</small>
                        </div>

                        <!-- Daftar Siswa (akan muncul setelah DUDI dipilih) -->
                        <div class="mb-4" id="siswaSection" style="display: none;">
                            <label class="form-label">
                                <strong>Pilih Siswa yang Akan PKL <span class="text-danger">*</span></strong>
                            </label>

                            <!-- Loading Indicator -->
                            <div id="loadingIndicator" class="alert alert-info" style="display: none;">
                                <i class="fas fa-spinner fa-spin me-2"></i>Memuat daftar siswa yang mendaftar ke DUDI ini...
                            </div>

                            <!-- No Siswa Alert -->
                            <div id="noSiswaAlert" class="alert alert-warning" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Tidak ada siswa yang mendaftar ke DUDI ini.</strong>
                                <br><small>Belum ada siswa yang mengajukan PKL ke DUDI yang dipilih.</small>
                            </div>

                            <!-- Siswa Card -->
                            <div class="card" id="siswaCard" style="display: none;">
                                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                    <!-- Search Box -->
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="searchSiswa"
                                            placeholder="🔍 Cari nama siswa...">
                                    </div>

                                    <!-- Select All -->
                                    <div class="form-check mb-3 border-bottom pb-2">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label fw-bold" for="selectAll">
                                            Pilih Semua Siswa (<span id="siswaCount">0</span>)
                                        </label>
                                    </div>

                                    <!-- List Siswa -->
                                    <div id="siswaList"></div>
                                </div>
                            </div>
                            <small class="text-muted">Hanya menampilkan siswa yang mendaftar ke DUDI ini</small>
                        </div>

                        <!-- Upload Template Surat (Opsional) -->
                        <div class="mb-4">
                            <label class="form-label">
                                <strong>Template Surat (Opsional)</strong>
                            </label>
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="useTemplate"
                                            name="use_template">
                                        <label class="form-check-label" for="useTemplate">
                                            <strong>Upload Template Surat Sendiri</strong>
                                        </label>
                                    </div>

                                    <!-- Auto Generate Info (Default) -->
                                    <div id="autoGenerateInfo">
                                        <div class="alert alert-success mb-0">
                                            <i class="fas fa-magic me-2"></i>
                                            <strong>Surat PDF Akan Dibuat Otomatis</strong>
                                            <br><small>Sistem akan membuat file PDF surat pengajuan secara otomatis berisi
                                                daftar siswa yang dipilih.</small>
                                        </div>
                                    </div>

                                    <!-- Upload Template Section (Hidden by default) -->
                                    <div id="uploadTemplateSection" style="display: none;">
                                        <div class="alert alert-info mb-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <small>Upload template surat dalam format Word (.docx) atau PDF. Template akan
                                                digunakan sebagai dasar surat yang dikirim ke DUDI.</small>
                                        </div>
                                        <input class="form-control" type="file" id="template_file"
                                            name="template_file" accept=".pdf,.doc,.docx">
                                        <small class="text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label for="catatan" class="form-label">
                                <strong>Catatan (Opsional)</strong>
                            </label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"
                                placeholder="Tambahkan catatan untuk DUDI jika diperlukan..."></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="/admin/surat-dudi" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Surat Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Load siswa when DUDI is selected
            document.getElementById('id_dudi').addEventListener('change', function() {
                const dudiId = this.value;
                const siswaSection = document.getElementById('siswaSection');
                const loadingIndicator = document.getElementById('loadingIndicator');
                const noSiswaAlert = document.getElementById('noSiswaAlert');
                const siswaCard = document.getElementById('siswaCard');
                const siswaList = document.getElementById('siswaList');

                if (!dudiId) {
                    siswaSection.style.display = 'none';
                    return;
                }

                // Show section and loading
                siswaSection.style.display = 'block';
                loadingIndicator.style.display = 'block';
                noSiswaAlert.style.display = 'none';
                siswaCard.style.display = 'none';
                siswaList.innerHTML = '';

                // Fetch siswa yang mendaftar ke DUDI ini
                fetch(`/admin/surat-dudi/siswa/${dudiId}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingIndicator.style.display = 'none';

                        if (!data.success || data.siswas.length === 0) {
                            noSiswaAlert.style.display = 'block';
                            return;
                        }

                        // Show siswa card
                        siswaCard.style.display = 'block';
                        document.getElementById('siswaCount').textContent = data.siswas.length;

                        // Populate siswa list
                        data.siswas.forEach(siswa => {
                            const div = document.createElement('div');
                            div.className = 'form-check mb-2 siswa-item';
                            div.setAttribute('data-nama', siswa.nama.toLowerCase());
                            div.innerHTML = `
                            <input class="form-check-input siswa-checkbox" type="checkbox"
                                name="siswa_ids[]" value="${siswa.id}" id="siswa${siswa.id}">
                            <label class="form-check-label" for="siswa${siswa.id}">
                                <strong>${siswa.nama}</strong> - ${siswa.nis}
                                <br>
                                <small class="text-muted">
                                    ${siswa.jurusan} | Kelas ${siswa.kelas}
                                </small>
                            </label>
                        `;
                            siswaList.appendChild(div);
                        });

                        // Reinitialize event listeners
                        initializeSiswaEvents();
                    })
                    .catch(error => {
                        loadingIndicator.style.display = 'none';
                        noSiswaAlert.style.display = 'block';
                        console.error('Error loading siswa:', error);
                    });
            });

            // Initialize siswa-related event listeners
            function initializeSiswaEvents() {
                // Search siswa
                document.getElementById('searchSiswa').addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const siswaItems = document.querySelectorAll('.siswa-item');

                    siswaItems.forEach(item => {
                        const nama = item.getAttribute('data-nama');
                        if (nama.includes(searchTerm)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });

                // Select All functionality
                document.getElementById('selectAll').addEventListener('change', function(e) {
                    const checkboxes = document.querySelectorAll('.siswa-checkbox');
                    const visibleCheckboxes = Array.from(checkboxes).filter(cb => {
                        return cb.closest('.siswa-item').style.display !== 'none';
                    });

                    visibleCheckboxes.forEach(checkbox => {
                        checkbox.checked = e.target.checked;
                    });
                });

                // Update select all when individual checkbox changes
                document.querySelectorAll('.siswa-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allCheckboxes = document.querySelectorAll('.siswa-checkbox');
                        const visibleCheckboxes = Array.from(allCheckboxes).filter(cb => {
                            return cb.closest('.siswa-item').style.display !== 'none';
                        });
                        const allChecked = visibleCheckboxes.every(cb => cb.checked);
                        document.getElementById('selectAll').checked = allChecked;
                    });
                });
            }

            // Toggle template upload section
            document.getElementById('useTemplate').addEventListener('change', function() {
                const autoInfo = document.getElementById('autoGenerateInfo');
                const uploadSection = document.getElementById('uploadTemplateSection');
                const templateFile = document.getElementById('template_file');

                if (this.checked) {
                    autoInfo.style.display = 'none';
                    uploadSection.style.display = 'block';
                    templateFile.required = true;
                } else {
                    autoInfo.style.display = 'block';
                    uploadSection.style.display = 'none';
                    templateFile.required = false;
                    templateFile.value = '';
                }
            });

            // File size validation for template
            document.getElementById('template_file')?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.size > 5 * 1024 * 1024) { // 5MB
                    alert('⚠️ Ukuran file maksimal 5MB!');
                    e.target.value = '';
                }
            });

            // Form validation
            document.getElementById('formSuratPengajuan').addEventListener('submit', function(e) {
                const checkedSiswa = document.querySelectorAll('.siswa-checkbox:checked');

                if (checkedSiswa.length === 0) {
                    e.preventDefault();
                    alert('⚠️ Pilih minimal 1 siswa untuk surat pengajuan!');
                    return false;
                }

                if (!confirm(`Kirim surat pengajuan untuk ${checkedSiswa.length} siswa?`)) {
                    e.preventDefault();
                    return false;
                }
            });
        </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\pkl-smktelkom\resources\views/admin/surat-pengajuan.blade.php ENDPATH**/ ?>