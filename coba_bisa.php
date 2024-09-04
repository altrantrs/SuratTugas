<?php
session_start();
include_once("db_connection.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

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
// header("Content-Disposition: attachment;Filename=Laporan".".doc");

// Fetching data from settings
$sql_ttd = "SELECT * FROM settings";
$result_ttd = mysqli_query($conn, $sql_ttd); 
$ttd = mysqli_fetch_array($result_ttd);

$nipttd = $ttd ? $ttd['ttd'] : '';

// Fetching data from pegawai
$sql_ttd2 = "SELECT * FROM pegawai WHERE nip='$nipttd'";
$result_ttd2 = mysqli_query($conn, $sql_ttd2); 
$ttd2 = mysqli_fetch_array($result_ttd2);

$namattd = $ttd2 ? $ttd2['nama'] : '';
$jabatan = $ttd2 ? $ttd2['jabatan'] : '';

$mengetahui = $jabatan == 'Kepala' ? "Kepala Badan Pusat Statistik" : "a.n.Kepala Badan Pusat Statistik";

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
    <title>Surat Tugas</title>
    <style>
        body {
            font-family: "Bookman Old Style", serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
        }
        .center {
            text-align: center;
        }
        .title {
            font-weight: bold;
            text-align: center;
            font-size: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="center">
        <p><b>BADAN PUSAT STATISTIK</b><br>
        KABUPATEN WONOGIRI</p>
        <p><b>SURAT TUGAS</b><br>
        NOMOR '.$nosurat.'</p>
    </div>

    <table border="0">
        <tr>
            <th>Membimbang</th>
            <td>
                <ol type="a">
                    <li>bahwa dalam rangka Pelaksanaan Surat Tugas Pencacahan Survei Profil Pasar 2024 di Badan Pusat Statistik Kabupaten Wonogiri Provinsi Jawa Tengah, maka diperlukan penunjukan untuk melaksanakan kegiatan tersebut;</li>
                </ol>
            </td>
        </tr>
        <tr>
            <th>Mengingat</th>
            <td>
                <ol type="1">
                    <li>Peraturan Pemerintah Nomor 30 Tahun 2019 tentang Penilaian Kinerja Pegawai Negeri Sipil;</li>
                    <li>Peraturan Menteri Pendayagunaan Aparatur Negara dan Reformasi Birokrasi Nomor 8 Tahun 2021 tentang Sistem Manajemen Kinerja Pegawai Negeri Sipil;</li>
                    <li>Peraturan Badan Pusat Statistik Nomor 7 Tahun 2020 tentang Organisasi dan Tata Kerja Badan Pusat Statistik; dan</li>
                    <li>Peraturan Badan Pusat Statistik Nomor 8 Tahun 2020 tentang Organisasi dan Tata Kerja Badan Pusat Statistik Provinsi dan Badan Pusat Statistik Kabupaten/Kota.</li>
                </ol>
            </td>
        </tr>
    </table>

    <br>

    <p class="text-center"><b>Memberi Tugas</b></p>
    
    <table>
        <tr>
            <td width="20%">Kepada</td>
            <td width="1%">:</td>
            <td width="79%">'.$peg['nama'].'</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>'.$peg['jabatan'].'</td>
        </tr>
        <tr>
            <td>Untuk</td>
            <td>:</td>
            <td>Melakukan '.$keg['activity'].'</td>
        </tr>
        <tr>
            <td>Jadwal</td>
            <td>:</td>
            <td>'.$prj['jadwal'].'</td>
        </tr>
    </table>

    <br>

        <table border="0" width="100%">
    <tr>
        <td width="60%"></td>
        <td width="40%">
        Wonogiri, '.$tanggal.' '.$bulan.' '.$tahun.'<br>
            <center>
                '.$mengetahui.' Kabupaten Wonogiri
                Nomor SP: '.$nosurat.'
                <br><br><br><br><br>
                '.$namattd.'
            </center>
        </td>
    </tr>
    </table>

    <br><br><br>
    <table width="100%" border="0" cellspacing=0 cellpadding=2>
        <tr>
            <td width="60%"></td>
            <td width="40%">
                <center>
                    <p style="text-align:left;">Lampiran Surat Tugas</p>
                    <p style="text-align:left;">Nomor  :</p>
                    <p style="text-align:left;">Tanggal :</p>
                </center>
            </td>
        </tr>
    </table>

</body>
</html>';
?>
