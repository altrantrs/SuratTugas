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

$fungsiOptions = ['Statistik Distribusi', 'Statistik Produksi', 'Statistik Sosial', 'Neraca Wilayah', 'IPDS', 'Subbag TU'];
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
    <?php
          include 'header.php';
        ?>
    <h2>Tambah Kegiatan</h2>
    <main>
        <section class="form-container">
            <form action="kegiatan_simpan.php" method="POST">

                <label for="fungsi">Fungsi</label>
                <select id="fungsi" name="fungsi" required>
                    <option value="">Pilih Fungsi</option>
                    <?php foreach ($fungsiOptions as $fungsi): ?>
                        <option value="<?= $fungsi ?>"><?= $fungsi ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="kode-kegiatan">Kode Kegiatan</label>
                <input type="text" id="kode-kegiatan" name="kode-kegiatan" required>

                <label for="kegiatan">Kegiatan</label>
                <input type="text" id="kegiatan" name="kegiatan" required>

                <label for="nomor-surat">Nomor Surat</label>
                <input type="text" id="nomor-surat" name="nomor-surat" required>
                
                <label for="tanggal-surat">Tanggal Surat</label>
                <input type="date" id="tanggal-surat" name="tanggal-surat" required>

                <label for="tujuan-kegiatan">Tujuan Kegiatan</label>
                <input type="text" id="tujuan-kegiatan" name="tujuan-kegiatan" required>

                <label for="jadwal">Jadwal</label>
                <input type="text" id="jadwal" name="jadwal" required>

                <button type="submit" class="btn btn-save">Simpan</button>
            </form>
        </section>
    </main>
    </div>
</body>

</html>