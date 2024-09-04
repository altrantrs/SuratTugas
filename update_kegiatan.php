<?php
include 'db_connection.php';

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

$conn->close();
?>
