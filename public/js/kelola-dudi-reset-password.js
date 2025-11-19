// Reset Password Functions for Kelola DUDI
let currentResetDudiId = null;
let currentResetDudiName = null;

// Function to show reset password confirmation modal
function resetPasswordDudi(dudiId, dudiName) {
    currentResetDudiId = dudiId;
    currentResetDudiName = dudiName;

    document.getElementById("reset_dudi_name").textContent = dudiName;

    const resetModal = new bootstrap.Modal(
        document.getElementById("resetPasswordModal")
    );
    resetModal.show();
}

// Function to confirm reset password
function confirmResetPassword() {
    if (!currentResetDudiId) {
        Swal.fire("Error", "Data DUDI tidak valid", "error");
        return;
    }

    // Show loading
    Swal.fire({
        title: "Mereset Password...",
        text: "Mohon tunggu sebentar",
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        },
    });

    // Send AJAX request
    fetch(`/admin/dudi/${currentResetDudiId}/reset-password`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            Swal.close();

            if (data.success) {
                // Hide confirmation modal
                const resetModal = bootstrap.Modal.getInstance(
                    document.getElementById("resetPasswordModal")
                );
                resetModal.hide();

                // Show result modal
                document.getElementById("result_dudi_name").textContent =
                    data.dudi_name;
                document.getElementById("new_password_display").value =
                    data.new_password;

                const resultModal = new bootstrap.Modal(
                    document.getElementById("resetPasswordResultModal")
                );
                resultModal.show();

                // Show success notification
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false,
                });
            } else {
                Swal.fire(
                    "Error",
                    data.message || "Terjadi kesalahan",
                    "error"
                );
            }
        })
        .catch((error) => {
            Swal.close();
            console.error("Error:", error);
            Swal.fire("Error", "Terjadi kesalahan sistem", "error");
        });
}

// Function to copy password to clipboard
function copyPassword() {
    const passwordField = document.getElementById("new_password_display");
    passwordField.select();
    passwordField.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard
        .writeText(passwordField.value)
        .then(function () {
            // Show copy success feedback
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Password telah disalin ke clipboard",
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: "top-end",
            });
        })
        .catch(function (err) {
            console.error("Could not copy text: ", err);
            // Fallback for older browsers
            document.execCommand("copy");
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Password telah disalin",
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: "top-end",
            });
        });
}

// Function untuk filter DUDI berdasarkan jenis
function filterDudi() {
    const jenisDudi = document.getElementById("filterJenisDudi").value;
    const currentUrl = new URL(window.location.href);

    if (jenisDudi) {
        currentUrl.searchParams.set("jenis_dudi", jenisDudi);
    } else {
        currentUrl.searchParams.delete("jenis_dudi");
    }

    window.location.href = currentUrl.toString();
}
