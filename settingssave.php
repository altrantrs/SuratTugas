<?php
session_start();
include_once("db_connection.php");

if(isset($_POST['submit'])) {
    $ttd = mysqli_real_escape_string($conn, $_POST['ttd']);
    $ppk = mysqli_real_escape_string($conn, $_POST['ppk']);
    $bendahara = mysqli_real_escape_string($conn, $_POST['bendahara']);
    $nama = mysqli_real_escape_string($conn, $_POST['namakabkota']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $biaya = mysqli_real_escape_string($conn, $_POST['biaya']);
    // Clear previous settings
    mysqli_query($conn, "DELETE FROM settings");

    // Insert new settings
    $query = "INSERT INTO settings (ttd, ppk, bendahara, namakabkota, alamat, url, biaya) 
              VALUES ('$ttd', '$ppk', '$bendahara', '$nama', '$alamat', '$url', '$biaya')";
    
    if(mysqli_query($conn, $query)) {
        header("Location: pengaturan.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
	// Debugging: Output captured form values
	echo "TTD: $ttd<br>";
	echo "PPK: $ppk<br>";
	echo "Bendahara: $bendahara<br>";
	echo "Nama: $nama<br>";
	echo "Alamat: $alamat<br>";
	echo "URL: $url<br>";
	echo "Biaya: $biaya<br>";
}
?>
