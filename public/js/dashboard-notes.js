// Dashboard Notes AJAX Handler
// Untuk fitur Catatan Admin di Dashboard

// Load notes saat halaman dimuat
document.addEventListener("DOMContentLoaded", function () {
    console.log("📝 Dashboard Notes: Initializing...");
    loadNotes();
    initScrollAnimations();
});

// Initialize scroll animations dan observer
function initScrollAnimations() {
    const notesList = document.getElementById("notesList");
    if (!notesList) return;

    // Deteksi scroll untuk fade effect di top
    notesList.addEventListener("scroll", function () {
        if (this.scrollTop > 10) {
            this.classList.add("is-scrolled");
        } else {
            this.classList.remove("is-scrolled");
        }
    });

    // Intersection Observer untuk animasi saat scroll
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                }
            });
        },
        {
            root: notesList,
            threshold: 0.1,
            rootMargin: "50px",
        }
    );

    // Observe semua note items
    const observeNotes = () => {
        notesList.querySelectorAll(".note-item").forEach((note) => {
            observer.observe(note);
        });
    };

    // Re-observe setelah load
    setTimeout(observeNotes, 100);

    // Re-observe setelah ada perubahan (mutation observer)
    const mutationObserver = new MutationObserver(observeNotes);
    mutationObserver.observe(notesList, { childList: true });
}

// Fungsi untuk load semua notes
function loadNotes() {
    const notesList = document.getElementById("notesList");

    console.log("📥 Loading notes from API...");

    // Show loading state
    notesList.innerHTML = `
        <div class="text-center text-muted py-3">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Memuat catatan...</p>
        </div>
    `;

    fetch("/admin/notes", {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        },
    })
        .then((response) => {
            console.log("📡 Response status:", response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            console.log("✅ Data received:", data);
            if (data.success) {
                displayNotes(data.data);
            } else {
                throw new Error(data.message || "Gagal memuat catatan");
            }
        })
        .catch((error) => {
            console.error("❌ Error loading notes:", error);
            notesList.innerHTML = `
                <div class="text-center text-danger py-4">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <p>Gagal memuat catatan</p>
                    <small>${error.message}</small>
                    <br>
                    <button class="btn btn-sm btn-primary mt-2" onclick="loadNotes()">
                        <i class="fas fa-redo"></i> Coba Lagi
                    </button>
                </div>
            `;
        });
}

// Fungsi untuk menampilkan notes di UI
function displayNotes(notes) {
    const notesList = document.getElementById("notesList");

    if (notes.length === 0) {
        notesList.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="fas fa-sticky-note fa-3x mb-3 opacity-25"></i>
                <p>Belum ada catatan. Klik tombol "+ Catatan Baru" untuk menambahkan.</p>
            </div>
        `;
        notesList.classList.remove("has-more-items");
        return;
    }

    notesList.innerHTML = notes
        .map(
            (note, index) => `
        <div class="note-item" data-note-id="${
            note.id
        }" style="animation-delay: ${index * 0.1}s">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="note-date text-primary mb-2">
                        <i class="fas fa-calendar-alt"></i> ${formatDate(
                            note.note_date
                        )}
                    </div>
                    <div class="note-content">${escapeHtml(note.content)}</div>
                </div>
                <div class="note-actions">
                    <button class="btn btn-sm btn-warning me-1" onclick="editNote(${
                        note.id
                    })" title="Edit catatan">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteNote(${
                        note.id
                    })" title="Hapus catatan">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `
        )
        .join("");

    // Tambahkan class jika ada lebih dari 4 catatan
    if (notes.length > 4) {
        notesList.classList.add("has-more-items");
    } else {
        notesList.classList.remove("has-more-items");
    }
}

