<?php
session_start();
include_once("db_connection.php");

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Validate 'tahun' and 'bulan'
$tahun = isset($_REQUEST['tahun']) ? $_REQUEST['tahun'] : date('Y');
$bulan = isset($_REQUEST['bulan']) ? $_REQUEST['bulan'] : date('m');
$nip = isset($_REQUEST['nip']) ? $_REQUEST['nip'] : '';

if (!preg_match('/^\d{4}$/', $tahun) || !preg_match('/^(0[1-9]|1[0-2])$/', $bulan)) {
    die("Tahun atau bulan tidak valid.");
}

// Set start and end dates for the month
$tgl_awal = "$tahun/$bulan/01";
$awal_tgl = strtotime($tgl_awal);
$tgl_akhir = "$tahun/$bulan/" . date("t", $awal_tgl);

// Query pegawai
$sql_peg = ($nip == "all") ? "SELECT * FROM pegawai ORDER BY nama" : "SELECT * FROM pegawai WHERE nip='$nip' ORDER BY nama";
$result = mysqli_query($conn, $sql_peg);

// Display the table
echo "<table border='1' cellspacing='0' cellpadding='5'>
<tr bgcolor='#FFFCCC'>
<td align='center' colspan='40'>Kegiatan di Bulan " . bulan($bulan) . " $tahun</td></tr>";

// Loop through employees
while ($pegawai = mysqli_fetch_array($result)) {
    $nama = isset($pegawai['nama']) ? $pegawai['nama'] : '';
    echo tampilkanHariKerja($pegawai['nip'], $bulan, $tahun, $tgl_awal, $tgl_akhir, $nama);
}

// Display the closing tag for the table
echo "</table>";

// Function to display working days
function tampilkanHariKerja($nip, $bulan, $tahun, $tgl_awal, $tgl_akhir, $nama) {
    global $conn;
    $awal_tgl = strtotime($tgl_awal);
    $akhir_tgl = strtotime($tgl_akhir);

    echo "<tr><td>$nama</td>";

    // Loop through each date in the month
    for ($waktu = $awal_tgl; $waktu <= $akhir_tgl; $waktu = strtotime("+1 day", $waktu)) {
        $current_date = date("Y-m-d", $waktu);
        $hari_temp = date("d", $waktu);
        $bln_temp = convertDayToInitial(date("D", $waktu));
        $bg_color = (date("D", $waktu) == "Sat" || date("D", $waktu) == "Sun") ? "pink" : "white";

        $sql_activity = "SELECT * FROM activity_dates WHERE created_by='$nip' AND date='$current_date'";
        $result_activity = mysqli_query($conn, $sql_activity);

        if ($result_activity && mysqli_num_rows($result_activity) > 0) {
            echo getActivityCell($hari_temp, $bln_temp, $current_date, $bg_color);
        } else {
            echo getEmptyCell($hari_temp, $bln_temp, $current_date, $bg_color);
        }
    }

    // Summary of activities
    $rekap = mysqli_query($conn, "SELECT COUNT(activity_id) as jum FROM activity_dates WHERE created_by='$nip' AND MONTH(date)='$bulan' AND YEAR(date)='$tahun'");
    $rekap_data = mysqli_fetch_array($rekap, MYSQLI_ASSOC);
    $rekap_color = ($rekap_data['jum'] > 15) ? "red" : (($rekap_data['jum'] > 0) ? "green" : "white");

    echo "<td bgcolor='$rekap_color'><p style='color:white;'>" . (isset($rekap_data['jum']) ? $rekap_data['jum'] : '0') . "</p></td></tr>";
}

// Convert day to initial
function convertDayToInitial($day) {
    $days = ["Mon" => "S", "Tue" => "S", "Wed" => "R", "Thu" => "K", "Fri" => "J", "Sat" => "S", "Sun" => "M"];
    return isset($days[$day]) ? $days[$day] : '';
}

// Display activity cell
function getActivityCell($hari_temp, $bln_temp, $current_date, $bg_color) {
    $activity_button = "<button onclick=\"window.location.href='perjalanan_tambah.php?tanggal=$current_date';\">$hari_temp</button>";
    $show_icon = "<i class='fa-solid fa-check' title='Print' onclick=\"window.location.href='tampil_kegiatan.php?date=$current_date';\"></i>";

    return "<td align='center' bgcolor='$bg_color'>$bln_temp<br>$activity_button<br>$show_icon</td>";
}

// Display empty cell
function getEmptyCell($hari_temp, $bln_temp, $current_date, $bg_color) {
    $activity_button = "<button onclick=\"window.location.href='perjalanan_tambah.php?tanggal=$current_date';\">$hari_temp</button>";
    return "<td align='center' bgcolor='$bg_color'>$bln_temp<br>$activity_button</td>";
}

// Get month name
function bulan($bln) {
    $bulan_array = ["01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember"];
    return isset($bulan_array[$bln]) ? $bulan_array[$bln] : '';
}

// Get activity info
function getActivityInfo($activity_id) {
    global $conn;
    $sql = "SELECT * FROM activities WHERE id='$activity_id'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    return mysqli_fetch_array($result, MYSQLI_ASSOC);
}
?>

