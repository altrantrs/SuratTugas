<?php

session_start();
include_once("db_connection.php");

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Pastikan 'tahun' dan 'bulan' ada dan valid
$tahun = isset($_REQUEST['tahun']) ? $_REQUEST['tahun'] : date('Y');
$bulan = isset($_REQUEST['bulan']) ? $_REQUEST['bulan'] : date('m');
$nip = isset($_REQUEST['nip']) ? $_REQUEST['nip'] : '';

// Validasi input tahun dan bulan
if (!preg_match('/^\d{4}$/', $tahun) || !preg_match('/^(0[1-9]|1[0-2])$/', $bulan)) {
    die("Tahun atau bulan tidak valid.");
}

// Tentukan tanggal awal dan akhir bulan
$tgl_awal = "$tahun/$bulan/01";
$awal_tgl = strtotime($tgl_awal);
$tgl_akhir = "$tahun/$bulan/" . date("t", $awal_tgl);

// Query pegawai berdasarkan NIP
$sql_peg = ($nip == "all") ? "SELECT * FROM pegawai ORDER BY nama" : "SELECT * FROM pegawai WHERE nip='$nip' ORDER BY nama";
$result = mysqli_query($conn, $sql_peg);

// Tampilkan tabel kegiatan
echo "<table border='1' cellspacing='0' cellpadding='5'>
<tr bgcolor='#FFFCCC'>
<td align='center' colspan='40'>Kegiatan di Bulan " . bulan($bulan) . " $tahun</td></tr>";

// Loop setiap pegawai dan tampilkan hari kerja
while ($pegawai = mysqli_fetch_array($result)) {
    $nama = isset($pegawai['nama']) ? $pegawai['nama'] : '';
    echo tampilkanHariKerja($pegawai['nip'], $bulan, $tahun, $tgl_awal, $tgl_akhir, $nama);
}

// Fungsi menampilkan hari kerja
function tampilkanHariKerja($nip, $bulan, $tahun, $tgl_awal, $tgl_akhir, $nama) {
    global $conn;
    $awal_tgl = strtotime($tgl_awal);
    $akhir_tgl = strtotime($tgl_akhir);

    echo "<tr><td>$nama</td>";

    // Loop setiap tanggal dalam bulan tersebut
    for ($waktu = $awal_tgl; $waktu <= $akhir_tgl; $waktu = strtotime("+1 day", $waktu)) {
        $current_date = date("Y-m-d", $waktu);
        $hari_temp = date("d", $waktu);
        $bln_temp = convertDayToInitial(date("D", $waktu));
        $bg_color = (date("D", $waktu) == "Sat" || date("D", $waktu) == "Sun") ? "pink" : "white";

        $sql_activity = "SELECT * FROM activity_dates WHERE created_by='$nip' AND date='$current_date'";
        $result_activity = mysqli_query($conn, $sql_activity);

        // Jika ada kegiatan di hari itu
        if ($result_activity && mysqli_num_rows($result_activity) > 0) {
            $activity = mysqli_fetch_array($result_activity, MYSQLI_ASSOC);
            $activity_info = getActivityInfo(isset($activity['activity_id']) ? $activity['activity_id'] : '');

            echo getActivityCell($activity_info, $bln_temp, $hari_temp, $bg_color, $nip);
        } else {
            echo getEmptyCell($bg_color, $bln_temp, $hari_temp, $nip);
        }
    }

    // Rekap jumlah kegiatan pegawai per bulan
    $rekap = mysqli_query($conn, "SELECT COUNT(activity_id) as jum FROM activity_dates WHERE created_by='$nip' AND MONTH(date)='$bulan' AND YEAR(date)='$tahun'");
    $rekap_data = mysqli_fetch_array($rekap, MYSQLI_ASSOC);
    $rekap_color = ($rekap_data['jum'] > 15) ? "red" : (($rekap_data['jum'] > 0) ? "green" : "white");

    echo "<td bgcolor='$rekap_color'><p style='color:white;'>" . (isset($rekap_data['jum']) ? $rekap_data['jum'] : '0') . "</p></td></tr>";
}

// Fungsi mengkonversi hari menjadi inisial
function convertDayToInitial($day) {
    $days = ["Mon" => "S", "Tue" => "S", "Wed" => "R", "Thu" => "K", "Fri" => "J", "Sat" => "S", "Sun" => "M"];
    return isset($days[$day]) ? $days[$day] : '';
}

// Fungsi menampilkan cell kegiatan
function getActivityCell($activity, $bln_temp, $hari_temp, $bg_color, $nip) {
    $nama = isset($activity['nama']) ? $activity['nama'] : '';
    $status = isset($activity['status']) ? $activity['status'] : '';
    $kode_kegiatan = isset($activity['kode_kegiatan']) ? $activity['kode_kegiatan'] : '';
    $activity_id = isset($activity['activity_id']) ? $activity['activity_id'] : '';
    $nosurat = isset($activity['nosurat']) ? $activity['nosurat'] : '';
    $tglsurat = isset($activity['tglsurat']) ? $activity['tglsurat'] : '';
    $tempat = isset($activity['tempat']) ? $activity['tempat'] : '';
    $periode = isset($activity['periode']) ? $activity['periode'] : '';
    
    $date = date('Y-m-d', strtotime("$bln_temp/$hari_temp"));

    $activity_button = "<button onclick=\"window.location.href='perjalanan_tambah.php?tanggal=$date';\" class='tanggal{$status}' title='{$kode_kegiatan} - {$nama}'>{$hari_temp}</button>";
    $delete_icon = "<i class='fas fa-trash-alt' title='Hapus' onclick=\"window.location.href='perjalanan_hapus.php?date=$date&activity_id=$activity_id&created_by=$nip';\"></i>";
    $print_icon = "<i class='fa-solid fa-check' title='Print' onclick=\"window.location.href='tampil_kegiatan.php?date=$date';\"></i>";
    
    return "<td align='center' bgcolor='$bg_color'>$bln_temp<br>$activity_button $delete_icon $print_icon</td>";
}


// Fungsi menampilkan cell kosong
function getEmptyCell($bg_color, $bln_temp, $hari_temp, $nip) {
    if ($_SESSION['level'] == "Administrator") {
        return "<td align='center' bgcolor='$bg_color'>$bln_temp<br><button onclick=\"document.getElementById('id01').style.display='block';isi('$nip','$hari_temp');\" class='tanggal0'>$hari_temp</button></td>";
    }
    return "<td align='center' bgcolor='$bg_color'>$bln_temp<br><button class='tanggal0'>$hari_temp</button></td>";
}

// Fungsi mengkonversi bulan
function bulan($bln) {
    $bulan_array = ["01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember"];
    return isset($bulan_array[$bln]) ? $bulan_array[$bln] : '';
}

// Fungsi untuk mendapatkan info kegiatan
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
