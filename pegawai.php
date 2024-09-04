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

// Mengambil data pegawai dari database
$sql = "SELECT * FROM pegawai";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pegawai BPS Kabupaten Wonogiri</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Feather Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

</head>

<body>
    <?php
    include 'header.php';
    ?>
    <div class="container">
        <div class="title">
            <h1>Daftar Pegawai</h1>
        </div>
        <div class="row">
            <div class="cari">
                <input type="text" name="keyword" id="keyword" placeholder="Masukkan keyword pencarian..." autocomplete="off">
            </div>
            <div class="tambah">
                <a href="pegawai_tambah.php" class="hero-btn">Tambah Pegawai</a>
            </div>
        </div>
        <main>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP Baru</th>
                        <th>NIP Lama</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Golongan</th>
                        <th>Pangkat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $id = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $id++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['nip']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nip_lama']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['jabatan']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['golongan']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['pangkat']) . "</td>";
                            echo "<td>";
                            echo "<a href='pegawai_ubah.php?id=" . $row['id'] . "' class='btn-edit'><i class='fas fa-edit'></i> Edit</a> ";
                            echo "<a href='#' class='btn-delete' onclick='deletePegawai(" . $row['id'] . "); return false;'><i class='fas fa-trash-alt'></i> Hapus</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>Tidak ada pegawai yang tersedia.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
    <script>
        function deletePegawai(id) {
            if (confirm('Anda yakin ingin menghapus pegawai ini?')) {
                fetch('pegawai_hapus.php', {
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
                            window.location.href = 'pegawai.php';
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