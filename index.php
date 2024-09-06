<?php
session_start();
include_once("db_connection.php");
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$result = null;
$nip = $_SESSION["nip"];
$employees = [];
if ($_SESSION['level'] == "Administrator") {
    // Fetch all employees for administrator
    $result = mysqli_query($conn, "SELECT * FROM pegawai ORDER BY nama");
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[] = $row;
    }
} else {
    // Fetch data for the logged-in user
    $result = mysqli_query($conn, "SELECT * FROM pegawai WHERE nip='$nip'");
    $employees = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas BPS Kabupaten Wonogiri</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Feather Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="filter">
            <label for="employee-select">Pilih Pegawai:</label>
            <select id="employee-select" onchange="generateCalendar()">
                <?php if ($_SESSION['level'] == "Administrator") { ?>
                    <option value="all">Semua Pegawai</option>
                <?php } ?>
                <?php foreach ($employees as $employee) { ?>
                    <option value="<?php echo $employee['nip']; ?>"><?php echo $employee['nama']; ?></option>
                <?php } ?>
            </select>

            <label for="month-select">Bulan:</label>
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

        <div class="main-content">
            <div class="employee-table" id="employee-table">
                <!-- Employee table will be generated here -->

                <?php
                echo "<table border='1' cellspacing='0' cellpadding='5'>
<tr bgcolor='#FFFCCC'>
<td align='center' colspan='40'>Kegiatan di Bulan " . bulan($bulan) . " $tahun</td></tr>";?>
<?php if ($_SESSION['level'] == "Administrator") { 
    while ($pegawai = mysqli_fetch_array($result)) {
    $nama = isset($pegawai['nama']) ? $pegawai['nama'] : '';

    echo "<tr><td>$nama</td>"; ?>
                    <div class="calendar">
                <div class="days" id="days-container">
                    <!-- Calendar will be generated here -->
                </div>
            </div>
            </div>
            </tr>
     <?php
}
    echo "</table>"; ?>
   
<?php } ?> 
        </div>
</div>
    </main>

    <script src="script.js"></script>
</body>
</html>
