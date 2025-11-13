// View Detail
function viewDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById("detailModal"));
    modal.show();

    fetch(`/admin/pengajuan-pkl/${id}/detail`, {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const p = data.data;
                const siswa = p.siswa;

                // Debug - cek data
                console.log("Data lengkap:", p);
                console.log("DUDI Pilihan 1:", p.dudi_pilihan1);
                console.log("DUDI Pilihan 2:", p.dudi_pilihan2);
                console.log("DUDI Pilihan 3:", p.dudi_pilihan3);

                // Get pilihan DUDI - gunakan snake_case sesuai JSON Laravel
                const pilihan1 = p.dudi_pilihan1
                    ? p.dudi_pilihan1.nama_dudi
                    : p.dudi_mandiri_pilihan1
                    ? p.dudi_mandiri_pilihan1.nama_dudi + " (Mandiri)"
                    : "-";
                const pilihan2 = p.dudi_pilihan2
                    ? p.dudi_pilihan2.nama_dudi
                    : p.dudi_mandiri_pilihan2
                    ? p.dudi_mandiri_pilihan2.nama_dudi + " (Mandiri)"
                    : "-";
                const pilihan3 = p.dudi_pilihan3
                    ? p.dudi_pilihan3.nama_dudi
                    : p.dudi_mandiri_pilihan3
                    ? p.dudi_mandiri_pilihan3.nama_dudi + " (Mandiri)"
                    : "-";

                console.log("Pilihan 1 nama:", pilihan1);
                console.log("Pilihan 2 nama:", pilihan2);
                console.log("Pilihan 3 nama:", pilihan3);

                // Function untuk membuat HTML detail DUDI Mandiri
                function getDudiMandiriDetailHTML(dudiMandiri) {
                    if (!dudiMandiri) return "";

                    return `
                        <div class="dudi-mandiri-detail mt-2 p-3 border-start border-info border-3 bg-light">
                            <div class="mb-2">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                <strong class="text-info">Detail DUDI Mandiri:</strong>
                            </div>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td width="35%" class="text-muted"><i class="fas fa-map-marker-alt me-1"></i> Alamat:</td>
                                    <td><strong>${
                                        dudiMandiri.alamat || "-"
                                    }</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-phone me-1"></i> No. Telepon:</td>
                                    <td><strong>${
                                        dudiMandiri.nomor_telepon || "-"
                                    }</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-user-tie me-1"></i> Person in Charge:</td>
                                    <td><strong>${
                                        dudiMandiri.person_in_charge || "-"
                                    }</strong></td>
                                </tr>
                            </table>
                        </div>
                    `;
                }

                document.getElementById("detailContent").innerHTML = `
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="fas fa-building me-2"></i>Pilihan DUDI</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th width="20%">Pilihan 1</th>
                                    <td>
                                        ${pilihan1} ${
                    p.pilihan_aktif == "1"
                        ? '<span class="badge bg-success">Aktif</span>'
                        : ""
                }
                                        ${getDudiMandiriDetailHTML(
                                            p.dudi_mandiri_pilihan1
                                        )}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Pilihan 2</th>
                                    <td>
                                        ${pilihan2} ${
                    p.pilihan_aktif == "2"
                        ? '<span class="badge bg-success">Aktif</span>'
                        : ""
                }
                                        ${getDudiMandiriDetailHTML(
                                            p.dudi_mandiri_pilihan2
                                        )}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Pilihan 3</th>
                                    <td>
                                        ${pilihan3} ${
                    p.pilihan_aktif == "3"
                        ? '<span class="badge bg-success">Aktif</span>'
                        : ""
                }
                                        ${getDudiMandiriDetailHTML(
                                            p.dudi_mandiri_pilihan3
                                        )}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <td>${new Date(
                                        p.tanggal_pengajuan
                                    ).toLocaleDateString("id-ID", {
                                        year: "numeric",
                                        month: "long",
                                        day: "numeric",
                                    })}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="badge bg-${
                                        p.status == "approved"
                                            ? "success"
                                            : p.status == "rejected"
                                            ? "danger"
                                            : "warning"
                                    }">${p.status.toUpperCase()}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="fas fa-cog me-2"></i>Ubah Pilihan Aktif</h6>
                            <form id="formChangePilihan-${
                                p.id
                            }" onsubmit="changePilihan(event, ${p.id})">
                                <div class="input-group">
                                    <select name="pilihan_aktif" class="form-select" id="selectPilihan-${
                                        p.id
                                    }">
                                        <option value="1" ${
                                            p.pilihan_aktif == "1"
                                                ? "selected"
                                                : ""
                                        } ${pilihan1 === "-" ? "disabled" : ""}>
                                            Pilihan 1${
                                                pilihan1 !== "-"
                                                    ? ": " + pilihan1
                                                    : " (Tidak diisi)"
                                            }
                                        </option>
                                        <option value="2" ${
                                            p.pilihan_aktif == "2"
                                                ? "selected"
                                                : ""
                                        } ${pilihan2 === "-" ? "disabled" : ""}>
                                            Pilihan 2${
                                                pilihan2 !== "-"
                                                    ? ": " + pilihan2
                                                    : " (Tidak diisi)"
                                            }
                                        </option>
                                        <option value="3" ${
                                            p.pilihan_aktif == "3"
                                                ? "selected"
                                                : ""
                                        } ${pilihan3 === "-" ? "disabled" : ""}>
                                            Pilihan 3${
                                                pilihan3 !== "-"
                                                    ? ": " + pilihan3
                                                    : " (Tidak diisi)"
                                            }
                                        </option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-sync-alt"></i> Ubah
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById("detailContent").innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> ${data.message}
                    </div>
                `;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            document.getElementById("detailContent").innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan saat memuat data
                </div>
            `;
        });
}

// Change Pilihan Aktif
function changePilihan(event, id) {
    event.preventDefault();

    const selectElement = document.getElementById(`selectPilihan-${id}`);
    const pilihanAktif = selectElement.value;

    Swal.fire({
        title: "Ubah Pilihan Aktif?",
        text: `Pilihan aktif akan diubah ke Pilihan ${pilihanAktif}`,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#0d6efd",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Ubah",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: "Mengubah...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch(`/admin/pengajuan-pkl/${id}/change-pilihan`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    pilihan_aktif: pilihanAktif,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: "Pilihan aktif berhasil diubah",
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        // Reload page to refresh data
                        window.location.reload();
                    });
                })
                .catch((error) => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat mengubah pilihan",
                        confirmButtonColor: "#e53e3e",
                    });
                });
        }
    });
}

// Confirm Approve
function confirmApprove(id, nama, namaDudi) {
    Swal.fire({
        title: "Approve & Kirim ke DUDI?",
        html: `
            <div class="text-start">
                <p>Anda akan menyetujui pengajuan PKL dari:</p>
                <strong>${nama}</strong>
                <p class="mt-2">Pengajuan akan dikirim ke:</p>
                <strong class="text-primary">${namaDudi}</strong>
                <hr>
                <div class="alert alert-info small">
                    <i class="fas fa-info-circle"></i> Siswa akan otomatis ditempatkan ke DUDI dan pengajuan akan dikirim ke akun DUDI untuk diproses lebih lanjut.
                </div>
            </div>
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#6c757d",
        confirmButtonText: '<i class="fas fa-check"></i> Ya, Approve & Kirim!',
        cancelButtonText: "Batal",
        reverseButtons: true,
        width: "500px",
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = `/admin/pengajuan-pkl/${id}/approve`;

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

// Confirm Reject
function confirmReject(id, nama) {
    Swal.fire({
        title: "Tolak Pengajuan?",
        html: `Anda akan menolak pengajuan PKL dari:<br><strong>${nama}</strong>`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ffc107",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Tolak",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = `/admin/pengajuan-pkl/${id}/reject`;

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

// Confirm Create DUDI Account (untuk DUDI Mandiri)
function confirmCreateDudiAccount(
    dudiMandiriId,
    namaDudi,
    pengajuanId,
    namaSiswa
) {
    Swal.fire({
        title: "Buat Akun DUDI & Approve?",
        html: `
            <div class="text-start">
                <p>Anda akan membuat akun untuk DUDI Mandiri:</p>
                <strong>${namaDudi}</strong>
                <p class="text-muted small mt-2">Diajukan oleh: ${namaSiswa}</p>
                <hr>
                <div class="alert alert-info small">
                    <i class="fas fa-info-circle"></i> <strong>Yang akan dilakukan:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Membuat data DUDI di sistem</li>
                        <li>Membuat akun login untuk DUDI</li>
                        <li>Approve pengajuan PKL siswa</li>
                        <li>Mengirim pengajuan ke DUDI</li>
                    </ul>
                </div>
                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle"></i> Pastikan Anda sudah menghubungi DUDI dan mendapat persetujuan!
                </div>
            </div>
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#0d6efd",
        cancelButtonColor: "#6c757d",
        confirmButtonText:
            '<i class="fas fa-user-plus"></i> Ya, Buat Akun & Approve',
        cancelButtonText: "Batal",
        reverseButtons: true,
        width: "600px",
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: "Memproses...",
                html: "Sedang membuat akun DUDI dan approve pengajuan PKL",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // Submit form untuk approve DUDI Mandiri
            const form = document.createElement("form");
            form.method = "POST";
            form.action = `/admin/dudi-mandiri/${dudiMandiriId}/approve`;

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = csrfToken;

            // Tambahkan pengajuan_id untuk auto-approve setelah create akun
            const pengajuanInput = document.createElement("input");
            pengajuanInput.type = "hidden";
            pengajuanInput.name = "pengajuan_id";
            pengajuanInput.value = pengajuanId;

            form.appendChild(csrfInput);
            form.appendChild(pengajuanInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Confirm Reject DUDI Mandiri (DUDI tidak setuju)
function confirmRejectDudiMandiri(pengajuanId, namaSiswa, namaDudi) {
    Swal.fire({
        title: "Tolak Pengajuan DUDI Mandiri?",
        html: `
            <div class="text-start">
                <p>Anda akan menolak pengajuan PKL karena DUDI tidak setuju:</p>
                <strong>Siswa:</strong> ${namaSiswa}<br>
                <strong>DUDI Mandiri:</strong> ${namaDudi}
                <hr>
                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Yang akan terjadi:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Pengajuan PKL siswa akan ditolak</li>
                        <li>Sistem akan otomatis pindah ke pilihan berikutnya (jika ada)</li>
                        <li>DUDI Mandiri tidak akan dibuatkan akun</li>
                    </ul>
                </div>
                <div class="form-group mt-3">
                    <label class="form-label">Catatan (Opsional):</label>
                    <textarea id="catatanReject" class="form-control" rows="3" placeholder="Alasan penolakan dari DUDI..."></textarea>
                </div>
            </div>
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: '<i class="fas fa-times-circle"></i> Ya, Tolak!',
        cancelButtonText: "Batal",
        reverseButtons: true,
        width: "600px",
        preConfirm: () => {
            return {
                catatan: document.getElementById("catatanReject").value,
            };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: "Memproses...",
                html: "Sedang menolak pengajuan PKL",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // Submit form untuk reject pengajuan
            const form = document.createElement("form");
            form.method = "POST";
            form.action = `/admin/pengajuan-pkl/${pengajuanId}/reject`;

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = csrfToken;

            // Tambahkan catatan jika ada
            const catatanInput = document.createElement("input");
            catatanInput.type = "hidden";
            catatanInput.name = "catatan";
            catatanInput.value =
                result.value.catatan || "DUDI Mandiri tidak menyetujui";

            form.appendChild(csrfInput);
            form.appendChild(catatanInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Confirm Delete
function confirmDelete(id, nama) {
    Swal.fire({
        title: "Hapus Pengajuan?",
        html: `Anda akan menghapus pengajuan PKL dari:<br><strong>${nama}</strong><br><br><small class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan!</small>`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = `/admin/pengajuan-pkl/${id}`;

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = csrfToken;

            const methodInput = document.createElement("input");
            methodInput.type = "hidden";
            methodInput.name = "_method";
            methodInput.value = "DELETE";

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
