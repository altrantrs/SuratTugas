<?php
session_start();
include_once("db_connection.php");

$month = $_GET['month'];
$year = $_GET['year'];
$pelaksana = isset($_GET['pelaksana']) ? $_GET['pelaksana'] : '';

if ($_SESSION['level'] == "Administrator") {
    if ($pelaksana === "all") {
        $query = "SELECT activity_dates.date, activity_dates.pelaksana 
                  FROM activity_dates
                  WHERE MONTH(activity_dates.date) = $month AND YEAR(activity_dates.date) = $year";
    } else {
        $query = "SELECT activity_dates.date, activity_dates.pelaksana 
                  FROM activity_dates
                  WHERE MONTH(activity_dates.date) = $month AND YEAR(activity_dates.date) = $year AND activity_dates.pelaksana='$pelaksana'";
    }
} else {
    $query = "SELECT activity_dates.date, activity_dates.pelaksana 
              FROM activity_dates 
              WHERE MONTH(activity_dates.date) = $month AND YEAR(activity_dates.date) = $year AND pelaksana='$pelaksana'";
}

$result = mysqli_query($conn, $query);

if (!$result) {
    // Handle query error
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$activities = [];

while ($row = mysqli_fetch_assoc($result)) {
    $activities[] = [
        'date' => $row['date'],
        'pelaksana' => $row['pelaksana'] // Menyertakan kolom pelaksana
    ];
}

header('Content-Type: application/json');
echo json_encode($activities);
?>
