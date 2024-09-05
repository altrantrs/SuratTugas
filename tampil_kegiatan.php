<?php
session_start();
include_once("db_connection.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$selectedDate = isset($_GET['date']) ? $_GET['date'] : '';
$activity_id = isset($_GET['activity_id']) ? $_GET['activity_id'] : '';

if ($selectedDate === '') {
    echo "Date parameter is missing.";
    exit();
}

// Fetch activity details
$activitySql = "SELECT * FROM activity_dates WHERE date = '$selectedDate' AND activity_id = '$activity_id'";
$activityResult = $conn->query($activitySql);

$activities = [];
while ($row = $activityResult->fetch_assoc()) {
    $activities[] = $row;
}

// Fetch all employees
$pegawaiResult = $conn->query("SELECT nip, nama FROM pegawai ORDER BY nama");
$pegawaiList = [];
if ($pegawaiResult->num_rows > 0) {
    while ($row = $pegawaiResult->fetch_assoc()) {
        $pegawaiList[$row['nip']] = $row['nama'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampil Kegiatan</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <main>
            <section class="allocation">
                <div class="form-container">
                    <h2>Kegiatan pada <?= htmlspecialchars($selectedDate); ?></h2>

                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kegiatan</th>
                                <th>Pelaksana</th>
                                <th>Nomor Surat</th>
                                <th>Tanggal Surat</th>
                                <th>Tujuan Kegiatan</th>
                                <th>Jadwal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($activities) > 0) {
                                foreach ($activities as $index => $activity) {
                                    $pelaksanaName = isset($pegawaiList[$activity['pelaksana']]) ? $pegawaiList[$activity['pelaksana']] : 'Unknown';
                            ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>
                                        <td><?= htmlspecialchars($activity['activity_id']); ?></td>
                                        <td><?= htmlspecialchars($pelaksanaName); ?></td>
                                        <td><?= htmlspecialchars($activity['nomor_surat']); ?></td>
                                        <td><?= htmlspecialchars($activity['tanggal_surat']); ?></td>
                                        <td><?= htmlspecialchars($activity['tujuan_kegiatan']); ?></td>
                                        <td><?= htmlspecialchars($activity['jadwal']); ?></td>
                                        <td>
                                            <a href="perjalanan_tambah.php?date=<?= urlencode($selectedDate); ?>" class="btn btn-edit">Edit</a>
                                            <a href="perjalanan_hapus.php?date=<?= urlencode($selectedDate); ?>&activity_id=<?= urlencode($activity['activity_id']); ?>&created_by=<?= urlencode($activity['created_by']); ?>" class="btn btn-delete">Hapus</a>
                                            <a href="cetak_kegiatan.php?date=<?= urlencode($selectedDate); ?>" class="btn btn-print">Cetak</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='8'>Tidak ada data kegiatan pada tanggal ini.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
