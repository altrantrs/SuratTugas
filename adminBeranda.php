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
                