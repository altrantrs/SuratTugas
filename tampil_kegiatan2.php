<?php
include 'db_connection.php';

$date = isset($_GET['date']) ? $_GET['date'] : '';

$sql = "SELECT ad.id, ad.date, ad.nomor_surat, ad.tanggal_surat, ad.tujuan_kegiatan, ad.jadwal, a.activity
        FROM activity_dates ad
        JOIN activities a ON ad.activity_id = a.id
        WHERE ad.date = '$date'";
$result = $conn->query($sql);

$activity = [];

if ($result->num_rows > 0) {
    $activity = $result->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kegiatan</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Surat Tugas BPS Kabupaten Wonogiri</h1>
        <div class="user-profile">
            <span>Admin</span>
            <img src="user-avatar.png" alt="Admin Avatar">
        </div>
    </header>

    <?php
          include 'header.php';
        ?>
    <div class="container">
        <main>
            <h2>Detail Kegiatan</h2>
            <section class="activity-details">
                <p><strong>Tanggal:</strong> <?= htmlspecialchars($activity['date']); ?></p>
                <p><strong>Kegiatan:</strong> <?= htmlspecialchars($activity['activity']); ?></p>
                <p><strong>Nomor Surat:</strong> <?= htmlspecialchars($activity['nomor_surat']); ?></p>
                <p><strong>Tanggal Surat:</strong> <?= htmlspecialchars($activity['tanggal_surat']); ?></p>
                <p><strong>Tujuan Kegiatan:</strong> <?= htmlspecialchars($activity['tujuan_kegiatan']); ?></p>
                <p><strong>Jadwal:</strong> <?= htmlspecialchars($activity['jadwal']); ?></p>
            </section>

            <div class="buttons">
                <button class="btn btn-delete" onclick="deleteActivity()">Hapus Kegiatan</button>
                <button class="btn btn-report" onclick="window.location.href='https://s.id/laporanjadi';">Cetak Laporan</button>
                <button class="btn btn-print" onclick="printSuratTugas()">Cetak Surat Tugas</button>
            </div>
        </main>
    </div>

    <script>
        function deleteActivity() {
            if (confirm('Anda yakin ingin menghapus kegiatan ini?')) {
                fetch('delete_activity.php', {
                        method: 'POST',
                        body: new URLSearchParams({
                            date: '<?= $date ?>'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            window.location.href = 'index.php'; // Redirect to index.php after deletion
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function printReport() {
            alert('Cetak Laporan button clicked!');
        }

        function printSuratTugas() {
            alert('Cetak Surat Tugas button clicked!');
        }
    </script>
</body>

</html>