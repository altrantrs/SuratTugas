<?php
session_start();
include_once("db_connection.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$nip = $_SESSION["nip"];
$date = isset($_GET['date']) ? $_GET['date'] : '';

if (empty($date)) {
    echo "Tanggal tidak ditemukan!";
    exit();
}

// Format tanggal dalam format yang diinginkan
$formattedDate = date('d F Y', strtotime($date));

// Query untuk mengambil detail kegiatan berdasarkan tanggal dan created_by
$sql = "SELECT ad.id as id_activity_date, ad.date, ad.nomor_surat, ad.tanggal_surat, ad.tujuan_kegiatan, ad.jadwal, a.activity, p.nama as pelaksana 
        FROM activity_dates ad
        JOIN activities a ON ad.activity_id = a.id
        JOIN pegawai p ON ad.created_by = p.nip
        WHERE ad.date = '$date' AND ad.id = '$id'";



$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $activity = $result->fetch_assoc();
    echo "<pre>";
print_r($activity);
echo "</pre>";

} else {
    echo "Tidak ada kegiatan yang ditemukan untuk tanggal ini!";
    exit();
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
    <p><strong>Pelaksana:</strong> <?= htmlspecialchars($activity['pelaksana']); ?></p> <!-- Display the executor's name -->
</section>


            <div class="buttons">
                <button class="btn btn-delete" onclick="deleteActivity()"> Hapus Kegiatan </button>
                <button class="btn btn-report" onclick="window.location.href='https://s.id/laporanjadi';">Cetak Laporan</button>
                <button class="btn btn-print"
                    onclick="printSuratTugas(
                        '<?= $activity['id_kegiatan'] ?>',  // Menggunakan id_kegiatan
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
                            window.location.href = 'index.php'; // Redirect to index.php after deletion
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function printSuratTugas(kegiatan, nip, tahun, bulan, tanggal, nosurat, tglsurat, tujuan, periode) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'coba.php';

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
</body>

</html>
