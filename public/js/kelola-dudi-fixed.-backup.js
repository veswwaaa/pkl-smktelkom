// Form validation function
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

// Function to reset form
function resetForm(formId) {
    const form = document.getElementById(formId);
    form.reset();
    form.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
    });
}

// Function to add new DUDI with proper error handling
function tambahDudi() {
    // Validate form first
    if (!validateForm('addDudiForm')) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: 'Mohon lengkapi semua field yang wajib diisi',
            confirmButtonColor: '#e53e3e'
        });
        return;
    }

    const formData = new FormData(document.getElementById('addDudiForm'));
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
            const text = await response.text();
            console.error('Non-JSON response:', text);
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
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat menyimpan data',
            confirmButtonColor: '#e53e3e'
        });
    });
}

// Function to edit DUDI with improved error handling
function updateDudi() {
    // Validate form first
    if (!validateForm('editDudiForm')) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: 'Mohon lengkapi semua field yang wajib diisi',
            confirmButtonColor: '#e53e3e'
        });
        return;
    }

    const form = document.getElementById('editDudiForm');
    const formData = new FormData(form);
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
            // Response bukan JSON
            const text = await response.text();
            console.error('Non-JSON response:', text);
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
        console.error('Error:', error);
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
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
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
                console.error('Error:', error);
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

// Function to show edit modal with data
function showEditModal(id, nama, telepon, alamat, pic) {
    // Set form action URL
    document.getElementById('editDudiForm').action = `/admin/dudi/${id}`;

    // Fill form with current data
    document.getElementById('edit_nama_dudi').value = nama;
    document.getElementById('edit_nomor_telpon').value = telepon;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_person_in_charge').value = pic;

    // Clear password field (optional update)
    document.getElementById('edit_password').value = '';

    // Show modal
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

// Function to show add modal
function showAddModal() {
    resetForm('addDudiForm');
    new bootstrap.Modal(document.getElementById('addModal')).show();
}

// Real-time validation on input
document.addEventListener('DOMContentLoaded', function() {
    // Add input event listeners for real-time validation
    const forms = ['addDudiForm', 'editDudiForm'];

    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    }
                });
            });
        }
    });

    // Password confirmation validation for add form
    const addPassword = document.getElementById('password');
    const addPasswordConfirm = document.getElementById('password_confirmation');

    if (addPassword && addPasswordConfirm) {
        addPasswordConfirm.addEventListener('input', function() {
            if (this.value === addPassword.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    }
});
