
<?php
session_start();
include_once("db_connection.php");
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$result = null;
$nip = $_SESSION["nip"];
if ($_SESSION['level'] == "Administrator") {
    $result = mysqli_query($conn, "SELECT * FROM pegawai ORDER BY nama");
} else {
    $result = mysqli_query($conn, "SELECT * FROM pegawai WHERE nip='$nip'");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas BPS Kabupaten Wonogiri</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
        rel="stylesheet" />

    <!-- Feather Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />


    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    include 'header.php';
    ?>

    <main>
        <div>
            <?php if ($_SESSION['level'] == "Administrator") { ?>
                <table>
                    <tr style='color:#fff;'>
                        <td align='center' bgcolor='#3db2e1'>Tahun</td>
                        <td align='center' bgcolor='#3db2e1'>Bulan</td>
                        <td align='center' bgcolor='#3db2e1'>Pegawai</td>
                        <td align='center' rowspan='2'><button id="proses" name="proses" onclick="cari();">Tampil</button></td>
                        <td align='center' rowspan='2'><button id="excel" name="excel" onclick="excel();">Excel</button></td>
                    </tr>
                    <tr>
                        <td>
                            <select id="tahun" name="tahun">
                                <option selected>--Pilih--</option>
                                <option value="2024">2024</option>
                            </select>
                        </td>
                        <td>
                            <select id="bulan" name="bulan">
                                <option selected>--Pilih--</option>
                                <option value="01">Januari</option>
                                <option value="1">Februari</option>
                                <option value="2">Maret</option>
                                <option value="3">April</option>
                                <option value="4">Mei</option>
                                <option value="5">Juni</option>
                                <option value="6">Juli</option>
                                <option value="7">Agustus</option>
                                <option value="8">September</option>
                                <option value="9">Oktober</option>
                                <option value="10">November</option>
                                <option value="11">Desember</option>
                            </select>
                        </td>
                        <td>
                            <select id="nip" name="nip">
                                <option selected>--Pilih--</option>
                                <?php
                                if ($_SESSION['level'] == "Administrator") {
                                    echo '<option value="all">Semua Pegawai</option>';
                                }
                                while ($res = mysqli_fetch_array($result)) {
                                    echo "<option value=" . $res['nip'] . ">" . $res['nama'] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <br>
                <span id="data"></span>
                <br>
                <ul>.</ul>
        </div>
    <?php } else { ?>
        <div class="filter">
            <label for="month-select">Bulan</label>
            <select id="month-select" onchange="generateCalendar()">
                <option value="0">Januari</option>
                <option value="1">Februari</option>
                <option value="2">Maret</option>
                <option value="3">April</option>
                <option value="4">Mei</option>
                <option value="5">Juni</option>
                <option value="6">Juli</option>
                <option value="7">Agustus</option>
                <option value="8">September</option>
                <option value="9">Oktober</option>
                <option value="10">November</option>
                <option value="11">Desember</option>
            </select>
        </div>
        <!-- <div class="employee-name"> 
        <?php echo $_SESSION["user"]; ?>
        </div> -->
        <div class="calendar">
            <div class="days" id="days-container">
            </div>
        </div>
    <?php } ?>
    </main>
    <script>
        function cari() {
            var t = document.getElementById("tahun").value;
            var b = document.getElementById("bulan").value;
            var n = document.getElementById("nip").value;

            if (t == "--Pilih--" || b == "--Pilih--" || n == "--Pilih--") {
                alert("Isian tahun, bulan dan pegawai harus diisi.");
            } else {
                var tahun = document.getElementById('tahun').value;
                var bulan = document.getElementById('bulan').value;
                var nip = document.getElementById('nip').value;

                var xhttp;
                xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("data").innerHTML = this.responseText;
                    }
                };
                document.getElementById("data").innerHTML = "<center><img src='images/loading.gif'/></center>";
                xhttp.open("GET", "showmatriks.php?tahun=" + tahun + "&bulan=" + bulan + "&nip=" + nip, true);
                xhttp.send();
            }
        }
    </script>

    <script src="script.js"></script>
</body>

</html>