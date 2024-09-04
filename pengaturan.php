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
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
        rel="stylesheet" />

    <!-- Feather Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="title">
        <h1>Pengaturan</h1>
    </div>
    <div class="container">
        <main>
            <section class="allocation">
                <div class="form-container">
                    <form id="activity-form" action="pengaturan_simpan.php" method="post">
                        <table cellspacing="0" cellpadding="5">
                            <?php
                            // Fetch current settings
                            $settingsQuery = "SELECT * FROM settings";
                            $settingsResult = mysqli_query($conn, $settingsQuery);
                            if (!$settingsResult) {
                                die('Error: ' . mysqli_error($conn));
                            }
                            $settings = mysqli_fetch_assoc($settingsResult);
                            ?>

                            <tr>
                                <td>Penandatangan Surat</td>
                                <td>
                                    <input type="text" id="ttd" name="ttd" value="<?php echo htmlspecialchars($settings['ttd']); ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>Pejabat Pembuat Komitmen</td>
                                <td>
                                    <input type="text" id="ppk" name="ppk" value="<?php echo htmlspecialchars($settings['ppk']); ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>Bendahara Pengeluaran</td>
                                <td>
                                    <input type="text" id="bendahara" name="bendahara" value="<?php echo htmlspecialchars($settings['bendahara']); ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>Nama Kabupaten/Kota</td>
                                <td>
                                    <input type="text" id="namakabkota" name="namakabkota" value="<?php echo htmlspecialchars($settings['namakabkota']); ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>Biaya Satuan Perjalanan</td>
                                <td>
                                    <input type="text" id="biaya" name="biaya" value="<?php echo htmlspecialchars($settings['biaya']); ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>Alamat Kantor</td>
                                <td>
                                    <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars($settings['alamat']); ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>Website, email, telp</td>
                                <td>
                                    <input type="text" id="url" name="url" value="<?php echo htmlspecialchars($settings['url']); ?>">
                                </td>
                            </tr>

                        </table><br>
                        <div class="buttons">
                            <button type="submit" id="save-btn" class="btn btn-save">Simpan</button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <br><br><br>
    <ul>
        .
    </ul>
    </div>

    <script>
        function cari(str) {
            var xhttp;
            if (str.length == 0) {
                str = " ";
            }
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("data").innerHTML = this.responseText;
                }
            };
            document.getElementById("data").innerHTML = "<center><img src='images/loading.gif'/></center>";
            xhttp.open("GET", "showkomponen.php?q=" + encodeURIComponent(str), true);
            xhttp.send();
        }

        function isi(s1, s2, x) {
            document.getElementById("kodekomponen").value = s1;
            document.getElementById("nama").value = s2;
            document.getElementById("edit").value = x;
        }

        function kosong() {
            document.getElementById("kodekomponen").value = "";
            document.getElementById("nama").value = "";
            document.getElementById("edit").value = "";
            document.savekomponen.kodekomponen.focus();
        }

        function konfirmadd() {
            if (!confirm("Yakin akan disimpan?")) {
                return false;
            }
        }

        function konfirmhapus(y) {
            if (!confirm("Yakin akan dihapus?")) {
                return false;
            } else {
                var lok = "komponendelete.php?id";
                window.location = lok.concat("=", y);
            }
        }
    </script>
</body>

</html>