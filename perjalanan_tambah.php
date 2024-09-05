<?php
session_start();
include_once("db_connection.php");
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$result = null;
$nip = $_SESSION["nip"];
if ($_SESSION['level'] == "Administrator") {
    $result = mysqli_query($conn, "SELECT * FROM pegawai ORDER BY nama");
} else {
    $result = mysqli_query($conn, "SELECT * FROM pegawai WHERE nip='$nip'");
}

// Fetch all employees for the "pelaksana" dropdown
$pegawaiQuery = "SELECT nip, nama FROM pegawai ORDER BY nama";
$pegawaiResult = $conn->query($pegawaiQuery);

// Fetch activities for dropdown
$sql = "SELECT id, activity FROM activities";
$activityResult = $conn->query($sql);

$kegiatanList = [];
if ($activityResult->num_rows > 0) {
    while ($row = $activityResult->fetch_assoc()) {
        $kegiatanList[] = $row;
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

                        <label for="pelaksana">Pelaksana</label>
                        <select id="pelaksana" name="pelaksana[]" multiple>
                            <?php while ($pegawai = $pegawaiResult->fetch_assoc()) : ?>
                                <option value="<?= $pegawai['nip']; ?>"><?= $pegawai['nama']; ?></option>
                            <?php endwhile; ?>
                        </select>

                        <label for="nomor-surat">Nomor Surat</label>
                        <input type="text" id="nomor-surat" name="nomor-surat" placeholder="Value">

                        <label for="tanggal-surat">Tanggal Surat</label>
                        <input type="text" id="tanggal-surat" name="tanggal-surat" placeholder="Value">

                        <label for="tujuan-kegiatan">Tujuan Kegiatan</label>
                        <input type="text" id="tujuan-kegiatan" name="tujuan-kegiatan" placeholder="Value">

                        <label for="jadwal">Jadwal</label>
                        <input type="text" id="jadwal" name="jadwal" placeholder="Value">

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
