<?php
session_start();
//including the database connection file
include_once("db_connection.php");

if(isset($_POST['submit'])) {	
	$user=mysqli_real_escape_string($conn, $_POST['user']);
	$pass=mysqli_real_escape_string($conn, $_POST['pass']);

	$result = mysqli_query($conn, "SELECT * FROM pegawai WHERE username='$user' and password='$pass'"); 
	$res = mysqli_fetch_array($result);
	if($result->num_rows>0){
		$_SESSION["user"] = $res['nama'];
		$_SESSION["level"] = $res['level'];
        $_SESSION["nip"] = $res['nip'];
		header("Location: index.php");
	}
	else{
		header("Location: login.php");
	}
}
?>