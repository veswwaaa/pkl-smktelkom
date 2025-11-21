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
                console.log("Data Siswa:", siswa);
                console.log("Grade Kurikulum:", siswa.grade_kurikulum);
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
                                        ${
                                            siswa.grade_kurikulum === "D" ||
                                            siswa.grade_kurikulum === "E" ||
                                            p.pilihan_aktif ===
                                                "SMK Telkom Banjarbaru"
                                                ? `<option value="SMK Telkom Banjarbaru" ${
                                                      p.pilihan_aktif ===
                                                      "SMK Telkom Banjarbaru"
                                                          ? "selected"
                                                          : ""
                                                  }>PKL di Sekolah (SMK Telkom Banjarbaru)</option>`
                                                : ""
                                        }
                                    </select>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-sync-alt"></i> Ubah
                                    </button>
                                </div>
                                ${
                                    siswa.grade_kurikulum === "D"
                                        ? '<small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Grade D: Pilihan PKL di sekolah tersedia atas keputusan admin</small>'
                                        : siswa.grade_kurikulum === "E"
                                        ? '<small class="text-danger"><i class="fas fa-exclamation-circle"></i> Grade E: Siswa HARUS PKL di sekolah (otomatis)</small>'
                                        : ""
                                }
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
    const selectedText =
        selectElement.options[selectElement.selectedIndex].text;

    const isPklSekolah = pilihanAktif === "SMK Telkom Banjarbaru";

    const confirmMessage = isPklSekolah
        ? `Siswa akan ditempatkan PKL di <strong>SMK Telkom Banjarbaru</strong>.<br><br><small class="text-success"><i class="fas fa-check-circle"></i> Status akan otomatis di-approve</small>`
        : `Pilihan aktif akan diubah ke <strong>${selectedText}</strong>`;

    Swal.fire({
        title: "Ubah Pilihan Aktif?",
        html: confirmMessage,
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
                        html: data.message || "Pilihan aktif berhasil diubah",
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
        title: "Approve Pengajuan PKL?",
        html: `
            <div class="text-start">
                <p>Anda akan menyetujui pengajuan PKL dari:</p>
                <strong>${nama}</strong>
                <p class="mt-2">Akan ditempatkan di:</p>
                <strong class="text-primary">${namaDudi}</strong>
                <hr>
                <div class="alert alert-success small">
                    <i class="fas fa-check-circle"></i> <strong>Yang akan dilakukan:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Siswa akan ditempatkan ke DUDI</li>
                        <li>Pengajuan akan dikirim ke akun DUDI</li>
                        <li>DUDI dapat melihat detail siswa dan memberikan approval akhir</li>
                    </ul>
                </div>
                <div class="alert alert-info small">
                    <i class="fas fa-info-circle"></i> <strong>Catatan:</strong> Pastikan Anda sudah menerima surat balasan persetujuan dari DUDI sebelum meng-approve!
                </div>
            </div>
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#6c757d",
        confirmButtonText: '<i class="fas fa-check"></i> Ya, Approve!',
        cancelButtonText: "Batal",
        reverseButtons: true,
        width: "550px",
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
        title: "Buat Akun DUDI?",
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
                        <li>Status pengajuan diubah ke <strong>"Diproses"</strong></li>
                    </ul>
                </div>
                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Langkah selanjutnya setelah akun dibuat:</strong>
                    <ol class="mb-0 mt-2">
                        <li>Kirim surat pengajuan PKL + CV & portofolio siswa ke DUDI</li>
                        <li>Tunggu surat balasan dari DUDI</li>
                        <li>Jika DUDI menyetujui, klik tombol <strong>"Approve"</strong></li>
                        <li>Jika DUDI menolak, klik tombol <strong>"Tolak"</strong></li>
                    </ol>
                </div>
                <div class="alert alert-danger small">
                    <i class="fas fa-info-circle"></i> <strong>PENTING:</strong> Siswa <u>BELUM</u> langsung diterima. Anda masih perlu menunggu persetujuan dari DUDI sebelum meng-approve pengajuan PKL siswa!
                </div>
            </div>
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#0d6efd",
        cancelButtonColor: "#6c757d",
        confirmButtonText:
            '<i class="fas fa-user-plus"></i> Ya, Buat Akun DUDI',
        cancelButtonText: "Batal",
        reverseButtons: true,
        width: "650px",
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: "Memproses...",
                html: "Sedang membuat akun DUDI",
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

            // Tambahkan pengajuan_id untuk mengubah status ke 'diproses'
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

// Confirm DUDI Tidak Bersedia (sebelum akun dibuat)
function confirmDudiTidakBersedia(pengajuanId, namaSiswa, namaDudi) {
    Swal.fire({
        title: "DUDI Tidak Bersedia?",
        html: `
            <div class="text-start">
                <p><strong>DUDI tidak bersedia dijadikan tempat PKL:</strong></p>
                <h5 class="text-danger">${namaDudi}</h5>
                <p class="text-muted small mt-2">Pengajuan dari: ${namaSiswa}</p>
                <hr>
                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Yang akan terjadi:</strong>
                    <ul class="mb-0 mt-2">
                        <li>DUDI Mandiri <strong>tidak akan dibuatkan akun</strong></li>
                        <li>Pengajuan siswa akan <strong>ditolak untuk pilihan ini</strong></li>
                        <li>Sistem otomatis pindah ke <strong>pilihan berikutnya</strong> (jika ada)</li>
                        <li>Siswa bisa mengajukan DUDI lain</li>
                    </ul>
                </div>
                <div class="form-group mt-3">
                    <label class="form-label">Alasan DUDI tidak bersedia:</label>
                    <textarea id="catatanTidakBersedia" class="form-control" rows="3" placeholder="Contoh: Kuota PKL sudah penuh, tidak menerima bidang IT, dll..." required></textarea>
                </div>
            </div>
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: '<i class="fas fa-ban"></i> Ya, Tidak Bersedia',
        cancelButtonText: "Batal",
        reverseButtons: true,
        width: "600px",
        preConfirm: () => {
            const catatan = document.getElementById(
                "catatanTidakBersedia"
            ).value;
            if (!catatan) {
                Swal.showValidationMessage(
                    "Harap isi alasan DUDI tidak bersedia"
                );
            }
            return { catatan: catatan };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: "Memproses...",
                html: "Sedang memproses penolakan",
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

            // Tambahkan catatan
            const catatanInput = document.createElement("input");
            catatanInput.type = "hidden";
            catatanInput.name = "catatan";
            catatanInput.value = `DUDI tidak bersedia: ${result.value.catatan}`;

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

// Show Upload Surat Modal
function showUploadSuratModal(pengajuanId, namaSiswa, namaDudi) {
    document.getElementById("modal_nama_siswa").textContent = namaSiswa;
    document.getElementById("modal_nama_dudi").textContent = namaDudi;

    const form = document.getElementById("formUploadSurat");
    form.action = `/admin/pengajuan-pkl/${pengajuanId}/upload-surat`;

    const modal = new bootstrap.Modal(
        document.getElementById("uploadSuratModal")
    );
    modal.show();
}

// Confirm Approve After Response (setelah ada balasan dari DUDI)
function confirmApproveAfterResponse(id, nama, namaDudi) {
    Swal.fire({
        title: "Approve Pengajuan PKL?",
        html: `
            <div class="text-start">
                <p><strong>DUDI telah menyetujui siswa:</strong></p>
                <h5 class="text-primary">${nama}</h5>
                <p class="mt-2">Akan ditempatkan di:</p>
                <h5 class="text-success">${namaDudi}</h5>
                <hr>
                <div class="alert alert-success small">
                    <i class="fas fa-check-circle"></i> <strong>Konfirmasi:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Surat balasan DUDI menyatakan <strong>MENERIMA</strong></li>
                        <li>Siswa akan ditempatkan ke DUDI</li>
                        <li>Status pengajuan akan diubah menjadi <strong>APPROVED</strong></li>
                    </ul>
                </div>
            </div>
        `,
        icon: "success",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#6c757d",
        confirmButtonText: '<i class="fas fa-check"></i> Ya, Approve!',
        cancelButtonText: "Batal",
        reverseButtons: true,
        width: "550px",
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

// Confirm Reject After Response (setelah DUDI menolak)
function confirmRejectAfterResponse(id, nama, catatanDudi) {
    Swal.fire({
        title: "Tolak Pengajuan PKL?",
        html: `
            <div class="text-start">
                <p><strong>DUDI telah menolak siswa:</strong></p>
                <h5 class="text-danger">${nama}</h5>
                <hr>
                <div class="alert alert-danger small">
                    <i class="fas fa-times-circle"></i> <strong>Catatan dari DUDI:</strong>
                    <p class="mt-2 mb-0">${
                        catatanDudi || "Tidak ada catatan"
                    }</p>
                </div>
                <div class="alert alert-warning small">
                    <i class="fas fa-info-circle"></i> Sistem akan otomatis memindahkan ke pilihan DUDI berikutnya (jika ada).
                </div>
            </div>
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: '<i class="fas fa-times"></i> Ya, Tolak',
        cancelButtonText: "Batal",
        reverseButtons: true,
        width: "550px",
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

            // Gunakan catatan dari DUDI
            const catatanInput = document.createElement("input");
            catatanInput.type = "hidden";
            catatanInput.name = "catatan";
            catatanInput.value = catatanDudi || "DUDI menolak";

            form.appendChild(csrfInput);
            form.appendChild(catatanInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// View Detail menggunakan data lokal (tanpa fetch ke server)
function viewDetailLocal(data) {
    const modal = new bootstrap.Modal(document.getElementById("detailModal"));

    // Set data siswa
    document.getElementById("detail_nama_siswa").textContent = data.nama;
    document.getElementById("detail_nis").textContent = data.nis;
    document.getElementById("detail_kelas").textContent = data.kelas;
    document.getElementById("detail_jurusan").textContent = data.jurusan;

    // Set data pengajuan
    document.getElementById("detail_pilihan1").textContent =
        data.pilihan1 || "-";
    document.getElementById("detail_pilihan2").textContent =
        data.pilihan2 || "-";
    document.getElementById("detail_pilihan3").textContent =
        data.pilihan3 || "-";
    document.getElementById("detail_pilihan_aktif").textContent =
        "Pilihan " + data.pilihanAktif;

    // Set status dengan badge
    const statusBadge =
        data.status === "approved"
            ? '<span class="badge bg-success">Approved</span>'
            : data.status === "rejected"
            ? '<span class="badge bg-danger">Rejected</span>'
            : data.status === "diproses"
            ? '<span class="badge bg-warning">Diproses</span>'
            : '<span class="badge bg-secondary">Pending</span>';
    document.getElementById("detail_status").innerHTML = statusBadge;

    document.getElementById("detail_tanggal_pengajuan").textContent =
        data.tanggal;

    modal.show();
}
