
<?php
session_start();
include_once("db_connection.php");

$month = $_GET['month'];
$year = $_GET['year'];
$nip = $_SESSION["nip"];

if ($_SESSION['level'] == "Administrator") {
    $query = "SELECT activity_dates.date, pegawai.nama 
              FROM activity_dates
              JOIN pegawai ON activity_dates.created_by = pegawai.nip
              WHERE MONTH(activity_dates.date) = $month AND YEAR(activity_dates.date) = $year";
} else {
    $query = "SELECT activity_dates.date 
              FROM activity_dates 
              WHERE MONTH(activity_dates.date) = $month AND YEAR(activity_dates.date) = $year AND created_by='$nip'";
}

$result = mysqli_query($conn, $query);
$activities = [];

while ($row = mysqli_fetch_assoc($result)) {
    $activities[] = [
        'date' => $row['date'],
        'nama' => $row['nama'] ?? null // Tambahkan nama pegawai untuk administrator
    ];
}

echo json_encode($activities);
?>
