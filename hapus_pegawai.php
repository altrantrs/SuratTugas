<?php
include 'db_connection.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id) {
    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM pegawai WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response = array('status' => 'success', 'message' => 'Pegawai berhasil dihapus!');
    } else {
        $response = array('status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus pegawai.');
    }

    $stmt->close();
} else {
    $response = array('status' => 'error', 'message' => 'ID tidak ditemukan.');
}

$conn->close();

// Return response in JSON format
header('Content-Type: application/json');
echo json_encode($response);
?>


