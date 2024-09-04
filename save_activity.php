<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fungsi = $_POST['fungsi'];
    $kodeKegiatan = $_POST['kode-kegiatan'];
    $kegiatan = $_POST['kegiatan'];
    $nomorSurat = $_POST['nomor-surat'];
    $tanggalSurat = $_POST['tanggal-surat'];
    $tujuanKegiatan = $_POST['tujuan-kegiatan'];
    $jadwal = $_POST['jadwal'];

    // Prepare the SQL query to insert data into the activities table
    $sql = "INSERT INTO activities (fungsi, kode_kegiatan, activity, nomor_surat, tanggal_surat, tujuan_kegiatan, jadwal)
            VALUES ('$fungsi', '$kodeKegiatan', '$kegiatan', '$nomorSurat', '$tanggalSurat', '$tujuanKegiatan', '$jadwal')";

    if ($conn->query($sql) === TRUE) {
        $response = ['status' => 'success', 'message' => 'Kegiatan berhasil disimpan'];
    } else {
        $response = ['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $conn->error];
    }

    $conn->close();

    // Redirect to a specific page (e.g., kegiatan.php) after saving
    header('Location: kegiatan.php');
    exit;
}
?>
