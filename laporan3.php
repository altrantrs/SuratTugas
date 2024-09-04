<?php
$nosurat = "B-217/33120/VS.330/2024";
$tanggalFormat = "28 Mei 2024";
$mengetahui = "Plh. Kepala BPS Kabupaten Wonogiri";
$namattd = "Kurniawan Dwi Nugroho, S.ST";
$keg = [
    'activity' => 'Pencacahan Survei Profil Pasar 2024'
];
$peg = [
    'nama' => 'Adhi Yuliawan Prasetyo, SE',
    'jabatan' => 'Statistisi Terampil'
];
$prj = [
    'jadwal' => '1-30 Juni 2024'
];

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=Laporan".$nama."-".$tanggal.$bulan.$tahun.".doc");

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
        margin: 0;
        padding: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        border: none;
    }
    th, td {
        padding: 5px;
        text-align: left;
        vertical-align: top;
        background-color: transparent; 
    }
    .center {
        text-align: center;
    }
    p {
        margin: 0; 
        line-height: 1.0;
    }
    .no-spacing {
        margin: 0;
        padding: 0;
    }
    .table-no-border td {
        border: none;
    }
    .narrow-col {
        width: 20%;
    }
    .wide-col {
        width: 80%;
    }
    .small-spacing {
        padding-right: 5px;
        padding-left: 5px;
    }
</style>
</head>
<body>
    <div class="center" style=font-family:Arial>
    <img src="http://webapps.bps.go.id/wonogirikab/translok/images/bps.png" width=70 height=50 ><br><br>
        <p><b>BADAN PUSAT STATISTIK</b><br>
        <b>KABUPATEN WONOGIRI</b></p>
    </div>
    <br>
    <center>
        <p>SURAT TUGAS<br>
        NOMOR '.htmlspecialchars($nosurat).'</p>
    </center>
    <table class="table-no-border">
        <tr>
            <td class="narrow-col small-spacing">Menimbang</td>
            <td class="small-spacing">:</td>
            <td class="wide-col">
                <ol type="a">
                    <li>bahwa dalam rangka Pelaksanaan Surat Tugas '.htmlspecialchars($keg['activity']).' di Badan Pusat Statistik Kabupaten Wonogiri Provinsi Jawa Tengah, maka diperlukan penunjukan untuk melaksanakan kegiatan tersebut;</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td class="narrow-col small-spacing">Mengingat</td>
            <td class="small-spacing">:</td>
            <td class="wide-col">
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
    <center>
        <p class="text-center">Memberi Tugas</p>
    </center>
    <table class="table-no-border">
        <tr>
            <td class="narrow-col small-spacing">Kepada</td>
            <td class="small-spacing">:</td>
            <td class="wide-col">'.htmlspecialchars($peg['nama']).'</td>
        </tr>
        <tr>
            <td class="narrow-col small-spacing">Jabatan</td>
            <td class="small-spacing">:</td>
            <td class="wide-col">'.htmlspecialchars($peg['jabatan']).'</td>
        </tr>
        <tr>
            <td class="narrow-col small-spacing">Untuk</td>
            <td class="small-spacing">:</td>
            <td class="wide-col">Melakukan '.htmlspecialchars($keg['activity']).'</td>
        </tr>
        <tr>
            <td class="narrow-col small-spacing">Jadwal</td>
            <td class="small-spacing">:</td>
            <td class="wide-col">'.htmlspecialchars($prj['jadwal']).'</td>
        </tr>
    </table>
    <br>
    <table class="table-no-border" width="100%">
        <tr>
            <td width="60%"></td>
            <td width="40%">
            <center>    
            Wonogiri, '.htmlspecialchars($tanggalFormat).'<br>
                '.htmlspecialchars($mengetahui).'<br>
                    Nomor SP: '.htmlspecialchars($nosurat).'
                    <br><br><br><br><br>
                    '.htmlspecialchars($namattd).'
                </center>
            </td>
        </tr>
    </table>
    <br><br><br>
    <table width="100%" class="table-no-border" cellspacing=0 cellpadding=2>
        <tr>
            <td width="60%"></td>
            <td width="40%">
                <center>
                    <p style="text-align:left;">Lampiran Surat Tugas</p>
                    <p style="text-align:left;">Nomor : '.htmlspecialchars($nosurat).'</p>
                    <p style="text-align:left;">Tanggal : '.htmlspecialchars($tanggalFormat).'</p>
                </center>
            </td>
        </tr>
    </table>
    <br>
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
    <table class="table-no-border" width="100%">
        <tr>
            <td width="60%"></td>
            <td width="40%">
                <center>
                    Mengetahui,<br>
                    '.htmlspecialchars($mengetahui).'<br>
                    Kabupaten Wonogiri<br><br><br><br><br>
                    '.htmlspecialchars($namattd).'
                </center>
            </td>
        </tr>
    </table>
    <br><br><br>
</body>
</html>
';
?>