// Fungsi untuk tambah note baru
function addNote() {
    Swal.fire({
        title: "Tambah Catatan Baru",
        html: `
            <div class="mb-3 text-start">
                <label class="form-label">Tanggal</label>
                <input type="date" id="note_date" class="swal2-input form-control" value="${getTodayDate()}">
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Catatan</label>
                <textarea id="note_content" class="swal2-textarea form-control" rows="4" placeholder="Tulis catatan di sini..."></textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: "Simpan",
        cancelButtonText: "Batal",
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#6c757d",
        preConfirm: () => {
            const date = document.getElementById("note_date").value;
            const content = document
                .getElementById("note_content")
                .value.trim();

            if (!date) {
                Swal.showValidationMessage("Tanggal harus diisi!");
                return false;
            }
            if (!content) {
                Swal.showValidationMessage("Catatan harus diisi!");
                return false;
            }

            return { note_date: date, content: content };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            saveNote(result.value);
        }
    });
}

// Fungsi untuk save note ke server
function saveNote(data) {
    fetch("/admin/notes", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false,
                });
                loadNotes(); // Reload notes
            } else {
                throw new Error(data.message || "Gagal menyimpan catatan");
            }
        })
        .catch((error) => {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text:
                    error.message || "Terjadi kesalahan saat menyimpan catatan",
            });
        });
}

// Fungsi untuk edit note
function editNote(id) {
    // Cari note by ID dari DOM
    const noteItem = document.querySelector(`[data-note-id="${id}"]`);
    const currentContent = noteItem
        .querySelector(".note-content")
        .textContent.trim();
    const currentDate = noteItem
        .querySelector(".note-date")
        .textContent.trim()
        .split(" ")[1]; // Ambil tanggal dari format "📅 YYYY-MM-DD"

    Swal.fire({
        title: "Edit Catatan",
        html: `
            <div class="mb-3 text-start">
                <label class="form-label">Tanggal</label>
                <input type="date" id="edit_note_date" class="swal2-input form-control" value="${currentDate}">
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Catatan</label>
                <textarea id="edit_note_content" class="swal2-textarea form-control" rows="4">${currentContent}</textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: "Update",
        cancelButtonText: "Batal",
        confirmButtonColor: "#ffc107",
        cancelButtonColor: "#6c757d",
        preConfirm: () => {
            const date = document.getElementById("edit_note_date").value;
            const content = document
                .getElementById("edit_note_content")
                .value.trim();

            if (!date) {
                Swal.showValidationMessage("Tanggal harus diisi!");
                return false;
            }
            if (!content) {
                Swal.showValidationMessage("Catatan harus diisi!");
                return false;
            }

            return { note_date: date, content: content };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            updateNote(id, result.value);
        }
    });
}

// Fungsi untuk update note
function updateNote(id, data) {
    fetch(`/admin/notes/${id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false,
                });
                loadNotes(); // Reload notes
            } else {
                throw new Error(data.message || "Gagal mengupdate catatan");
            }
        })
        .catch((error) => {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text:
                    error.message ||
                    "Terjadi kesalahan saat mengupdate catatan",
            });
        });
}

// Fungsi untuk delete note
function deleteNote(id) {
    Swal.fire({
        title: "Hapus Catatan?",
        text: "Catatan yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
    }).then((result) => {
        if (result.isConfirmed) {
            // Animasi removing sebelum delete
            const noteElement = document.querySelector(
                `[data-note-id="${id}"]`
            );
            if (noteElement) {
                noteElement.classList.add("removing");
            }

            // Delay untuk animasi
            setTimeout(() => {
                fetch(`/admin/notes/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        "X-Requested-With": "XMLHttpRequest",
                        Accept: "application/json",
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Terhapus!",
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false,
                            });
                            loadNotes(); // Reload notes
                        } else {
                            throw new Error(
                                data.message || "Gagal menghapus catatan"
                            );
                        }
                    })
                    .catch((error) => {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text:
                                error.message ||
                                "Terjadi kesalahan saat menghapus catatan",
                        });
                        // Remove animasi jika gagal
                        if (noteElement) {
                            noteElement.classList.remove("removing");
                        }
                    });
            }, 300); // Match dengan durasi animasi CSS
        }
    });
}

// Helper functions
function getTodayDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, "0");
    const day = String(today.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: "numeric", month: "long", day: "numeric" };
    return date.toLocaleDateString("id-ID", options);
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}
