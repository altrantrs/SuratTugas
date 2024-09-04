<?php
include("config.php");
$id = $_GET['id'];
$result = mysqli_query($mysqli, "DELETE FROM activitiy_dates WHERE id='$id'");
?>
