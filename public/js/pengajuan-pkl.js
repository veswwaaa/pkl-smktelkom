// Pengajuan PKL JavaScript

// Show success/error messages
document.addEventListener("DOMContentLoaded", function () {
    const successMsg = document.getElementById("successMessage");
    const errorMsg = document.getElementById("errorMessage");

    if (successMsg && successMsg.dataset.message) {
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: successMsg.dataset.message,
            confirmButtonColor: "#0d6efd",
        }).then(() => {
            // Reload page to update dropdown
            window.location.reload();
        });
    }

    if (errorMsg && errorMsg.dataset.message) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: errorMsg.dataset.message,
            confirmButtonColor: "#dc3545",
        });
    }
});

// Form validation before submit
const formPengajuanPkl = document.getElementById("formPengajuanPkl");
if (formPengajuanPkl) {
    formPengajuanPkl.addEventListener("submit", function (e) {
        const pilihan1 = document.getElementById("pilihan1").value;
        const pilihan2 = document.getElementById("pilihan2").value;
        const pilihan3 = document.getElementById("pilihan3").value;

        if (!pilihan1 || !pilihan2 || !pilihan3) {
            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Harap pilih semua DUDI (Pilihan 1, 2, dan 3)",
                confirmButtonColor: "#dc3545",
            });
            return false;   
        }

        // Check for duplicate selections
        const selections = [pilihan1, pilihan2, pilihan3];
        const uniqueSelections = new Set(selections);

        if (uniqueSelections.size !== selections.length) {
            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Duplikasi!",
                text: "Anda tidak boleh memilih DUDI yang sama untuk pilihan berbeda",
                confirmButtonColor: "#dc3545",
            });
            return false;
        }
    });
}
