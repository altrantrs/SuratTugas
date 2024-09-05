<?php
session_start();
include_once("db_connection.php");
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$result = null;
$nip = $_SESSION["nip"];
if ($_SESSION['level'] == "Administrator") {
    // Ambil semua pegawai untuk administrator
    $result = mysqli_query($conn, "SELECT * FROM pegawai ORDER BY nama");
} else {
    // Ambil data pegawai untuk user biasa
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
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    include 'header.php';
    ?>

    <main>
        <div>
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
                        <ooption value="9">Oktober</option>
                        <option value="10">November</option>
                        <option value="11">Desember</option>
                    </select>
                </div>
            <?php if ($_SESSION['level'] == "Administrator") { ?>
                <!-- Bagian administrator -->
                <div class="calendar">
                    <div class="days" id="days-container">
                        <!-- Kalender akan di-generate di sini -->
                    </div>
                </div>
            <?php } else { ?>
                <!-- Bagian user biasa -->
                <div class="calendar">
                    <div class="days" id="days-container">
                        <!-- Kalender akan di-generate di sini untuk user biasa -->
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            generateCalendar(); // Panggil fungsi kalender saat halaman di-load
        });

        function generateCalendar() {
            const daysContainer = document.getElementById('days-container');
            daysContainer.innerHTML = ''; // Hapus hari sebelumnya

            const monthSelect = document.getElementById('month-select');
            const selectedMonth = parseInt(monthSelect.value);
            const year = new Date().getFullYear();
            const daysInMonth = getDaysInMonth(selectedMonth, year);

            // Fetch data dari server untuk kegiatan
            fetch(`get_activities.php?month=${selectedMonth+1}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    for (let day = 1; day <= daysInMonth; day++) {
                        const dayElement = document.createElement('div');
                        dayElement.className = 'day';
                        dayElement.textContent = day.toString().padStart(2, '0');

                        const currentDate = `${year}-${(selectedMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                        const activitiesForDate = data.filter(activity => activity.date === currentDate);

                        if (activitiesForDate.length > 0) {
                            activitiesForDate.forEach(activity => {
                                const activityInfo = document.createElement('div');
                                activityInfo.className = 'activity-info';
                                activityInfo.textContent = activity.nama; // Nama pegawai yang memiliki kegiatan

                                const link = document.createElement('a');
                                link.href = `tampil_kegiatan.php?date=${currentDate}`;
                                link.appendChild(activityInfo);

                                dayElement.appendChild(link);
                                dayElement.classList.add('icon-day');
                            });
                        }

                        daysContainer.appendChild(dayElement);
                    }
                })
                .catch(error => console.error("Error fetching activities:", error));
        }

        function getDaysInMonth(month, year) {
            return new Date(year, month + 1, 0).getDate(); // Mendapatkan jumlah hari dalam bulan
        }
    </script>
</body>

</html>
