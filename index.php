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
            <div class="employee-name">
                <?php if ($_SESSION['level'] == "Administrator") { ?>
                    <select id="nip" name="nip">
                        <option selected>--Pilih--</option>
                        <option value="all">Semua Pegawai</option>
                        <?php while ($res = mysqli_fetch_array($result)) { ?>
                            <option value="<?php echo $res['nip']; ?>">
                                <?php echo $res['nama']; ?>
                            </option>
                        <?php } ?>
                    </select>
                <?php } else { ?>
                    <div><?php echo $_SESSION["user"]; ?></div>
                <?php } ?>
            </div>
        </div>

        <div class="calendar">
            <div class="days" id="days-container">
                <!-- Days will be generated here by JavaScript -->
            </div>
        </div>

        <h2>Daftar Pegawai dan Kegiatan</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $pegawai_nip = $row['nip'];
                    $activities = getActivities($conn, $pegawai_nip);
                    echo "<tr>";
                    echo "<td>{$counter}</td>";
                    echo "<td>{$row['nama']}</td>";
                    echo "<td>";
                    if (count($activities) > 0) {
                        foreach ($activities as $activity) {
                            echo "<div>";
                            echo "Tanggal: " . $activity['date'] . " - Kegiatan: " . $activity['activity_id'];
                            echo "</div>";
                        }
                    } else {
                        echo "Tidak ada kegiatan";
                    }
                    echo "</td>";
                    echo "</tr>";
                    $counter++;
                }
                ?>
            </tbody>
        </table>
    </main>

    <script src="script.js"></script>
</body>

</html>
