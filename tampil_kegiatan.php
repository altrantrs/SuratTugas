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

$date = isset($_GET['date']) ? $_GET['date'] : '';

// Format tanggal dalam format yang diinginkan
$formattedDate = '';
if (!empty($date)) {
    $formattedDate = date('d F Y', strtotime($date));
}

$sql = "SELECT ad.activity_id as id_kegiatan, ad.date, ad.nomor_surat, ad.tanggal_surat, ad.tujuan_kegiatan, ad.jadwal, ad.pelaksana, a.activity
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
    <?php
    include 'header.php';
    ?>
    <div class="container">
        <main>
            <h2>Detail Kegiatan</h2>
            <section class="activity-details">
                <p><strong>Tanggal:</strong> <?= htmlspecialchars($formattedDate); ?></p>
                <p><strong>Kegiatan:</strong> <?= htmlspecialchars($activity['activity']); ?></p>
                <p><strong>Nomor Surat:</strong> <?= htmlspecialchars($activity['nomor_surat']); ?></p>
                <p><strong>Tanggal Surat:</strong> <?= htmlspecialchars(date('d F Y', strtotime($activity['tanggal_surat']))); ?></p>
                <p><strong>Tujuan Kegiatan:</strong> <?= htmlspecialchars($activity['tujuan_kegiatan']); ?></p>
                <p><strong>Jadwal:</strong> <?= htmlspecialchars($activity['jadwal']); ?></p>
                <!-- <p><strong>Pelaksana:</strong> <?= htmlspecialchars($activity['pelaksana']); ?></p> -->
            </section>

            <div class="buttons">
                <button class="btn btn-delete" onclick="deleteActivity()"> Hapus Kegiatan </button>
                <button class="btn btn-report" onclick="window.location.href='https://s.id/arBiniajaoneogrSinPrWPpdLo';">Cetak Laporan</button>
                <button class="btn btn-print"
                    onclick="printSuratTugas(
                        '<?= $activity['id_kegiatan'] ?>',
                        '<?= $nip ?>',
                        '<?= date('Y', strtotime($activity['date'])) ?>',
                        '<?= date('m', strtotime($activity['date'])) ?>',
                        '<?= date('d', strtotime($activity['date'])) ?>',
                        '<?= $activity['nomor_surat'] ?>',
                        '<?= $activity['tanggal_surat'] ?>',
                        '<?= $activity['tujuan_kegiatan'] ?>',
                        '<?= $activity['jadwal'] ?>'
                    );">
                    Cetak Surat Tugas
                </button>


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
                            window.location.href = 'index.php'; 
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

        function printSuratTugas(kegiatan, nip, tahun, bulan, tanggal, nosurat, tglsurat, tujuan, periode) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'laporan.php';

            form.appendChild(createInput('kegiatan', kegiatan));
            form.appendChild(createInput('nip', nip));
            form.appendChild(createInput('tahun', tahun));
            form.appendChild(createInput('bulan', bulan));
            form.appendChild(createInput('tanggal', tanggal));
            form.appendChild(createInput('nosurat', nosurat));
            form.appendChild(createInput('tglsurat', tglsurat));
            form.appendChild(createInput('tujuan', tujuan));
            form.appendChild(createInput('periode', periode));

            document.body.appendChild(form);
            form.submit();
        }

        function createInput(name, value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            return input;
        }
    </script>
<script>
    function calculateDateDifference(activityDate) {
        const currentDate = new Date();
        const dateParts = activityDate.split('-'); 
        const activityDateObj = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);

        const timeDifference = currentDate - activityDateObj;

        const dayDifference = Math.floor(timeDifference / (1000 * 3600 * 24));

        return dayDifference;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const activityDate = '<?= $activity["date"]; ?>';
        const dayDifference = calculateDateDifference(activityDate);

        const reportButton = document.querySelector('.btn.btn-report');

        if (dayDifference >= 10) {
            reportButton.disabled = true; 
            reportButton.style.cursor = 'not-allowed'
            reportButton.title = "Button disabled as it's been more than 10 days after the activity date.";
        }
    });
</script>

</body>

</html>
