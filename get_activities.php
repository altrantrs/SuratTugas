<?php
session_start();
include_once("db_connection.php");

$month = $_GET['month'];
$year = $_GET['year'];
$pelaksana_name = isset($_GET['pelaksana']) ? $_GET['pelaksana'] : '';

if ($_SESSION['level'] == "Administrator") {
    if ($pelaksana_name === "all") {
        $query = "SELECT activity_dates.date, pegawai.nama 
                  FROM activity_dates
                  JOIN pegawai ON activity_dates.pelaksana = pegawai.nip
                  WHERE MONTH(activity_dates.date) = $month AND YEAR(activity_dates.date) = $year";
    } else {
        $query = "SELECT activity_dates.date, pegawai.nama 
                  FROM activity_dates
                  JOIN pegawai ON activity_dates.pelaksana = pegawai.nip
                  WHERE MONTH(activity_dates.date) = $month AND YEAR(activity_dates.date) = $year AND pegawai.nama='$pelaksana_name'";
    }
} else {
    $query = "SELECT activity_dates.date, pegawai.nama 
              FROM activity_dates
              JOIN pegawai ON activity_dates.pelaksana = pegawai.nip
              WHERE MONTH(activity_dates.date) = $month AND YEAR(activity_dates.date) = $year AND pegawai.nama='$pelaksana_name'";
}


$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$activities = [];

while ($row = mysqli_fetch_assoc($result)) {
    $activities[] = [
        'date' => $row['date'],
        'pelaksana' => $row['nama'] // Returning the name instead of NIP
    ];
}

header('Content-Type: application/json');
echo json_encode($activities);
?>
