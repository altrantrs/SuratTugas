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

// Mengambil data kegiatan dari database
$sql = "SELECT * FROM activities";
$result = $conn->query($sql);
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


    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <div class="container">
        <div class="title">
            <h1>Daftar Kegiatan</h1>
        </div>
        <div class="row">

            <div class="cari">
                <input type="text" name="keyword" id="keyword" placeholder="Masukkan keyword pencarian..." autocomplete="off">
            </div>
            <div class="tambah">
                <a href="kegiatan_tambah.php" class="hero-btn">Tambah Kegiatan</a>
            </div>

        </div>
        <main>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th width='1%'>No</th>
                        <th width='5%'>Fungsi</th>
                        <th width='5%'>Kode Kegiatan</th>
                        <th width='10%'>Kegiatan</th>
                        <th width='5%'>Nomor Surat</th>
                        <th width='5%'>Tanggal Surat</th>
                        <th width='5%'>Tujuan</th>
                        <th width='5%'>Jadwal</th>
                        <th width='7%'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $id = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $id++ . "</td>";
                            echo "<td>" . $row['fungsi'] . "</td>";
                            echo "<td>" . $row['kode_kegiatan'] . "</td>";
                            echo "<td>" . $row['activity'] . "</td>";
                            echo "<td>" . $row['nomor_surat'] . "</td>";
                            echo "<td>" . $row['tanggal_surat'] . "</td>";
                            echo "<td>" . $row['tujuan_kegiatan'] . "</td>";
                            echo "<td>" . $row['jadwal'] . "</td>";
                            echo "<td>";
                            echo "<a href='update_activity.php?id=" . $row['id'] . "' class='btn-edit'><i class='fas fa-edit'></i> Edit</a> ";
                            echo "<a href='#' class='btn-delete' onclick='deleteActivity(" . $row['id'] . "); return false;'><i class='fas fa-trash-alt'></i> Hapus</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>Tidak ada kegiatan yang tersedia.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
    <script>
        function deleteActivity(id) {
            if (confirm('Anda yakin ingin menghapus kegiatan ini?')) {
                fetch('hapus_kegiatan.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            id: id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            window.location.href = 'kegiatan.php'; // Refresh to see the change
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>