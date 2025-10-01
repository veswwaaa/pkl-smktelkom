//fungsi untuk membuka modal edit dudi
function editDudi(id, nama, telpon,alamat, pic){
    // Set action url form
    document.getElementById('editForm').action = '/admin/dudi/' + id;

    //isi data ke form modal
    document.getElementById('edit_nama_dudi').value = nama;
    document.getElementById('edit_nomor_telpon').value = telpon;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_person_in_charge').value = pic;

    // Tampilkan modal
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

//fungsi untuk submit form edit dudi

function submitEdit() {
    document.getElementById('editForm').submit();
}

// fungsi untuk konfirmasi hapus dudi
function deleteDudi(id, nama) {
    if(confirm('apakah anda yakin ingin mengapus dudi ' + nama + '?')) {

        //buat form delete dinamis
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/dudi/' + id;

        //Token csrf
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        //method DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);

        //submit form
        form.submit();
    }
}