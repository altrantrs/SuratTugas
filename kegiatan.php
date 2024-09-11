<?php
session_start();
include_once("db_connection.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$nip = $_SESSION["nip"];
$isAjaxRequest = isset($_GET['ajax']) && $_GET['ajax'] == 1;
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

if ($_SESSION['level'] == "Administrator") {
    if ($keyword) {
        // Pencarian dengan keyword
        $stmt = $conn->prepare("SELECT * FROM activities WHERE activity LIKE ? OR kode_kegiatan LIKE ? OR tujuan_kegiatan LIKE ? ORDER BY activity");
        $searchTerm = '%' . $keyword . '%';
        $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // Menampilkan seluruh kegiatan jika tidak ada keyword
        $result = $conn->query("SELECT * FROM activities ORDER BY activity");
    }
} else {
    // User bukan Administrator
    $result = $conn->query("SELECT * FROM activities WHERE nip='$nip'");
}

// Jika request AJAX, hanya kembalikan tabel kegiatan
if ($isAjaxRequest) {
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
            echo "<a href='kegiatan_ubah.php?id=" . $row['id'] . "' class='btn-edit'><i class='fas fa-edit'></i> Edit</a> ";
            echo "<a href='#' class='btn-delete' onclick='deleteActivity(" . $row['id'] . "); return false;'><i class='fas fa-trash-alt'></i> Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>Tidak ada kegiatan yang tersedia.</td></tr>";
    }
    exit();
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


    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="title">
            <h1>Daftar Kegiatan</h1>
        </div>
        <div class="row">
            <div class="cari">
                <input type="text" name="keyword" id="keyword" placeholder="Masukkan keyword pencarian..." autocomplete="off" onkeyup="searchKegiatan()">
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
                        <th width='10%'>Fungsi</th>
                        <th width='10%'>Kode Kegiatan</th>
                        <th width='10%'>Kegiatan</th>
                        <th width='10%'>Nomor Surat</th>
                        <th width='10%'>Tanggal Surat</th>
                        <th width='10%'>Tujuan</th>
                        <th width='10%'>Jadwal</th>
                        <th width='10%'>Aksi</th>
                    </tr>
                </thead>
                <tbody id="kegiatan-table">
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
                            echo "<a href='kegiatan_ubah.php?id=" . $row['id'] . "' class='btn-edit'><i class='fas fa-edit'></i> Edit</a> ";
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
        // Fungsi pencarian kegiatan
        function searchKegiatan() {
            const keyword = document.getElementById('keyword').value;
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('kegiatan-table').innerHTML = xhr.responseText;
                }
            };
            xhr.open('GET', 'kegiatan.php?keyword=' + encodeURIComponent(keyword) + '&ajax=1', true);
            xhr.send();
        }

        function deleteActivity(id) {
            if (confirm('Anda yakin ingin menghapus kegiatan ini?')) {
                fetch('kegiatan_hapus.php', {
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
                            window.location.href = 'kegiatan.php';
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
