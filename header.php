<header>
        <h1>Surat Tugas BPS Kabupaten Wonogiri</h1>
        <div class="user-profile">
            <span><a href='logout.php'>Logout - <?php echo $_SESSION["user"]; ?></a></span>
        </div>
    </header>
    
<nav>
    <ul>
        <li><a href="index.php">Alokasi Kegiatan</a></li>
        <?php
        if ($_SESSION['level'] == "Administrator") {
            echo "<li><a href='kegiatan.php'>Kegiatan</a></li>";
            echo "<li><a href='pegawai.php'>Pegawai</a></li>";
            echo "<li><a href='pengaturan.php'>Pengaturan</a></li>";
        }
        ?>
    </ul>
</nav>