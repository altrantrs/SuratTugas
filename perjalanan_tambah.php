<?php
session_start();
include_once("db_connection.php");

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$nip = $_SESSION["nip"];
$userLevel = $_SESSION['level'];

// Fetch activities for dropdown
$sql = "SELECT id, activity FROM activities";
$kegiatanResult = $conn->query($sql);

$kegiatanList = [];
if ($kegiatanResult->num_rows > 0) {
    while ($row = $kegiatanResult->fetch_assoc()) {
        $kegiatanList[] = $row;
    }
}

// Fetch employees based on user level
if ($userLevel == "Administrator") {
    $sql = "SELECT nip, nama FROM pegawai ORDER BY nama";
} else {
    $sql = "SELECT nip, nama FROM pegawai WHERE nip='$nip'";
}
$pegawaiResult = $conn->query($sql);

$pegawaiList = [];
if ($pegawaiResult->num_rows > 0) {
    while ($row = $pegawaiResult->fetch_assoc()) {
        $pegawaiList[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kegiatan</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <main>
            <section class="allocation">
                <div class="form-container">
                    <h2>Alokasi Kegiatan</h2>
                    <form id="activity-form">
                        <label for="tanggal-kegiatan">Tanggal Kegiatan</label>
                        <input type="text" id="tanggal-kegiatan" name="tanggal-kegiatan" placeholder="Value" readonly>

                        <label for="kegiatan">Kegiatan</label>
                        <select id="kegiatan" name="kegiatan">
                            <option value="">Pilih Kegiatan</option>
                            <?php foreach ($kegiatanList as $kegiatan) : ?>
                                <option value="<?= $kegiatan['id']; ?>"><?= $kegiatan['activity']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="nomor-surat">Nomor Surat</label>
                        <input type="text" id="nomor-surat" name="nomor-surat" placeholder="Value">

                        <label for="tanggal-surat">Tanggal Surat</label>
                        <input type="text" id="tanggal-surat" name="tanggal-surat" placeholder="Value">

                        <label for="tujuan-kegiatan">Tujuan Kegiatan</label>
                        <input type="text" id="tujuan-kegiatan" name="tujuan-kegiatan" placeholder="Value">

                        <label for="jadwal">Jadwal</label>
                        <input type="text" id="jadwal" name="jadwal" placeholder="Value">

                        <label for="pelaksana">Pelaksana</label>
                        <select id="pelaksana" name="pelaksana">
                            <option value="">Pilih Pelaksana</option>
                            <?php foreach ($pegawaiList as $pegawai) : ?>
                                <option value="<?= $pegawai['nama']; ?>"><?= $pegawai['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <div class="buttons">
                            <button type="submit" id="save-btn" class="btn btn-save">Simpan</button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('activity-form');
            const selectedDate = localStorage.getItem('selectedDate');

            if (selectedDate) {
                document.getElementById('tanggal-kegiatan').value = selectedDate;
            }

            const kegiatanSelect = document.getElementById('kegiatan');

            kegiatanSelect.addEventListener('change', function() {
                const kegiatanId = this.value;

                if (kegiatanId) {
                    fetch('get_kegiatan_details.php?id=' + encodeURIComponent(kegiatanId))
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                document.getElementById('nomor-surat').value = data.nomor_surat || '';
                                document.getElementById('tanggal-surat').value = data.tanggal_surat || '';
                                document.getElementById('tujuan-kegiatan').value = data.tujuan_kegiatan || '';
                                document.getElementById('jadwal').value = data.jadwal || '';
                            } else {
                                alert("Error: " + data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    document.getElementById('nomor-surat').value = '';
                    document.getElementById('tanggal-surat').value = '';
                    document.getElementById('tujuan-kegiatan').value = '';
                    document.getElementById('jadwal').value = '';
                }
            });

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                const formData = new FormData(form);

                fetch('perjalanan_simpan.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            window.location.href = 'tampil_kegiatan.php?date=' + encodeURIComponent(selectedDate);
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>
