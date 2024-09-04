<table>
                    <tr style='color:#fff;'>
                        <td align='center' bgcolor='#3db2e1'>Tahun</td>
                        <td align='center' bgcolor='#3db2e1'>Bulan</td>
                        <td align='center' bgcolor='#3db2e1'>Pegawai</td>
                        <td align='center' rowspan='2'><button id="proses" name="proses" onclick="cari();">Tampil</button></td>
                        <td align='center' rowspan='2'><button id="excel" name="excel" onclick="excel();">Excel</button></td>
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
                                <option value="01">Januari</option>
                                <option value="1">Februari</option>
                                <option value="2">Maret</option>
                                <option value="3">April</option>
                                <option value="4">Mei</option>
                                <option value="5">Juni</option>
                                <option value="6">Juli</option>
                                <option value="7">Agustus</option>
                                <option value="8">September</option>
                                <option value="9">Oktober</option>
                                <option value="10">November</option>
                                <option value="11">Desember</option>
                            </select>
                        </td>
                        <td>
                            <select id="nip" name="nip">
                                <option selected>--Pilih--</option>
                                <?php
                                if ($_SESSION['level'] == "Administrator") {
                                    echo '<option value="all">Semua Pegawai</option>';
                                }
                                while ($res = mysqli_fetch_array($result)) {
                                    echo "<option value=" . $res['nip'] . ">" . $res['nama'] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <br>
                <span id="data"></span>
                <br>
                <ul>.</ul>

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
                        while ($res = mysqli_fetch_array($result)) {
                            echo "<option value=" . $res['id'] . ">" . $res['id'] . " - " . $res['fungsi'] . " - " . $res['kode_kegiatan'] . " - " . $res['nama_kegiatan'] . "</option>";
                        }

                        ?>
                    </select>
                    <label for="nosurat"><b>Nomor Surat</b></label>
                    <input type="text" placeholder="Masukkan Nomor Surat" id="nosurat" name="nosurat" required>
                    <label for="tglsurat"><b>Tanggal Surat</b></label>
                    <input type="date" id="tglsurat" name='tglsurat' size='9' value="" />
                    <label for="tujuan"><b>Tempat Tujuan</b></label>
                    <input type="text" placeholder="Masukkan Tempat Tujuan" id="tujuan" name="tujuan" required>
                    <label for="jabatan"><b>Jabatan</b></label>
                    <input type="text" placeholder="Masukkan apakah sebagai pengawas, pencacah, dll" id="jabatan" name="jabatan" required>
                    <label for="periode"><b>Jangka Waktu</b></label>
                    <input type="text" placeholder="Masukkan Jangka Waktu" id="periode" name="periode" required>


                    <input type="hidden" id="nipx" name="nipx" value="">
                    <input type="hidden" id="tglx" name="tglx" value="">
                    <input type="hidden" id="blnx" name="blnx" value="">
                    <input type="hidden" id="thnx" name="thnx" value="">
                    <input type="hidden" id="edit" name="edit" value="">
                    <input type="hidden" id="status" name="status" value="">

                    <div class="clearfix">
                        <button type="submit" name="submit" class="signupbtn" onclick="save('');">Simpan</button>

                        <button type="button" id="cancel" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Tutup</button>
                        <button type="submit" id="selesai" name="selesai" class="signupbtn" onclick="save('1');">Selesai Perjalanan</button>
                        <button type="submit" id="batalselesai" name="batalselesai" class="signupbtn" onclick="save('');" style="background:red;">Batalkan Selesai Perjalanan</button>
                    </div>
                </div>
            </form>
        </div>
        <script>
            $(".chosen").chosen();

            document.getElementById('id01').style.display = 'none';
            document.getElementById('selesai').style.display = 'none';
            document.getElementById('batalselesai').style.display = 'none';

            function save(stt) {
                var nosurat = document.getElementById("nosurat").value;
                var tglsurat = document.getElementById("tglsurat").value;
                var nipx = document.getElementById("nipx").value;
                var kegiatan = document.getElementById("kegiatan").value;
                var jabatan = document.getElementById("jabatan").value;
                var periode = document.getElementById("periode").value;
                var thnx = document.getElementById('thnx').value;
                var blnx = document.getElementById('blnx').value;
                var tglx = document.getElementById('tglx').value;
                var tujuan = document.getElementById('tujuan').value;

                var status = stt;
                var edit = document.getElementById('edit').value;

                var xhttp;
                xhttp = new XMLHttpRequest();
                document.getElementById("data").innerHTML = "<center><img src='images/loading.gif'/></center>";
                xhttp.open("GET", "berandasave.php?nosurat=" + nosurat + "&tglsurat=" + tglsurat + "&nipx=" + nipx + "&kegiatan=" + kegiatan + "&jabatan=" + jabatan + "&periode=" + periode + "&tglx=" + tglx + "&blnx=" + blnx + "&thnx=" + thnx + "&tujuan=" + tujuan + "&status=" + status + "&edit=" + edit, true);
                xhttp.send();

                showtemp();
                document.getElementById('proses').click();
                document.getElementById('cancel').click();

            }

            function del(id) {

                var xhttp;
                xhttp = new XMLHttpRequest();
                xhttp.open("GET", "berandadelete.php?id=" + id, true);
                xhttp.send();
                showtemp();
                document.getElementById('proses').click();
                document.getElementById('cancel').click();

            }

            function cari() {
                var t = document.getElementById("tahun").value;
                var b = document.getElementById("bulan").value;
                var n = document.getElementById("nip").value;

                if (t == "--Pilih--" || b == "--Pilih--" || n == "--Pilih--") {
                    alert("Isian tahun, bulan dan pegawai harus diisi.");
                } else {

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
                    xhttp.open("GET", "showmatriks.php?tahun=" + tahun + "&bulan=" + bulan + "&nip=" + nip, true);
                    xhttp.send();
                }
            }

            function excel() {
                var t = document.getElementById("tahun").value;
                var b = document.getElementById("bulan").value;
                var n = document.getElementById("nip").value;

                if (t == "--Pilih--" || b == "--Pilih--" || n == "--Pilih--") {
                    alert("Isian tahun, bulan dan pegawai harus diisi.");
                } else {

                    document.savealokasi.nosurat.focus();

                    var tahun = document.getElementById('tahun').value;
                    var bulan = document.getElementById('bulan').value;
                    var nip = document.getElementById('nip').value;


                    location.href = "showexcel.php?tahun=" + tahun + "&bulan=" + bulan + "&nip=" + nip;
                }
            }

            function laporan(thn, bln, tgl, keg, n, nosurat, tglsurat, tujuan, periode) {

                var tahun = thn;
                var bulan = bln;
                var tanggal = tgl;
                var kegiatan = keg;
                var nip = n;
                var ns = nosurat;
                var ts = tglsurat;
                var t = tujuan;
                var p = periode;

                location.href = "laporan.php?tahun=" + tahun + "&bulan=" + bulan + "&tanggal=" + tanggal + "&kegiatan=" + kegiatan + "&nip=" + nip + "&nosurat=" + ns + "&tglsurat=" + ts + "&tujuan=" + t + "&periode=" + p;

            }

            function showtemp() {
                document.getElementById("tgl").value = "";
                //document.savealokasi.nosurat.focus();

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
                xhttp.open("GET", "showmatriks.php?tahun=" + tahun + "&bulan=" + bulan + "&nip=" + nip, true);
                xhttp.send();

            }

            function isi(nip, tgl, bln, thn) {
                document.getElementById('selesai').style.display = 'none';
                document.getElementById('batalselesai').style.display = 'none';
                document.getElementById("nipx").value = nip;
                document.getElementById("tgl").value = tgl + " " + bulan(bln) + " " + thn;
                document.getElementById("tglx").value = tgl;
                document.getElementById("blnx").value = bln;
                document.getElementById("thnx").value = thn;
                document.getElementById("edit").value = "";
                document.savealokasi.nosurat.focus();
            }

            function edit(nip, tgl, bln, thn, jab, keg, nosurat, tglsurat, tujuan, periode, stt, x) {
                var tglsurat = tglsurat.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, '-');
                document.getElementById("nipx").value = nip;
                document.getElementById("tgl").value = tgl + " " + bulan(bln) + " " + thn;
                document.getElementById("tglx").value = tgl;
                document.getElementById("blnx").value = bln;
                document.getElementById("thnx").value = thn;
                document.getElementById("jabatan").value = jab;
                document.getElementById("nosurat").value = nosurat;
                document.getElementById("tglsurat").value = tglsurat;
                document.getElementById("tujuan").value = tujuan;
                document.getElementById("periode").value = periode;


                if (stt == "1") {
                    document.getElementById('batalselesai').style.display = 'block';
                    document.getElementById('selesai').style.display = 'none';
                } else {
                    document.getElementById('selesai').style.display = 'block';
                    document.getElementById('batalselesai').style.display = 'none';
                }

                document.getElementById("edit").value = x;

                $('#kegiatan').val(keg).trigger('chosen:updated');
                document.savealokasi.nosurat.focus();
            }


            function kosong() {
                document.getElementById("kegiatan").value = "--Pilih--";
                document.getElementById("nosurat").value = "";
                document.getElementById("tglsurat").value = "";
                document.getElementById("tujuan").value = "";
                document.getElementById("periode").value = "";
            }

            function bulan(bln) {
                if (bln == "01") {
                    return "Januari";
                }
                if (bln == "02") {
                    return "Februari";
                }
                if (bln == "03") {
                    return "Maret";
                }
                if (bln == "04") {
                    return "April";
                }
                if (bln == "05") {
                    return "Mei";
                }
                if (bln == "06") {
                    return "Juni";
                }
                if (bln == "07") {
                    return "Juli";
                }
                if (bln == "08") {
                    return "Agustus";
                }
                if (bln == "09") {
                    return "September";
                }
                if (bln == "10") {
                    return "Oktober";
                }
                if (bln == "11") {
                    return "November";
                }
                if (bln == "12") {
                    return "Desember";
                }
            }
        </script>