<?php
session_start();
include_once("config.php");

// Redirect if user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$nip = $_SESSION["nip"];
$level = $_SESSION['level'];
$query = ($level == "Administrator") ? "SELECT * FROM pegawai ORDER BY nama" : "SELECT * FROM pegawai WHERE nip='$nip'";
$result = mysqli_query($mysqli, $query);

// Fetch settings
$settings = mysqli_query($mysqli, "SELECT * FROM settings"); 
$setting = mysqli_fetch_array($settings);
$jeniskabkota = $setting['jeniskabkota'];
$namakabkota = $setting['namakabkota'];
?>
<!doctype html>
<html lang=''>
<head>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <title>Translok</title>
    <link rel="stylesheet" href="select/jquery-ui.css">
    <link rel="stylesheet" href="select/chosen.min.css">
    <style>
        /* Your CSS Styles Here */
        body {font-family: Arial, Helvetica, sans-serif;}
        input[type=text], input[type=password], input[type=date], select {
            width: 100%;
            padding: 8px 20px;
            margin: 6px 2px;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 24px 20px;
            margin: 0 0 10px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        button:hover { opacity: 0.8; }
        .modal {
            display: block;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto 15% auto;
            border: 1px solid #888;
            width: 50%;
        }
        .close {
            position: absolute;
            right: 25px;
            top: 0;
            color: #000;
            font-size: 35px;
            font-weight: bold;
        }
        .close:hover { color: red; cursor: pointer; }
        .animate { -webkit-animation: animatezoom 0.6s; animation: animatezoom 0.6s; }
        @-webkit-keyframes animatezoom { from {-webkit-transform: scale(0)} to {-webkit-transform: scale(1)} }
        @keyframes animatezoom { from {transform: scale(0)} to {transform: scale(1)} }
    </style>
    <script src="select/jquery-ui.min.js"></script>
    <script src="select/chosen.jquery.min.js"></script>
</head>
<body onload="document.getElementById('id01').style.display='none';">

<h2>Translok BPS <?php echo $jeniskabkota . " " . $namakabkota; ?></h2>

<div id='cssmenu'>
    <ul>
        <li><a href='beranda.php'><font color=black><b>Alokasi Perjalanan</b></font></a></li>
        <?php if ($level == "Administrator"): ?>
            <li><a href='kegiatan.php'>Kegiatan</a></li>
            <li><a href='pegawai.php'>Pegawai</a></li>
            <li><a href='settings.php'>Pengaturan</a></li>
        <?php endif; ?>
        <li><a href='logout.php'>Logout - <?php echo $_SESSION["user"];?></a></li>
    </ul>
    <br>
    <b>Alokasi Perjalanan BPS <?php echo $jeniskabkota . " " . $namakabkota; ?></b>
    <br><br>
    <table>
        <tr style='color:#fff;'>
            <td align='center' bgcolor='#3db2e1'>Tahun</td>
            <td align='center' bgcolor='#3db2e1'>Bulan</td>
            <td align='center' bgcolor='#3db2e1'>Pegawai</td>
            <td align='center' rowspan='2'><button id="proses" onclick="cari();">Tampil</button></td>
            <td align='center' rowspan='2'><button id="excel" onclick="excel();">Excel</button></td>
        </tr>
        <tr>
            <td>
                <select id="tahun" name="tahun">
                    <option selected>--Pilih--</option>
                    <option value="2024">2024</option>
                </select>
            </td>
            <td>
                <select id="bulan" name="bulan">
                    <option selected>--Pilih--</option>
                    <!-- Options for months -->
                </select>
            </td>
            <td>
                <select id="nip" name="nip">
                    <option selected>--Pilih--</option>
                    <?php if ($level == "Administrator"): ?>
                        <option value="all">Semua Pegawai</option>
                    <?php endif; ?>
                    <?php while($res = mysqli_fetch_array($result)): ?>
                        <option value="<?php echo $res['nip']; ?>"><?php echo $res['nama']; ?></option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
    </table>
    <br>
    <span id="data"></span>
    <br>
    <ul></ul>
</div>

<div id="id01" class="modal">
    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
    <form id="savealokasi" name="savealokasi" class="modal-content">
        <div class="container">
            <h1>Alokasi Kegiatan</h1>
            <hr><br>
            <label for="tgl"><b>Tanggal Kegiatan</b></label>
            <input type="text" id="tgl" name="tgl" required>
            <label for="kegiatan"><b>Kegiatan</b></label>
            <select id="kegiatan" name="kegiatan" class="chosen">
                <option selected>--Pilih--</option>
                <?php 
                    $result = mysqli_query($mysqli, "SELECT * FROM translok_kegiatan ORDER BY fungsi"); 
                    while($res = mysqli_fetch_array($result)): ?>
                        <option value="<?php echo $res['id']; ?>"><?php echo $res['id'] . " - " . $res['fungsi'] . " - " . $res['kode_kegiatan'] . " - " . $res['nama_kegiatan']; ?></option>
                <?php endwhile; ?>
            </select>
            <label for="nosurat"><b>Nomor Surat</b></label>
            <input type="text" placeholder="Masukkan Nomor Surat" id="nosurat" name="nosurat" required>
            <label for="tglsurat"><b>Tanggal Surat</b></label>
            <input type="date" id="tglsurat" name="tglsurat" required>
            <label for="peg"><b>Pegawai</b></label>
            <select id="peg" name="peg" class="chosen" multiple>
                <?php while($res = mysqli_fetch_array($result)): ?>
                    <option value="<?php echo $res['nip']; ?>"><?php echo $res['nama']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="button" class="cancelbtn" onclick="document.getElementById('id01').style.display='none'">Cancel</button>
            <button type="submit" class="savebtn">Save</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $(".chosen").chosen();
        $('#tgl').datepicker({ dateFormat: 'yy-mm-dd' });
    });

    function cari() {
	var t = document.getElementById("tahun").value;
	var b = document.getElementById("bulan").value;
	var n = document.getElementById("nip").value;
	
	if (t=="--Pilih--" || b=="--Pilih--" ||n=="--Pilih--"){
		alert ("Isian tahun, bulan dan pegawai harus diisi.");
	}
	else{

		document.savealokasi.nosurat.focus();
		
		var tahun = document.getElementById('tahun').value;
		var bulan = document.getElementById('bulan').value;
		var nip = document.getElementById('nip').value;
		
		
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		  document.getElementById("data").innerHTML = this.responseText;
		}
		};
		document.getElementById("data").innerHTML = "<center><img src='images/loading.gif'/></center>";
		xhttp.open("GET", "showmatriks.php?tahun="+tahun+"&bulan="+bulan+"&nip="+nip, true);
		xhttp.send();   
	}
}
function excel() {
	var t = document.getElementById("tahun").value;
	var b = document.getElementById("bulan").value;
	var n = document.getElementById("nip").value;
	
	if (t=="--Pilih--" || b=="--Pilih--" ||n=="--Pilih--"){
		alert ("Isian tahun, bulan dan pegawai harus diisi.");
	}
	else{

		document.savealokasi.nosurat.focus();
		
		var tahun = document.getElementById('tahun').value;
		var bulan = document.getElementById('bulan').value;
		var nip = document.getElementById('nip').value;
		
		
		location.href="showexcel.php?tahun="+tahun+"&bulan="+bulan+"&nip="+nip;
	}
}
</script>
</body>
</html>
