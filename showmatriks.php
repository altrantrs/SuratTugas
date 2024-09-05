<?php
session_start();
include_once("db_connection.php");

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

$tahun = $_REQUEST['tahun'];
$bulan = $_REQUEST['bulan'];
$nip = $_REQUEST['nip'];

// Tentukan tanggal awal dan akhir untuk bulan yang dipilih
$tgl_awal = "$tahun/$bulan/01";
$tgl_akhir = "$tahun/$bulan/" . date("t", strtotime($tgl_awal));

$sql_peg = ($nip == "all") ? "SELECT * FROM pegawai ORDER BY nama" : "SELECT * FROM pegawai WHERE nip='$nip' ORDER BY nama";

$result = mysqli_query($conn, $sql_peg);
echo "<table border='1' cellspacing='0' cellpadding='5'><tr bgcolor='#FFFCCC'><td align=center colspan=40>Kegiatan di Bulan " . bulan($bulan) . " $tahun</td></tr>";

while ($pegawai = mysqli_fetch_array($result)) {
    echo tampilkanKegiatan($pegawai['nip'], $bulan, $tahun, $tgl_awal, $tgl_akhir, $pegawai['nama']);
}

function tampilkanKegiatan($nip, $bulan, $tahun, $tgl_awal, $tgl_akhir, $nama)
{
    global $conn;
    $waktu = strtotime($tgl_awal);
    $akhir_tgl = strtotime($tgl_akhir);
    echo "<tr><td>$nama</td>";

    while ($waktu <= $akhir_tgl) {
        $current_date = date("Y-m-d", $waktu);
        $hari = date("D", $waktu);
        $tanggal = date("d", $waktu);
        $hari_awal = translateDay($hari);
        $warna_hari = ($hari == "Sun" || $hari == "Sat") ? "pink" : "white";

        $sql = "SELECT * FROM activity_dates WHERE created_by='$nip' AND date='$current_date'";
        $activity = mysqli_fetch_array(mysqli_query($conn, $sql));

        if ($activity) {
            $activity_details = fetchActivityDetails($activity);
            $res_activity = fetchActivity($activity['activity_id']);
            tampilkanActivityButton($activity_details, $res_activity, $warna_hari, $hari_awal, $tanggal);
        } else {
            echo "<td align=center bgcolor='$warna_hari'>" . tampilkanKosongButton($nip, $tanggal, $bulan, $tahun) . "</td>";
        }

        $waktu = strtotime("+1 day", $waktu);
    }

    tampilkanRekap($nip, $bulan, $tahun);
    echo "</tr>";
}

function translateDay($day)
{
    $translations = [
        "Mon" => "S",
        "Tue" => "S",
        "Wed" => "R",
        "Thu" => "K",
        "Fri" => "J",
        "Sat" => "S",
        "Sun" => "M"
    ];
    return $translations[$day];
}

function fetchActivityDetails($activity)
{
    return [
        'nosurat' => $activity['nosurat'] ?? '',
        'tglsurat' => $activity['tglsurat'] ?? '',
        'jabatan' => $activity['jabatan'] ?? '',
        'id_kegiatan' => $activity['activity_id'] ?? '',
        'periode' => $activity['periode'] ?? '',
        'tempat' => $activity['tempat'] ?? '',
        'status' => $activity['status'] ?? '',
        'id' => $activity['id'] ?? ''
    ];
}

function fetchActivity($id_kegiatan)
{
    global $conn;
    $sql = "SELECT * FROM activities WHERE id='$id_kegiatan'";
    return mysqli_fetch_array(mysqli_query($conn, $sql));
}

function tampilkanActivityButton($activity, $res_activity, $warna_hari, $hari_awal, $tanggal)
{
    if ($_SESSION['level'] == "Administrator") {
        $button_class = ($activity['status'] == "1") ? 'tanggal3' : 'tanggal1';
        echo "<td align=center bgcolor='$warna_hari'>$hari_awal<br><button class='$button_class' title='{$res_activity['activity']}' onclick=\"editActivity('$activity[id]')\">$tanggal</button>";
        echo "<i class='fa-solid fa-check' onclick=\"laporanActivity('$activity')\"></i></td>";
    } else {
        $button_class = ($activity['status'] == "1") ? 'tanggal3' : 'tanggal1';
        echo "<td align=center bgcolor='$warna_hari'>$hari_awal<br><button class='$button_class' title='{$res_activity['activity']}' onclick=\"alert('{$res_activity['activity']}')\">$tanggal</button>";
        echo "<i class='fa-solid fa-check' onclick=\"laporanActivity('$activity')\"></i></td>";
    }
}

function tampilkanKosongButton($nip, $tanggal, $bulan, $tahun)
{
    if ($_SESSION['level'] == "Administrator") {
        return "<button onclick=\"isiActivity('$nip','$tanggal','$bulan','$tahun')\" class='tanggal0'>$tanggal</button>";
    } else {
        return "<button class='tanggal0'>$tanggal</button>";
    }
}

function tampilkanRekap($nip, $bulan, $tahun)
{
    global $conn;
    $rekap = mysqli_fetch_array(mysqli_query($conn, "SELECT count(activity_id) as jum FROM activity_dates WHERE created_by='$nip' and MONTH(date)='$bulan' and YEAR(date)='$tahun'"));
    $warna_rekap = ($rekap['jum'] > 15) ? "red" : ($rekap['jum'] > 0 ? "green" : "white");
    echo "<td bgcolor='$warna_rekap'><p style='color:white;'>{$rekap['jum']}</p></td>";
}

function bulan($bln)
{
    $bulan_array = [
        "01" => "Januari",
        "02" => "Februari",
        "03" => "Maret",
        "04" => "April",
        "05" => "Mei",
        "06" => "Juni",
        "07" => "Juli",
        "08" => "Agustus",
        "09" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "Desember",
    ];
    return $bulan_array[$bln];
}
?>
