<?php

session_start();
include_once("db_connection.php");

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
	header("Location: index.php");
	exit;
}

$tahun = $_REQUEST['tahun'];
$bulan = $_REQUEST['bulan'];
$nip = $_REQUEST['nip'];

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
	$nama = $pegawai['nama'];
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
			$activity = mysqli_fetch_array($result_activity);
			$activity_info = getActivityInfo($activity);

			echo getActivityCell($activity_info, $bln_temp, $hari_temp, $bg_color, $nip);
		} else {
			echo getEmptyCell($bg_color, $bln_temp, $hari_temp, $nip);
		}
	}

	// Rekap jumlah kegiatan pegawai per bulan
	$rekap = mysqli_query($conn, "SELECT COUNT(activity_id) as jum FROM activity_dates WHERE created_by='$nip' AND MONTH(date)='$bulan' AND YEAR(date)='$tahun'");
	$rekap_data = mysqli_fetch_array($rekap);
	$rekap_color = ($rekap_data['jum'] > 15) ? "red" : (($rekap_data['jum'] > 0) ? "green" : "white");

	echo "<td bgcolor='$rekap_color'><p style='color:white;'>" . $rekap_data['jum'] . "</p></td></tr>";
}

// Fungsi mengkonversi hari menjadi inisial
function convertDayToInitial($day) {
	$days = ["Mon" => "S", "Tue" => "S", "Wed" => "R", "Thu" => "K", "Fri" => "J", "Sat" => "S", "Sun" => "M"];
	return $days[$day];
}

// Fungsi menampilkan cell kegiatan
$nama = isset($pegawai['nama']) ? $pegawai['nama'] : '';
$status = isset($activity['status']) ? $activity['status'] : '';
$tahun = isset($activity['tahun']) ? $activity['tahun'] : '';
$bulan = isset($activity['bulan']) ? $activity['bulan'] : '';
$activity_id = isset($activity['activity_id']) ? $activity['activity_id'] : '';
$nosurat = isset($activity['nosurat']) ? $activity['nosurat'] : '';
$tglsurat = isset($activity['tglsurat']) ? $activity['tglsurat'] : '';
$tempat = isset($activity['tempat']) ? $activity['tempat'] : '';
$periode = isset($activity['periode']) ? $activity['periode'] : '';

if ($result_activity && mysqli_num_rows($result_activity) > 0) {
    $activity = mysqli_fetch_array($result_activity);
    $activity_info = getActivityInfo($activity);
    echo getActivityCell($activity_info, $bln_temp, $hari_temp, $bg_color, $nip);
} else {
    echo getEmptyCell($bg_color, $bln_temp, $hari_temp, $nip);
}

var_dump($pegawai);  // To check if pegawai data is correct
var_dump($activity); // To check if activity data is correct

function getActivityCell($activity, $bln_temp, $hari_temp, $bg_color, $nip) {
	$activity_button = "<button onclick=\"alert('{$activity['nama']}');\" class='tanggal{$activity['status']}' title='{$activity['kode_kegiatan']} - {$activity['nama']}'>{$hari_temp}</button>";
	$delete_icon = "<i class='fas fa-trash-alt' title='Hapus' onclick=\"del('{$activity['id']}');\"></i>";
	$print_icon = "<i class='fa-solid fa-check' title='Print' onclick=\"laporan('{$activity['tahun']}','{$activity['bulan']}','{$hari_temp}','{$activity['activity_id']}','$nip','{$activity['nosurat']}','{$activity['tglsurat']}','{$activity['tempat']}','{$activity['periode']}');\"></i>";
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
	return $bulan_array[$bln];
}

// Fungsi untuk mendapatkan info kegiatan
function getActivityInfo($activity) {
	global $conn;
	$activity_id = $activity['activity_id'];
	$sql = "SELECT * FROM activities WHERE id='$activity_id'";
	$result = mysqli_query($conn, $sql);
	return mysqli_fetch_array($result);
}
?>
