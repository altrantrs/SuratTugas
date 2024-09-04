<?php
include 'db_connection.php';

session_start(); // Make sure session is started to access session variables

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $conn->real_escape_string($_POST['date']);
    $activity_id = $conn->real_escape_string($_POST['activity_id']);
    $created_by = $_SESSION['nip']; 

    // Prepare the SQL statement to delete the record based on the combination of date, activity_id, and created_by
    $sqlDelete = "DELETE FROM activity_dates WHERE date = '$date' AND activity_id = '$activity_id' AND created_by = '$created_by'";

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
