<?php
include_once("db_connection.php");

$nip = $_GET['nip'];
$month = $_GET['month'];

// Sanitize and validate inputs
$nip = mysqli_real_escape_string($conn, $nip);
$month = (int)$month;

if ($nip == 'all') {
    $query = "SELECT date FROM activity_dates WHERE MONTH(date) = $month";
} else {
    $query = "SELECT date FROM activity_dates WHERE created_by = '$nip' AND MONTH(date) = $month";
}

$result = mysqli_query($conn, $query);
$calendarData = [];

while ($row = mysqli_fetch_assoc($result)) {
    $calendarData[] = $row['date']; // Only send date field
}

header('Content-Type: application/json');
echo json_encode($calendarData);

$conn->close();
?>
