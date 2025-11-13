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
    document.getElementById("edit_angkatan").value = siswaItem.angkatan;
    document.getElementById("edit_jurusan").value = siswaItem.jurusan;
    document.getElementById("edit_jenis_kelamin").value =
        siswaItem.jenis_kelamin;

    // Set form action
    document.getElementById("editSiswaForm").action = "/admin/siswa/" + id;

    // Show modal
    const editSiswaModal = new bootstrap.Modal(
        document.getElementById("editSiswaModal")
    );
    editSiswaModal.show();
}

// Function untuk edit siswa
function editSiswa(id, nis, nama, kelas, jenis_kelamin, angkatan, jurusan) {
    // Set form action URL
    document.getElementById("editSiswaForm").action = "/admin/siswa/" + id;

    // Isi field form dengan data siswa
    document.getElementById("edit_nis").value = nis;
    document.getElementById("edit_nama").value = nama;
    document.getElementById("edit_kelas").value = kelas;
    document.getElementById("edit_jenis_kelamin").value = jenis_kelamin;
    document.getElementById("edit_angkatan").value = angkatan;
    document.getElementById("edit_jurusan").value = jurusan;

    // Tampilkan modal
    var editModal = new bootstrap.Modal(
        document.getElementById("editSiswaModal")
    );
    editModal.show();
}

// Function untuk membuka modal assign DUDI
function openAssignModal(id, nama, nis) {
    document.getElementById("assignSiswaName").textContent = nama;
    document.getElementById("assignSiswaNis").textContent = nis;
    document.getElementById("assignDudiForm").action =
        "/admin/siswa/" + id + "/assign";
    document.getElementById("assignDudiForm").reset();

    var assignModal = new bootstrap.Modal(
        document.getElementById("assignDudiModal")
    );
    assignModal.show();
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
        document.getElementById("pklDetailModal")
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
        document.getElementById("setTanggalModal")
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
