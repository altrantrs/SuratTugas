<?php
session_start();
include_once("db_connection.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$nip = isset($_REQUEST['nip']) ? $_REQUEST['nip'] : '';
$id_kegiatan = isset($_REQUEST['kegiatan']) ? $_REQUEST['kegiatan'] : '';
$tahun = isset($_REQUEST['tahun']) ? $_REQUEST['tahun'] : '';
$bulan = isset($_REQUEST['bulan']) ? $_REQUEST['bulan'] : '';
$tanggal = isset($_REQUEST['tanggal']) ? $_REQUEST['tanggal'] : '';
$nosurat = isset($_REQUEST['nosurat']) ? $_REQUEST['nosurat'] : '';
$tglsurat = isset($_REQUEST['tglsurat']) ? $_REQUEST['tglsurat'] : '';
$tujuan = isset($_REQUEST['tujuan']) ? $_REQUEST['tujuan'] : '';
$periode = isset($_REQUEST['periode']) ? $_REQUEST['periode'] : '';

$sql_ttd = "SELECT * FROM settings";
$result_ttd = mysqli_query($conn, $sql_ttd); 
$ttd = mysqli_fetch_array($result_ttd);
$nipttd = $ttd['ttd'];

$sql_ttd2 = "SELECT * FROM pegawai WHERE nip='$nipttd'";
$result_ttd2 = mysqli_query($conn, $sql_ttd2); 
$ttd2 = mysqli_fetch_array($result_ttd2);
$namattd = $ttd2['nama'];
$jabatan = $ttd2['jabatan'];
$mengetahui = "Kepala Badan Pusat Statistik ";

if ($jabatan == 'Kepala') {
    $mengetahui = "Kepala Badan Pusat Statistik";
} else {
    $mengetahui = "a.n.Kepala Badan Pusat Statistik";
}

$sql_peg = "SELECT * FROM pegawai WHERE nip='$nip'";
$result_peg = mysqli_query($conn, $sql_peg); 
$peg = mysqli_fetch_array($result_peg);

$sql_keg = "SELECT * FROM activities WHERE id='$id_kegiatan'";
$result_keg = mysqli_query($conn, $sql_keg); 
$keg = mysqli_fetch_array($result_keg);

$sql_prj = "SELECT * FROM activity_dates WHERE nip='$nip' AND tahun='$tahun' AND bulan='$bulan' AND tanggal='$tanggal' AND id_kegiatan='$id_kegiatan'";
$result_prj = mysqli_query($conn, $sql_prj); 
$prj = mysqli_fetch_array($result_prj);

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
        NOMOR B-217/33120/VS.330/2024</p>
    </div>

    <table>
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
            <td width="79%">Adhi Yuliawan Prasetyo, SE;</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>Statistisi Terampil;</td>
        </tr>
        <tr>
            <td>Untuk</td>
            <td>:</td>
            <td>Melakukan Pencacahan Survei Profil Pasar 2024;</td>
        </tr>
        <tr>
            <td>Jadwal</td>
            <td>:</td>
            <td>1-30 Juni 2024.</td>
        </tr>
    </table>

    <br>

    <div class="signature">
        Wonogiri, 28 Mei 2024<br>
        Plh. Kepala BPS Kabupaten Wonogiri<br>
        Nomor SP: B-200/33000/KP.650/2024,
        <br><br><br><br><br>
        Kurniawan Dwi Nugroho, S.ST
    </div>

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
