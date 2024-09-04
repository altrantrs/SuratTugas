<?php
include 'db_connection.php';
session_start();

// Set header for JSON
header('Content-Type: application/json');

$nip = $_SESSION["nip"];
$level = $_SESSION["level"];

if ($level == "Administrator") {
    // If the user is an Administrator, fetch all dates
    $sql = "SELECT date FROM activity_dates";
} else {
    // Otherwise, fetch dates specific to the user
    $sql = "SELECT date FROM activity_dates WHERE created_by = '$nip'";
}

$result = $conn->query($sql);

$dates_with_activities = [];

if ($result) { // Ensure the query is successful
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $dates_with_activities[] = $row['date'];
        }
    }
} else {
    // If the query fails, send an error message in JSON format
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    exit;
}

echo json_encode($dates_with_activities);

$conn->close();
?>
