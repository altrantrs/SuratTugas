<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil id dari request POST
    $id = $_POST['id'];

    // Melindungi dari SQL injection
    $id = $conn->real_escape_string($id);

    // Menghapus data berdasarkan id
    $sqlDelete = "DELETE FROM activities WHERE id = '$id'";
    
    if ($conn->query($sqlDelete) === TRUE) {
        $response = ['status' => 'success', 'message' => 'Kegiatan berhasil dihapus'];
    } else {
        $response = ['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $conn->error];
    }

    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
