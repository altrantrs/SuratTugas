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

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=Laporan".$nama."-".$tanggal.$bulan.$tahun.".doc");

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
    <title>Surat Tugas</title>
    <style>
    body {
        font-family: "Bookman Old Style", serif;
        margin: 0; /* Menghilangkan margin default pada body */
        padding: 0; /* Menghilangkan padding default pada body */
    }
    table {
        width: 100%;
        border-collapse: collapse;
        border: none; /* Menghilangkan border pada tabel */
    }
    th, td {
        padding: 8px;
        text-align: left;
        vertical-align: top;
        border: none; /* Menghilangkan border pada sel */
        background-color: transparent; /* Menghilangkan background pada sel */
    }
    th {
        background-color: transparent; /* Menghilangkan background pada header tabel */
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
    p {
        margin: 0; /* Menghilangkan margin default pada paragraf */
        line-height: 1.0; /* Mengatur line height menjadi single atau 1.0 */
    }
    .no-spacing {
        margin: 0;
        padding: 0;
    }
</style>

</head>
<body>
    <div class="center">
        <p><b>BADAN PUSAT STATISTIK</b><br>
        <b>KABUPATEN WONOGIRI</b></p>
        <p>SURAT TUGAS<br>
        NOMOR '.htmlspecialchars($nosurat).'</p>
    </div>

    <table border="0">
        <tr>
            <td>Menimbang</td>
            <td> :</td>
            <td>
                <ol type="a">
                    <li>bahwa dalam rangka Pelaksanaan Surat Tugas '.htmlspecialchars($keg['activity']).' di Badan Pusat Statistik Kabupaten Wonogiri Provinsi Jawa Tengah, maka diperlukan penunjukan untuk melaksanakan kegiatan tersebut;</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td>Mengingat</td>
            <td> :</td>
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

    <p class="text-center">Memberi Tugas</p>
    
    <table>
        <tr>
            <td width="20%">Kepada</td>
            <td width="1%">:</td>
            <td width="79%">'.htmlspecialchars($peg['nama']).'</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>'.htmlspecialchars($peg['jabatan']).'</td>
        </tr>
        <tr>
            <td>Untuk</td>
            <td>:</td>
            <td>Melakukan '.htmlspecialchars($keg['activity']).'</td>
        </tr>
        <tr>
            <td>Jadwal</td>
            <td>:</td>
            <td>'.htmlspecialchars($prj['jadwal']).'</td>
        </tr>
    </table>

    <br>

    <table border="0" width="100%">
        <tr>
            <td width="60%"></td>
            <td width="40%">
            <center>    
            Wonogiri, '.htmlspecialchars($tanggalFormat).'<br>
                '.htmlspecialchars($mengetahui).' Kabupaten Wonogiri<br>
                    Nomor SP: '.htmlspecialchars($nosurat).'
                    <br><br><br><br><br>
                    '.htmlspecialchars($namattd).'
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
                    <p style="text-align:left;">Nomor   : '.htmlspecialchars($nosurat).'</p>
                    <p style="text-align:left;">Tanggal : '.htmlspecialchars($tanggalFormat).'</p>
                </center>
            </td>
        </tr>
    </table>

    <center>Form Bukti Kunjungan</center>
    <table width="100%" border="1" cellspacing=0 cellpadding=2>
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
           <center> 1 </center> <br><br><br><br><br><br><br><br><br><br>
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
            <center> 2 </center> <br><br><br><br><br><br><br><br><br><br>
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
            <center> 3 </center> <br><br><br><br><br><br><br><br><br><br>
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
</body>
</html>';
?>
