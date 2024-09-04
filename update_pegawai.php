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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'];
    $nip_lama = $_POST['nip_lama'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $golongan = $_POST['golongan'];
    $pangkat = $_POST['pangkat'];
    $kendaraan_dinas = $_POST['kendaraan_dinas'];

    $sql = "UPDATE pegawai 
            SET nip = '$nip', 
                nip_lama = '$nip_lama', 
                nama = '$nama', 
                jabatan = '$jabatan', 
                golongan = '$golongan', 
                pangkat = '$pangkat', 
                kendaraan_dinas = '$kendaraan_dinas'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pegawai berhasil diperbarui!'); window.location.href = 'pegawai.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    $sql = "SELECT * FROM pegawai WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $pegawai = $result->fetch_assoc();
    } else {
        echo "Pegawai tidak ditemukan!";
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pegawai</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <div class="container">
        <div class="container">
            <main>
                <section class="allocation">
                    <div class="form-container">
                        <form id="activity-form" action="update_pegawai.php?id=<?= $pegawai['id']; ?>" method="POST">
                            <label for="nip">NIP Baru</label>
                            <input type="text" id="nip" name="nip" value="<?= htmlspecialchars($pegawai['nip']); ?>" required>

                            <label for="nip_lama">NIP Lama</label>
                            <input type="text" id="nip_lama" name="nip_lama" value="<?= htmlspecialchars($pegawai['nip_lama']); ?>" required>

                            <label for="nama">Nama</label>
                            <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($pegawai['nama']); ?>" required>

                            <label for="jabatan">Jabatan</label>
                            <input type="text" id="jabatan" name="jabatan" value="<?= htmlspecialchars($pegawai['jabatan']); ?>" required>

                            <label for="golongan">Golongan</label>
                            <input type="text" id="golongan" name="golongan" value="<?= htmlspecialchars($pegawai['golongan']); ?>" required>

                            <label for="pangkat">Pangkat</label>
                            <input type="text" id="pangkat" name="pangkat" value="<?= htmlspecialchars($pegawai['pangkat']); ?>" required>

                            <label for="kendaraan_dinas">Kendaraan Dinas</label>
                            <input type="text" id="kendaraan_dinas" name="kendaraan_dinas" value="<?= htmlspecialchars($pegawai['kendaraan_dinas']); ?>" required>

                            <div class="buttons">
                                <button type="submit" class="btn btn-save">Simpan</button>
                                <button a href="pegawai.php" class="btn btn-print">Batal</a>
                            </div>
                        </form>
                    </div>
</body>

</html>