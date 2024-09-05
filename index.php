<?php
session_start();
include_once("db_connection.php");
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$result = null;
$nip = $_SESSION["nip"];
if ($_SESSION['level'] == "Administrator") {
    // Ambil semua pegawai untuk administrator
    $result = mysqli_query($conn, "SELECT * FROM pegawai ORDER BY nama");
} else {
    // Ambil data pegawai untuk user biasa
    $result = mysqli_query($conn, "SELECT * FROM pegawai WHERE nip='$nip'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas BPS Kabupaten Wonogiri</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="container">
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

            <div class="layout">
                <div class="employee-list">
                    <h3>Daftar Pegawai</h3>
                    <select id="employee-select" onchange="generateCalendar()">
                        <option value="all">Semua Pegawai</option>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <option value="<?php echo $row['nip']; ?>"><?php echo $row['nama']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="calendar">
                    <div class="days" id="days-container">
                        <!-- Kalender akan di-generate di sini -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="script2.js"></script>
</body>
</html>
