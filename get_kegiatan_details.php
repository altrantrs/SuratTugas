<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    $sql = "SELECT nomor_surat, tanggal_surat, tujuan_kegiatan, jadwal FROM activities WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $response = [
            'status' => 'success',
            'nomor_surat' => $data['nomor_surat'],
            'tanggal_surat' => $data['tanggal_surat'],
            'tujuan_kegiatan' => $data['tujuan_kegiatan'],
            'jadwal' => $data['jadwal']
        ];
    } else {
        $response = ['status' => 'error', 'message' => 'Kegiatan tidak ditemukan'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'ID tidak diberikan'];
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
