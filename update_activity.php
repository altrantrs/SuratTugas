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

$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $fungsi = $_POST['fungsi'];
    $kode_kegiatan = $_POST['kode_kegiatan'];
    $activity = $_POST['activity'];
    $nomor_surat = $_POST['nomor_surat'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $tujuan_kegiatan = $_POST['tujuan_kegiatan'];
    $jadwal = $_POST['jadwal'];

    $sql = "UPDATE activities 
        SET fungsi = '$fungsi', 
            kode_kegiatan = '$kode_kegiatan', 
            activity = '$activity', 
            nomor_surat = '$nomor_surat', 
            tanggal_surat = '$tanggal_surat', 
            tujuan_kegiatan = '$tujuan_kegiatan', 
            jadwal = '$jadwal'
        WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Kegiatan berhasil diperbarui!'); window.location.href = 'kegiatan.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    $sql = "SELECT * FROM activities WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $activity = $result->fetch_assoc();
    } else {
        echo "Kegiatan tidak ditemukan!";
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
    <title>Form Kegiatan</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <section class="allocation">
        <div class="form-container">
            <div class="container">
                <form id="activity-form" action="update_activity.php?id<?= $activity['id']; ?>" method="POST">
                    <input type="hidden" name="id" value="<?= $activity['id']; ?>">

                    <label for="fungsi">Fungsi</label>
                    <input type="text" id="fungsi" name="fungsi" value="<?= htmlspecialchars($activity['fungsi']); ?>">

                    <label for="kode_kegiatan">Kode Kegiatan</label>
                    <input type="text" id="kode_kegiatan" name="kode_kegiatan" value="<?= htmlspecialchars($activity['kode_kegiatan']); ?>">

                    <label for="activity">Kegiatan</label>
                    <input type="text" id="activity" name="activity" value="<?= htmlspecialchars($activity['activity']); ?>">

                    <label for="nomor_surat">Nomor Surat</label>
                    <input type="text" id="nomor_surat" name="nomor_surat" value="<?= htmlspecialchars($activity['nomor_surat']); ?>">

                    <label for="tanggal_surat">Tanggal Surat</label>
                    <input type="date" id="tanggal_surat" name="tanggal_surat" value="<?= htmlspecialchars($activity['tanggal_surat']); ?>">

                    <label for="tujuan_kegiatan">Tujuan Kegiatan</label>
                    <input type="text" id="tujuan_kegiatan" name="tujuan_kegiatan" value="<?= htmlspecialchars($activity['tujuan_kegiatan']); ?>">

                    <label for="jadwal">Jadwal</label>
                    <input type="text" id="jadwal" name="jadwal" value="<?= htmlspecialchars($activity['jadwal']); ?>">

                    <div class="buttons">
                        <button type="submit" class="btn btn-save">Simpan</button>
                        <button a href="kegiatan.php" class="btn btn-print">Batal</a>
                    </div>
                </form>
            </div>
</body>

</html>