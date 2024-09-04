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
    echo "<tr><td>" . $nama . "</td>";
    while ($waktu_temp <= $akhir_tgl) {
        $current_date = date("Y-m-d", $waktu_temp); // Format the current date

        // Determine the day of the week for coloring
        $hari_tem = date("D", $waktu_temp);
        $hari_temp = date("d", $waktu_temp);

        $bln_temp = date("D", $waktu_temp);
        if ($bln_temp == "Mon") {
            $bln_temp = "S";
        } elseif ($bln_temp == "Tue") {
            $bln_temp = "S";
        } elseif ($bln_temp == "Wed") {
            $bln_temp = "R";
        } elseif ($bln_temp == "Thu") {
            $bln_temp = "K";
        } elseif ($bln_temp == "Fri") {
            $bln_temp = "J";
        } elseif ($bln_temp == "Sat") {
            $bln_temp = "S";
        } elseif ($bln_temp == "Sun") {
            $bln_temp = "M";
        }

        $whari = ($hari_tem == "Sun" || $hari_tem == "Sat") ? "pink" : "white";

        include("db_connection.php");

        // Updated SQL query to match the date column
        $sql = "SELECT * FROM activity_dates WHERE created_by='$nip' AND date='$current_date'";
        $result2 = mysqli_query($conn, $sql);

        if ($result2 && mysqli_num_rows($result2) > 0) {
            $res = mysqli_fetch_array($result2);

            $nosurat = isset($res['nosurat']) ? $res['nosurat'] : '';
            $tglsurat = isset($res['tglsurat']) ? $res['tglsurat'] : '';
            $jabatan = isset($res['jabatan']) ? $res['jabatan'] : '';
            $id_kegiatan = isset($res['activity_id']) ? $res['activity_id'] : '';
            $periode = isset($res['periode']) ? $res['periode'] : '';
            $tempat = isset($res['tempat']) ? $res['tempat'] : '';
            $status = isset($res['status']) ? $res['status'] : '';
            $id = isset($res['id']) ? $res['id'] : '';
            $result3 = mysqli_query($conn, "SELECT * FROM activities WHERE id='$id_kegiatan'");
            $res3 = mysqli_fetch_array($result3);

            if ($_SESSION['level'] == "Administrator") {
                if ($status == "1") {
                    echo "<td align=center bgcolor=" . $whari . ">" . $bln_temp . "<br><button onclick=\"document.getElementById('id01').style.display='block';edit('$nip','$hari_temp','$bulan','$tahun','$jabatan','$id_kegiatan','$nosurat','$tglsurat','$tempat','$periode','$status','$id');\" class='tanggal3' title='" . $res3['id'] . " - " . $res3['nama_kegiatan'] . "'>" . $hari_temp . "</button><img src='images/delete.png' height=10 width=10  title='Hapus' onclick=\"del('" . $res['id'] . "');\"> <img src='images/print.png' height=15 width=15  title='Print' onclick=\"laporan('$tahun','$bulan','$hari_temp','$id_kegiatan','$nip','$nosurat','$tglsurat','$tempat','$periode');\"></td>";
                } else {
                    echo "<td align=center bgcolor=" . $whari . ">" . $bln_temp . "<br><button onclick=\"document.getElementById('id01').style.display='block';edit('$nip','$hari_temp','$bulan','$tahun','$jabatan','$id_kegiatan','$nosurat','$tglsurat','$tempat','$periode','$status','$id');\" class='tanggal1' title='" . $res3['id'] . " - " . $res3['nama_kegiatan'] . "'>" . $hari_temp . "</button><img src='images/delete.png' height=10 width=10  title='Hapus' onclick=\"del('" . $res['id'] . "');\"> <img src='images/print.png' height=15 width=15  title='Print' onclick=\"laporan('$tahun','$bulan','$hari_temp','$id_kegiatan','$nip','$nosurat','$tglsurat','$tempat','$periode');\"></td>";
                }
            } else {
                if ($status == "1") {
                    echo "<td align=center bgcolor=" . $whari . ">" . $bln_temp . "<br><button onclick=\"alert('" . $res3['nama_kegiatan'] . "');\" class='tanggal3' title='" . $res3['kode_kegiatan'] . " - " . $res3['nama_kegiatan'] . "'>" . $hari_temp . "</button> <img src='images/print.png' height=15 width=15  title='Print' onclick=\"laporan('$tahun','$bulan','$hari_temp','$id_kegiatan','$nip','$nosurat','$tglsurat','$tempat','$periode');\"></td>";
                } else {
                    echo "<td align=center bgcolor=" . $whari . ">" . $bln_temp . "<br><button onclick=\"alert('" . $res3['nama_kegiatan'] . "');\" class='tanggal1' title='" . $res3['kode_kegiatan'] . " - " . $res3['nama_kegiatan'] . "'>" . $hari_temp . "</button> <img src='images/print.png' height=15 width=15  title='Print' onclick=\"laporan('$tahun','$bulan','$hari_temp','$id_kegiatan','$nip','$nosurat','$tglsurat','$tempat','$periode');\"></td>";
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
    $rekap = mysqli_query($conn, "SELECT count(activity_id) as jum FROM activity_dates WHERE created_by='$nip' and MONTH(date)='$bulan' and YEAR(date)='$tahun'");
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

function bulan($bln)
{
    $bulan_array = array(
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
