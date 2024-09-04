<?php
session_start();
include_once("db_connection.php");
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$nip=$_REQUEST['nip'];
$id_kegiatan=$_REQUEST['kegiatan'];	
$tahun=$_REQUEST['tahun'];
$bulan=$_REQUEST['bulan'];
$tanggal=$_REQUEST['tanggal'];
$nosurat=$_REQUEST['nosurat'];
$tglsurat=$_REQUEST['tglsurat'];
$tujuan=$_REQUEST['tujuan'];
$periode=$_REQUEST['periode'];

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=Laporan".".docx");

$sql_ttd="SELECT * FROM settings";
$result_ttd = mysqli_query($conn,$sql_ttd); 
$ttd = mysqli_fetch_array($result_ttd);
$nipttd=$ttd['ttd'];

$sql_ttd2="SELECT * FROM pegawai WHERE nip='$nipttd'";
$result_ttd2 = mysqli_query($conn,$sql_ttd2); 
$ttd2 = mysqli_fetch_array($result_ttd2);
$namattd=$ttd2['nama'];
$jabatan=$ttd2['jabatan'];
$mengetahui="Kepala Badan Pusat Statistik ";
if($jabatan=='Kepala'){
    $mengetahui="Kepala Badan Pusat Statistik";
}
else{
    $mengetahui="a.n.Kepala Badan Pusat Statistik";
}
$sql_peg="SELECT * FROM pegawai WHERE nip='$nip'";
$result_peg = mysqli_query($mysqli,$sql_peg); 
$peg = mysqli_fetch_array($result_peg);
 
$sql_keg="SELECT * FROM activities WHERE id='$id_kegiatan'";
$result_keg = mysqli_query($conn,$sql_keg); 
$keg = mysqli_fetch_array($result_keg);

$sql_prj="SELECT * FROM activity_dates WHERE nip='$nip' AND tahun='$tahun' AND bulan='$bulan' AND tanggal='$tanggal' AND id_kegiatan='$id_kegiatan'";
$result_prj = mysqli_query($conn,$sql_prj); 
$prj = mysqli_fetch_array($result_prj);

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
</html>
';
?>
