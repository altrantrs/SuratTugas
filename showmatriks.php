<!-- 

Fatal error: Uncaught mysqli_sql_exception: Unknown column 'nip' in 'where clause' in D:\xampp\htdocs\Surat\showmatriks.php:81 Stack trace: #0 D:\xampp\htdocs\Surat\showmatriks.php(81): mysqli_query(Object(mysqli), 'SELECT * FROM a...') #1 D:\xampp\htdocs\Surat\showmatriks.php(32): harikerja('198712345678901...', '01', '2024', '2024/01/01', '2024/01/31', 'Andi Wijaya') #2 {main} thrown in D:\xampp\htdocs\Surat\showmatriks.php on line 81 -->
<?php
session_start();

include_once("db_connection.php");
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

$tahun = $_REQUEST['tahun'];
$bulan = $_REQUEST['bulan'];
$nip = $_REQUEST['nip'];
$nama = "";

// Define start and end dates for the month
$tgl_awal = $tahun . "/" . $bulan . "/01";
$awal_tgl = strtotime($tgl_awal);
$t = date("t", $awal_tgl);
$tgl_akhir = $tahun . "/" . $bulan . "/" . $t;

$sql_peg = "";
if ($nip == "all") {
    $sql_peg = "SELECT * FROM pegawai ORDER BY nama";
} else {
    $sql_peg = "SELECT * FROM pegawai WHERE nip='$nip' ORDER BY nama";
}

$result = mysqli_query($conn, $sql_peg);
echo "<table border='1' cellspacing='0' cellpadding='5'><tr bgcolor='#FFFCCC'><td align=center colspan=40>Kegiatan di Bulan " . bulan($bulan) . " " . $tahun . "</td></tr>";
while ($res = mysqli_fetch_array($result)) {
    $nama = $res['nama'];
    $nip2 = $res['nip'];
    echo harikerja($nip2, $bulan, $tahun, $tgl_awal, $tgl_akhir, $nama);
}

