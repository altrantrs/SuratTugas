<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];

    $date = $conn->real_escape_string($date);

    $sqlDelete = "DELETE FROM activity_dates WHERE date = '$date'";
    
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
