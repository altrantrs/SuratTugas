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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'];
    $nip_lama = $_POST['nip_lama'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $golongan = $_POST['golongan'];
    $pangkat = $_POST['pangkat'];
    $kendaraan_dinas = $_POST['kendaraan_dinas'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "INSERT INTO pegawai (nip, nip_lama, nama, jabatan, golongan, pangkat, kendaraan_dinas, username, password) 
            VALUES ('$nip', '$nip_lama', '$nama', '$jabatan', '$golongan', '$pangkat', '$kendaraan_dinas', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pegawai berhasil ditambahkan!'); window.location.href = 'pegawai.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pegawai</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    include 'header.php';
    ?>
    <div class="container">
        <main>
        <section class="allocation">
        <div class="form-container">            
        <form id="activity-form" action="pegawai_tambah.php" method="POST">
            <label for="nip">NIP Baru</label>
            <input type="text" id="nip" name="nip" required>

            <label for="nip_lama">NIP Lama</label>
            <input type="text" id="nip_lama" name="nip_lama" required>

            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" required>

            <label for="jabatan">Jabatan</label>
            <input type="text" id="jabatan" name="jabatan" required>

            <label for="golongan">Golongan</label>
            <input type="text" id="golongan" name="golongan" required>

            <label for="pangkat">Pangkat</label>
            <input type="text" id="pangkat" name="pangkat" required>

            <label for="kendaraan_dinas">Kendaraan Dinas</label>
            <input type="text" id="kendaraan_dinas" name="kendaraan_dinas" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="text" id="password" name="password" required>

            <button type="submit" class="btn btn-save">Simpan</button>
        </form>
    </div>
</body>
</html>