function harikerja($nip, $bulan, $tahun, $tgl_awal, $tgl_akhir, $nama)
{
    $awal_tgl = strtotime($tgl_awal);
    $akhir_tgl = strtotime($tgl_akhir);

    $waktu_temp = $awal_tgl;
    $bul = bulan(date("m", $waktu_temp));
    $tah = date("Y", $waktu_temp);
    echo "<tr><td>" . $nama . "</td>";
    while ($waktu_temp <= $akhir_tgl) {
        $hari_tem = date("D", $waktu_temp);
        $hari_temp = date("d", $waktu_temp);
        $bln_temp = date("D", $waktu_temp);

        if ($bln_temp == "Mon") {
            $bln_temp = "S";
        }
        if ($bln_temp == "Tue") {
            $bln_temp = "S";
        }
        if ($bln_temp == "Wed") {
            $bln_temp = "R";
        }
        if ($bln_temp == "Thu") {
            $bln_temp = "K";
        }
        if ($bln_temp == "Fri") {
            $bln_temp = "J";
        }
        if ($bln_temp == "Sat") {
            $bln_temp = "S";
        }
        if ($bln_temp == "Sun") {
            $bln_temp = "M";
        }

        $whari = "";
        if (($hari_tem == "Sun") || ($hari_tem == "Sat")) {
            $whari = "pink";
        } else {
            $whari = "white";
        }

        include("db_connection.php");

        $sql = "SELECT * FROM activity_dates WHERE nip='$nip' AND tanggal='$hari_temp' AND bulan='$bulan' AND tahun='$tahun'";
        $result2 = mysqli_query($conn, $sql);

        if ($result2->num_rows > 0) {
            $res = mysqli_fetch_array($result2);

            $nosurat = $res['nosurat'];
            $tglsurat = $res['tglsurat'];
            $jabatan = $res['jabatan'];
            $id_kegiatan = $res['id_kegiatan'];
            $periode = $res['periode'];
            $tujuan = $res['tempat'];
            $status = $res['status'];
            $id = $res['id'];
            $result3 = mysqli_query($conn, "SELECT * FROM activities WHERE id='$id_kegiatan'");
            $res3 = mysqli_fetch_array($result3);

            if ($_SESSION['level'] == "Administrator") {
                if ($status == "1") {
                    echo "<td align=center bgcolor=" . $whari . ">" . $bln_temp . "<br><button onclick=\"document.getElementById('id01').style.display='block';edit('$nip','$hari_temp','$bulan','$tahun','$jabatan','$id_kegiatan','$nosurat','$tglsurat','$tujuan','$periode','$status','$id');\" class='tanggal3' title='" . $res3['id'] . " - " . $res3['nama_kegiatan'] . "'>" . $hari_temp . "</button><img src='images/delete.png' height=10 width=10  title='Hapus' onclick=\"del('" . $res['id'] . "');\"> <img src='images/print.png' height=15 width=15  title='Print' onclick=\"laporan('$tahun','$bulan','$hari_temp','$id_kegiatan','$nip','$nosurat','$tglsurat','$tujuan','$periode');\"></td>";
                } else {
                    echo "<td align=center bgcolor=" . $whari . ">" . $bln_temp . "<br><button onclick=\"document.getElementById('id01').style.display='block';edit('$nip','$hari_temp','$bulan','$tahun','$jabatan','$id_kegiatan','$nosurat','$tglsurat','$tujuan','$periode','$status','$id');\" class='tanggal1' title='" . $res3['id'] . " - " . $res3['nama_kegiatan'] . "'>" . $hari_temp . "</button><img src='images/delete.png' height=10 width=10  title='Hapus' onclick=\"del('" . $res['id'] . "');\"> <img src='images/print.png' height=15 width=15  title='Print' onclick=\"laporan('$tahun','$bulan','$hari_temp','$id_kegiatan','$nip','$nosurat','$tglsurat','$tujuan','$periode');\"></td>";
                }
            } else {
                if ($status == "1") {
                    echo "<td align=center bgcolor=" . $whari . ">" . $bln_temp . "<br><button onclick=\"alert('" . $res3['nama_kegiatan'] . "');\" class='tanggal3' title='" . $res3['kode_kegiatan'] . " - " . $res3['nama_kegiatan'] . "'>" . $hari_temp . "</button> <img src='images/print.png' height=15width=15  title='Print' onclick=\"laporan('$tahun','$bulan','$hari_temp','$id_kegiatan','$nip','$nosurat','$tglsurat','$tujuan','$periode');\"></td>";
                } else {
                    echo "<td align=center bgcolor=" . $whari . ">" . $bln_temp . "<br><button onclick=\"alert('" . $res3['nama_kegiatan'] . "');\" class='tanggal1' title='" . $res3['kode_kegiatan'] . " - " . $res3['nama_kegiatan'] . "'>" . $hari_temp . "</button> <img src='images/print.png' height=15 width=15  title='Print' onclick=\"laporan('$tahun','$bulan','$hari_temp','$id_kegiatan','$nip','$nosurat','$tglsurat','$tujuan','$periode');\"></td>";
                }
            }
        } else {
            if ($_SESSION['level'] == "Administrator") {
                echo "<td align=center bgcolor=" . $whari . ">" . $bln_temp . "<br><button onclick=\"document.getElementById('id01').style.display='block';isi('$nip','$hari_temp','$bulan','$tahun');\" class='tanggal0'>" . $hari_temp . "</button></td>";
            } else {
                echo "<td align=center bgcolor=" . $whari . ">" . $bln_temp . "<br><button class='tanggal0'>" . $hari_temp . "</button></td>";
            }
        }

        $waktu_temp = strtotime("+1 day", $waktu_temp);
    }
    $rekap = mysqli_query($conn, "SELECT count(id_kegiatan) as jum FROM activity_dates WHERE nip='$nip' and bulan='$bulan' and tahun='$tahun'");
    $resrekap = mysqli_fetch_array($rekap);
    $wrekap = "";
    if ($resrekap['jum'] > 0) {
        if ($resrekap['jum'] > 15) {
            $wrekap = "red";
        } else {
            $wrekap = "green";
        }
    } else {
        $wrekap = "white";
    }
    echo "<td bgcolor=" . $wrekap . "><p style='color:white;'>" . $resrekap['jum'] . "</p></td>";
    echo "</tr>";
}

function bulan($bln){
$bulan_array = array (
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
);
return $bulan_array[$bln];
}?>