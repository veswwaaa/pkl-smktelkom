// Kelola DUDI JavaScript Functions

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Document loaded, initializing...');

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (alert.querySelector('.btn-close')) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);

    console.log('JavaScript loaded successfully');
});

// Function to show add DUDI modal
function showAddModal() {
    console.log('showAddModal called');
    var addModal = new bootstrap.Modal(document.getElementById('addModal'));

    // Reset form
    document.getElementById('addForm').reset();

    // Show modal
    addModal.show();
    console.log('Add modal shown');
}

// Function to submit add form
function submitAdd() {
    var form = document.getElementById('addForm');
    var formData = new FormData(form);

    // Add CSRF token
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', csrfToken);

    // Show loading
    Swal.fire({
        title: 'Menyimpan Data...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Submit form via AJAX
    fetch('/admin/dudi', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Terjadi kesalahan pada server');
            }
            return data;
        } else {
            // Response bukan JSON, kemungkinan redirect atau error page
            throw new Error('Server tidak mengembalikan response JSON yang valid. Pastikan form data benar.');
        }
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Close modal and reload page
                bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat menyimpan data',
            confirmButtonColor: '#e53e3e'
        });
    });
}

// Function to show edit modal
function editDudi(id, nama, telepon, alamat, pic) {
    console.log('editDudi called with:', id, nama, telepon, alamat, pic);
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));

    // Fill form with data
    document.getElementById('edit_nama_dudi').value = nama;
    document.getElementById('edit_nomor_telpon').value = telepon;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_person_in_charge').value = pic;

    // Set form action
    document.getElementById('editForm').action = `/admin/dudi/${id}`;

    // Show modal
    editModal.show();
    console.log('Edit modal shown');
}

// Function to submit edit form
function submitEdit() {
    var form = document.getElementById('editForm');
    var formData = new FormData(form);

    // Add CSRF token and method
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', csrfToken);
    formData.append('_method', 'PUT');

    // Show loading
    Swal.fire({
        title: 'Mengupdate Data...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Submit form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Terjadi kesalahan pada server');
            }
            return data;
        } else {
            // Response bukan JSON, kemungkinan redirect atau error page
            throw new Error('Server tidak mengembalikan response JSON yang valid. Pastikan form data benar.');
        }
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Close modal and reload page
                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat mengupdate data',
            confirmButtonColor: '#e53e3e'
        });
    });
}

// Function to delete DUDI with validation
function deleteDudi(id, nama) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `Apakah Anda yakin ingin menghapus DUDI:<br><strong>"${nama}"</strong>?<br><br><small class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan!</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus Data...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Get CSRF token
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Submit delete request
            fetch(`/admin/dudi/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan pada server');
                    }
                    return data;
                } else {
                    // Response bukan JSON
                    throw new Error('Server tidak mengembalikan response JSON yang valid.');
                }
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan saat menghapus data',
                    confirmButtonColor: '#e53e3e'
                });
            });
        }
    });
}

// Function to toggle sidebar on mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.left-sidebar');
    sidebar.classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.querySelector('.left-sidebar');
    const sidebarButton = document.querySelector('.sidebar-toggle');

    if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !sidebarButton?.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    }
});

// Handle responsive behavior
window.addEventListener('resize', function() {
    const sidebar = document.querySelector('.left-sidebar');
    if (window.innerWidth > 768) {
        sidebar.classList.remove('show');
    }
});

// Form validation enhancement
function validateForm(formId) {
    const form = document.getElementById(formId);
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Add input event listeners for real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const forms = ['addForm', 'editForm'];

    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.hasAttribute('required') && this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            });
        }
    });
});

// Success and error message handling from server
function showServerMessage() {
    // Check for success message
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        const message = successAlert.textContent.trim();
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                showConfirmButton: false,
                timer: 2000
            });
        }, 100);
    }

    // Check for error message
    const errorAlert = document.querySelector('.alert-danger');
    if (errorAlert) {
        const message = errorAlert.textContent.trim();
        setTimeout(() => {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: message,
                confirmButtonColor: '#e53e3e'
            });
        }, 100);
    }
}
