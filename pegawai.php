<?php
session_start();
include_once("db_connection.php");
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

// Memeriksa apakah request datang dari AJAX
$isAjaxRequest = isset($_GET['ajax']) && $_GET['ajax'] == 1;

$nip = $_SESSION["nip"];
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

if ($_SESSION['level'] == "Administrator") {
    if ($keyword) {
        // Jika ada keyword pencarian
        $result = mysqli_query($conn, "SELECT * FROM pegawai WHERE nama LIKE '%$keyword%' OR nip LIKE '%$keyword%' OR jabatan LIKE '%$keyword%' ORDER BY nama");
    } else {
        // Jika tidak ada keyword pencarian
        $result = mysqli_query($conn, "SELECT * FROM pegawai ORDER BY nama");
    }
} else {
    // Jika user bukan Administrator, hanya menampilkan pegawai dengan NIP tertentu
    $result = mysqli_query($conn, "SELECT * FROM pegawai WHERE nip='$nip'");
}

// Jika ini adalah AJAX request, hanya return data pegawai
if ($isAjaxRequest) {
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
    exit(); // Berhenti di sini untuk AJAX request
}
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
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="title">
            <h1>Daftar Pegawai</h1>
        </div>
        <div class="row">
            <div class="cari">
                <input type="text" name="keyword" id="keyword" placeholder="Masukkan keyword pencarian..." autocomplete="off" onkeyup="searchPegawai()">
            </div>
            <div class="tambah">
                <a href="pegawai_tambah.php" class="hero-btn">Tambah Pegawai</a>
            </div>
        </div>
        <main>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th width='1%'>No</th>
                        <th width='10%'>NIP Baru</th>
                        <th width='10%'>NIP Lama</th>
                        <th width='10%'>Nama</th>
                        <th width='10%'>Jabatan</th>
                        <th width='10%'>Golongan</th>
                        <th width='10%'>Pangkat</th>
                        <th width='10%'>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pegawai-table">
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
        function searchPegawai() {
            const keyword = document.getElementById('keyword').value;
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('pegawai-table').innerHTML = xhr.responseText;
                }
            };
            xhr.open('GET', 'pegawai.php?keyword=' + encodeURIComponent(keyword) + '&ajax=1', true);
            xhr.send();
        }

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
