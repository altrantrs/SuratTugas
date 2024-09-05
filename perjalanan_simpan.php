<?php
include 'db_connection.php';

session_start(); // Pastikan session dimulai untuk mengakses session variables

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_kegiatan = $conn->real_escape_string($_POST['tanggal-kegiatan']);
    $kegiatan_id = $conn->real_escape_string($_POST['kegiatan']);
    $nomor_surat = $conn->real_escape_string($_POST['nomor-surat']);
    $tanggal_surat = $conn->real_escape_string($_POST['tanggal-surat']);
    $tujuan_kegiatan = $conn->real_escape_string($_POST['tujuan-kegiatan']);
    $jadwal = $conn->real_escape_string($_POST['jadwal']);
    $created_by = $_SESSION['nip']; 
    $pelaksana = $_POST['pelaksana']; // Array of pelaksana NIPs

    // Cek apakah kombinasi kegiatan_id, tanggal_kegiatan, dan created_by sudah ada
    $checkSql = "SELECT id FROM activity_dates WHERE activity_id = '$kegiatan_id' AND date = '$tanggal_kegiatan' AND created_by = '$created_by'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Jika kombinasi sudah ada, update data yang ada
        $row = $checkResult->fetch_assoc();
        $activity_date_id = $row['id'];
        $sql = "UPDATE activity_dates 
                SET nomor_surat = '$nomor_surat', tanggal_surat = '$tanggal_surat', tujuan_kegiatan = '$tujuan_kegiatan', jadwal = '$jadwal' 
                WHERE id = '$activity_date_id'";
    } else {
        $sql = "INSERT INTO activity_dates (activity_id, date, nomor_surat, tanggal_surat, tujuan_kegiatan, jadwal, created_by)
                VALUES ('$kegiatan_id', '$tanggal_kegiatan', '$nomor_surat', '$tanggal_surat', '$tujuan_kegiatan', '$jadwal', '$created_by')";
        if ($conn->query($sql) === TRUE) {
            $activity_date_id = $conn->insert_id;
            foreach ($pelaksana as $nip_pelaksana) {
                $conn->query("INSERT INTO activity_pelaksana (activity_date_id, nip) VALUES ('$activity_date_id', '$nip_pelaksana')");
            }
        }
    }
}

    if ($conn->query($sql) === TRUE) {
        $response = ['status' => 'success', 'message' => 'Kegiatan berhasil disimpan'];
    } else {
        $response = ['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $conn->error];
    }

    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
}
