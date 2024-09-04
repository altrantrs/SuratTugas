<?php
session_start();
include_once("db_connection.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$nama = isset($_POST['nama']) ? $_POST['nama'] : '';
$nip = isset($_POST['nip']) ? $_POST['nip'] : '';
$id_kegiatan = isset($_POST['kegiatan']) ? $_POST['kegiatan'] : '';
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
$nosurat = isset($_POST['nosurat']) ? $_POST['nosurat'] : '';
$tglsurat = isset($_POST['tglsurat']) ? $_POST['tglsurat'] : '';
$tujuan = isset($_POST['tujuan']) ? $_POST['tujuan'] : '';
$periode = isset($_POST['periode']) ? $_POST['periode'] : '';

// header("Content-type: application/vnd.ms-word");
// header("Content-Disposition: attachment;Filename=Laporan".$nama."-".$tanggal.$bulan.$tahun.".doc");

/// Fetching data from settings
$sql_ttd = "SELECT * FROM settings";
$result_ttd = mysqli_query($conn, $sql_ttd); 
if (!$result_ttd) {
    die('Query gagal: ' . mysqli_error($conn));
}
$ttd = mysqli_fetch_array($result_ttd);

$nipttd = $ttd ? $ttd['ttd'] : '';

// Fetching data from pegawai
$sql_ttd2 = "SELECT * FROM pegawai WHERE nip='$nipttd'";
$result_ttd2 = mysqli_query($conn, $sql_ttd2); 
if (!$result_ttd2) {
    die('Query gagal: ' . mysqli_error($conn));
}
$ttd2 = mysqli_fetch_array($result_ttd2);

$namattd = $ttd2 ? $ttd2['nama'] : 'Rahmad Iswanto, SST., M.Si';
$jabatan = $ttd2 ? $ttd2['jabatan'] : '';

// Set default value for mengetahui if jabatan is not Kepala
$mengetahui = $jabatan == 'Kepala' ? "Kepala Badan Pusat Statistik" : "Kepala Badan Pusat Statistik";

// Fetching data from pegawai
$sql_peg = "SELECT * FROM pegawai WHERE nip='$nip'";
$result_peg = mysqli_query($conn, $sql_peg);

if (!$result_peg) {
    die("Query for pegawai failed: " . mysqli_error($conn));
}

$peg = mysqli_fetch_array($result_peg);
if (!$peg) {
    die("No data found for pegawai.");
}

$sql_keg = "
    SELECT ad.*, a.activity
    FROM activity_dates ad
    JOIN activities a ON ad.activity_id = a.id
    WHERE ad.activity_id = '$id_kegiatan'
    AND ad.created_by = '$nip'
    AND YEAR(ad.date) = '$tahun'
    AND MONTH(ad.date) = '$bulan'
    AND DAY(ad.date) = '$tanggal'
";
$result_keg = mysqli_query($conn, $sql_keg);

if (!$result_keg) {
    die("Query failed: " . mysqli_error($conn));
}

$keg = mysqli_fetch_array($result_keg);
if (!$keg) {
    die("No data found for the provided criteria.");
}

// Fetching data for activity dates
$sql_prj = "SELECT * FROM activity_dates 
            WHERE created_by='$nip' 
            AND YEAR(date)='$tahun' 
            AND MONTH(date)='$bulan' 
            AND DAY(date)='$tanggal' 
            AND activity_id='$id_kegiatan'";
$result_prj = mysqli_query($conn, $sql_prj);

if (!$result_prj) {
    die("Query for activity dates failed: " . mysqli_error($conn));
}

$prj = mysqli_fetch_array($result_prj);
if (!$prj) {
    die("No data found for activity dates.");
}

// Menghapus angka nol di depan tanggal
$cleanedDate = ltrim($tanggal, '0');

// Membuat array untuk nama-nama bulan
$months = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei',
    '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober',
    '11' => 'November', '12' => 'Desember'
];

// Mengubah bulan menjadi nama bulan
$bulanNama = $months[$bulan] ?? '';

// Format tanggal dan bulan
$tanggalFormat = $cleanedDate . ' ' . $bulanNama . ' ' . $tahun;

