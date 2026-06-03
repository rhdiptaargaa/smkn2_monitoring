// Logika Penanganan Pop-up Kehadiran di Dashboard
function bukaPopupIzin(id, tipe, nama, statusSekarang) {
    document.getElementById('modalIzin').style.display = 'flex';
    document.getElementById('targetId').value = id;
    document.getElementById('targetTipe').value = tipe;
    document.getElementById('modalText').innerText = "Apakah " + nama + " saat ini sudah kembali berada di lingkungan sekolah?";
}

function tutupPopup() {
    document.getElementById('modalIzin').style.display = 'none';
}

function simpanStatus(statusBaru) {
    const id = document.getElementById('targetId').value;
    const tipe = document.getElementById('targetTipe').value;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('tipe', tipe);
    formData.append('status', statusBaru);

    fetch('api/update_status.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            location.reload();
        } else {
            alert(data.message || "Gagal memperbarui status.");
        }
    });
}

// Logika Autocomplete Pencarian Data Siswa Otomatis (NIS/Nama)
function inisialisasiAutofill() {
    const inputCari = document.getElementById('cari_siswa');
    if(!inputCari) return;

    // Buat wadah box pencarian di bawah input
    const suggestionBox = document.createElement('div');
    suggestionBox.setAttribute('class', 'autocomplete-suggestions');
    inputCari.parentNode.appendChild(suggestionBox);

    inputCari.addEventListener('input', function() {
        let keyword = this.value;
        if(keyword.length < 2) {
            suggestionBox.innerHTML = '';
            return;
        }

        fetch('api/get_siswa.php?q=' + encodeURIComponent(keyword))
        .then(res => res.json())
        .then(data => {
            suggestionBox.innerHTML = '';
            data.forEach(siswa => {
                let div = document.createElement('div');
                div.innerHTML = `<strong>${siswa.nis}</strong> - ${siswa.nama} (${siswa.kelas})`;
                div.addEventListener('click', function() {
                    inputCari.value = siswa.nis;
                    document.getElementById('txt_nama').value = siswa.nama;
                    document.getElementById('txt_kelas').value = siswa.kelas;
                    document.getElementById('select_jurusan').value = siswa.jurusan;
                    suggestionBox.innerHTML = '';
                });
                suggestionBox.appendChild(div);
            });
        });
    });
}

document.addEventListener("DOMContentLoaded", inisialisasiAutofill);