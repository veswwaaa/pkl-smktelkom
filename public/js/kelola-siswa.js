// DOM Content Loaded Event
document.addEventListener("DOMContentLoaded", function () {
    // Success Message
    const successMessage = document.getElementById("success-message");
    if (successMessage) {
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: successMessage.getAttribute("data-message"),
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    // Error Message
    const errorMessage = document.getElementById("error-message");
    if (errorMessage) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: errorMessage.getAttribute("data-message"),
            confirmButtonText: "OK",
            confirmButtonColor: "#d33",
        });
    }

    // Validation Errors
    const validationErrors = document.getElementById("validation-errors");
    if (validationErrors) {
        const errors = JSON.parse(validationErrors.getAttribute("data-errors"));
        let errorText = "";
        errors.forEach(function (error, index) {
            errorText += index + 1 + ". " + error + "\n";
        });

        Swal.fire({
            icon: "warning",
            title: "Validasi Gagal",
            text: errorText,
            confirmButtonText: "Perbaiki",
            confirmButtonColor: "#f39c12",
        });
    }
});

// Function untuk konfirmasi delete
function confirmDelete(id, nama, nis) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        html: `Anda akan menghapus siswa:<br><strong>${nama}</strong><br>NIS: <strong>${nis}</strong><br><br>Data yang dihapus tidak dapat dikembalikan!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form delete
            document.getElementById("delete-form-" + id).submit();
        }
    });
}

// Function untuk membuka modal edit siswa
function openEditSiswaModal(id, siswaData) {
    // Reset form
    document.getElementById("editSiswaForm").reset();

    // Get siswa data by id
    const siswaItem = siswaData.find((item) => item.id === id);

    // Set form values
    document.getElementById("edit_nis").value = siswaItem.nis;
    document.getElementById("edit_nama").value = siswaItem.nama;
    document.getElementById("edit_kelas").value = siswaItem.kelas;
    document.getElementById("edit_tahun_ajaran").value = siswaItem.tahun_ajaran;
    document.getElementById("edit_jurusan").value = siswaItem.jurusan;
    document.getElementById("edit_jenis_kelamin").value =
        siswaItem.jenis_kelamin;

    // Set form action
    document.getElementById("editSiswaForm").action = "/admin/siswa/" + id;

    // Show modal
    const editSiswaModal = new bootstrap.Modal(
        document.getElementById("editSiswaModal"),
    );
    editSiswaModal.show();
}

// Function untuk edit siswa
function editSiswa(id, nis, nama, kelas, jenis_kelamin, tahun_ajaran, jurusan) {
    // Set form action URL
    document.getElementById("editSiswaForm").action = "/admin/siswa/" + id;

    // Isi field form dengan data siswa
    document.getElementById("edit_nis").value = nis;
    document.getElementById("edit_nama").value = nama;
    document.getElementById("edit_kelas").value = kelas;
    document.getElementById("edit_jenis_kelamin").value = jenis_kelamin;
    document.getElementById("edit_tahun_ajaran").value = tahun_ajaran;
    document.getElementById("edit_jurusan").value = jurusan;

    // Tampilkan modal
    var editModal = new bootstrap.Modal(
        document.getElementById("editSiswaModal"),
    );
    editModal.show();
}

// Function untuk lihat detail PKL
function viewPklDetail(id, nama, dudi, tanggalMulai, tanggalSelesai) {
    document.getElementById("detailNamaSiswa").textContent = nama;
    document.getElementById("detailNamaDudi").textContent = dudi || "-";
    document.getElementById("detailTanggalMulai").textContent =
        tanggalMulai || "-";
    document.getElementById("detailTanggalSelesai").textContent =
        tanggalSelesai || "-";

    var detailModal = new bootstrap.Modal(
        document.getElementById("pklDetailModal"),
    );
    detailModal.show();
}

// Function untuk membuka modal set tanggal PKL
function openSetTanggalModal(id, nama, tanggalMulai, tanggalSelesai) {
    document.getElementById("setTanggalNamaSiswa").textContent = nama;
    document.getElementById("setTanggalForm").action =
        "/admin/siswa/" + id + "/set-tanggal-pkl";

    // Set value jika sudah ada
    document.getElementById("tanggal_mulai_pkl").value = tanggalMulai || "";
    document.getElementById("tanggal_selesai_pkl").value = tanggalSelesai || "";

    var setTanggalModal = new bootstrap.Modal(
        document.getElementById("setTanggalModal"),
    );
    setTanggalModal.show();
}

// Function untuk konfirmasi batalkan penempatan
function confirmCancelAssignment(id, nama) {
    Swal.fire({
        title: "Batalkan Penempatan?",
        html: `Anda akan membatalkan penempatan PKL untuk:<br><strong>${nama}</strong><br><br>Siswa akan kembali ke status "Belum Ditempatkan"`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Batalkan!",
        cancelButtonText: "Batal",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form cancel assignment
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "/admin/siswa/" + id + "/cancel-assignment";

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = csrfToken;

            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// --- Fungsi Bulk Delete Siswa ---

function toggleSelectAll() {
    const selectAll = document.getElementById("selectAll");
    const checkboxes = document.querySelectorAll(".siswa-checkbox");
    checkboxes.forEach((cb) => {
        cb.checked = selectAll.checked;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll(".siswa-checkbox:checked");
    const count = checkboxes.length;
    const btnBulkDelete = document.getElementById("btnBulkDelete");
    const selectedCountSpan = document.getElementById("selectedCount");

    if (selectedCountSpan) selectedCountSpan.innerText = count;

    if (count > 0) {
        if (btnBulkDelete) btnBulkDelete.style.display = "inline-block";
    } else {
        if (btnBulkDelete) btnBulkDelete.style.display = "none";
        // Reset selectAll jika tidak ada yang dicentang
        const selectAll = document.getElementById("selectAll");
        if (selectAll) selectAll.checked = false;
    }
}

function bulkDeleteSiswa() {
    const checkboxes = document.querySelectorAll(".siswa-checkbox:checked");
    const ids = Array.from(checkboxes).map((cb) => cb.value);

    if (ids.length === 0) return;

    Swal.fire({
        title: "Hapus " + ids.length + " data siswa?",
        text: "Seluruh data siswa yang dipilih akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus Semua!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "/admin/siswa/bulk-delete";

            const csrfTokenElement = document.querySelector(
                'meta[name="csrf-token"]',
            );
            if (csrfTokenElement) {
                const csrfInput = document.createElement("input");
                csrfInput.type = "hidden";
                csrfInput.name = "_token";
                csrfInput.value = csrfTokenElement.getAttribute("content");
                form.appendChild(csrfInput);
            }

            ids.forEach((id) => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "ids[]";
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });
}