echo '
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:w="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta charset="utf-8">
    <style>
        .label {
            vertical-align: top;
            width: 20%;
        }

        .separator {
            vertical-align: top;
            width: 5%;
            padding-right: 10px; /* Menyesuaikan jarak setelah tanda titik dua */
        }

        .content {
            vertical-align: top;
            width: 75%;
        }

        td {
            padding: 2px; /* Menyesuaikan padding antar teks */
        }

        .table-border {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
            padding: 2px;
        }

        .table-border td, .table-border th {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
<center>Form Bukti Kunjungan</center>
<table class="table-border">
<tbody>
  <tr>
    <td rowspan="2"><center>No</center></td>
    <td rowspan="2"><center>Pelaksana</center></td>
    <td rowspan="2"><center>Hari</center></td>
    <td rowspan="2"><center>Tanggal</center></td>
    <td colspan="3"><center>Pejabat/Petugas yang mengesahkan</center></td>
  </tr>
  <tr>
    <td>Nama</td>
    <td>Jabatan</td>
    <td>Tanda Tangan</td>
  </tr>
  <tr>
    <td><center>[1]</center></td>
    <td><center>[2]</center></td>
    <td><center>[3]</center></td>
    <td><center>[4]</center></td>
    <td><center>[5]</center></td>
    <td><center>[6]</center></td>
    <td><center>[7]</center></td>
  </tr>
  <tr>
    <td>
        1<br><br><br><br><br><br><br><br><br><br>
    </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>
        2<br><br><br><br><br><br><br><br><br><br>
    </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>
        3<br><br><br><br><br><br><br><br><br><br>
    </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</tbody>
</table>
<br>
<table border="0" width="100%">
    <tr>
        <td width="60%"></td>
        <td width="40%">
            <center>
                Mengetahui,<br>
                '.$mengetahui.'<br>
                Kabupaten Wonogiri<br><br><br><br><br>
                '.$namattd.'
            </center>
        </td>
    </tr>

</table>
<br><br><br>
<center>

<img src="http://webapps.bps.go.id/wonogirikab/translok/images/bps.png" width=70 height=50 ><br><br>
BADAN PUSAT STATISTIK KABUPATEN WONOGIRI <BR><BR>
SURAT TUGAS<BR>
NOMOR: '.$nosurat.'<BR><BR>

<table border="0">

    <tr><td width="20%" style="vertical-align:top;">Menimbang</td><td width="5%" style="vertical-align:top;">:</td><td style="vertical-align:top;">'.$keg['menimbang'].'</td></tr>
    <tr><td style="vertical-align:top;">Mengingat</td><td style="vertical-align:top;">:</td><td style="vertical-align:top;">'.$keg['mengingat'].'</td></tr>
    <tr><td style="text-align:center;" colspan="3">Menugaskan:</td></tr>
    <tr><td style="vertical-align:top;">Kepada</td><td style="vertical-align:top;">:</td><td style="vertical-align:top;">'.$peg['nama'].'</td></tr>
    <tr><td style="vertical-align:top;">Jabatan</td><td style="vertical-align:top;">:</td><td style="vertical-align:top;">'.$prj['jabatan'].'</td></tr>
    <tr><td style="vertical-align:top;">Untuk</td><td style="vertical-align:top;">:</td><td>Melaksanakan '.$keg['nama_kegiatan'].'</td></tr>
    <tr><td style="vertical-align:top;">Jadwal</td><td style="vertical-align:top;">:</td><td style="vertical-align:top;">'.$prj['periode'].'</td></tr>

</table>

<br><br>
<table border="0" width="100%">
    <tr>
        <td width="60%"></td>
        <td width="40%">
            <center>
                Mengetahui,<br>
                '.$mengetahui.'<br>
                Kabupaten Wonogiri<br><br><br><br><br>
                '.$namattd.'
            </center>
        </td>
    </tr>

</table>
<br><br><br><br>
Badan Pusat Statistik Kabupaten Wonogiri (Statistics of Wonogiri Regency)
<br>
Jl. Pelem II No. 8 Wonogiri 57612 Telp (0273) 321055, Faks (0273) 321055, E-Mail : bps3312@bps.go.id
</center>
<br>
';



echo'
</body>
</html>';
?>
