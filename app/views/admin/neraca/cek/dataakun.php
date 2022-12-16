<table class="table table-sm table-striped">
    <thead>
        <tr>
            <th>No.Akun</th>
            <th>Nama Akun</th>
            <th style="text-align: center;">Awal</th>
            <th style="text-align: center;">Akhir</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tampildata->result_array() as $row) : $noakun = $row['noakun']; ?>
        <tr>
            <td><?= $row['noakun']; ?></td>
            <td>
                <?php
                    if ($row['kat'] == '0') {
                        echo "<strong>$row[namaakun]</strong>";
                    } else {
                    ?>
                <a href="#"
                    onclick="lihatdetail('<?= $row['noakun'] ?>')"><?= $row['namaakun']; ?></a>&nbsp;&nbsp;&nbsp;
                <span style="cursor: pointer;" onclick="tambahdata('<?= $row['noakun'] ?>')"><i
                        class="fa fa-plus-circle" style="color: blue;"></i></span>
                <?php
                    }
                    ?>
            </td>
            <td style="text-align: right;">
                <?php
                    if ($row['kat'] == '1') {
                        if ($row['noakun'] == '3-100') {
                            echo '<span class="badge badge-info">Auto</span>';
                        } else {
                            $query_awal_lagi = $this->db->query("SELECT 
                                    CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                    CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                    FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun' AND DATE_FORMAT(transtgl,'%Y-%m') < '$bulan' ORDER BY transtgl ASC");

                                if ($query_awal_lagi->num_rows() > 0) {
                                    $saldo_akhir_lama = 0;
                                    foreach ($query_awal_lagi->result_array() as $yyy) :
                                        $saldo_akhir_lama = ($saldo_akhir_lama + $yyy['masuk']) - $yyy['keluar'];
                                    endforeach;
                                } else {
                                    $saldo_akhir_lama = 0;
                                }
                                echo number_format($saldo_akhir_lama, 0, ".", ",");
                        }
                    } else {
                        echo '';
                    }
                    ?>
            </td>
            <td style="text-align: right;">
                <?php
                    $query_akhir = $this->db->query("SELECT 
                    CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                    CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                    FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun' AND DATE_FORMAT(transtgl,'%Y-%m') = '$bulan' ORDER BY transtgl ASC");

                    if ($query_akhir->num_rows() > 0) {
                        $saldo_akhir = 0;
                        foreach ($query_akhir->result_array() as $akhir) :
                            $saldo_akhir = ($saldo_akhir + $akhir['masuk']) - $akhir['keluar'];
                        endforeach;
                    } else {
                        $saldo_akhir = 0;
                    }


                    if ($row['kat'] == '1') {
                        if ($row['noakun'] == '3-100') {
                            echo '<span class="badge badge-info">Auto</span>';
                        } else {
                            $saldo_akhir = $saldo_akhir + $saldo_akhir_lama;
                            echo number_format($saldo_akhir, 0, ".", ",");
                        }
                    } else {
                        echo '';
                    }
                    ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
function tambahdata(no) {
    alert(no);
}

function lihatdetail(no) {
    $.ajax({
        type: "post",
        url: "<?= site_url('neraca/lihatdata-akun') ?>",
        data: {
            noakun: no
        },
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodal').html(response.data).show();
                $('#modaldetaildataakun').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function tambahdata(no) {
    $.ajax({
        type: "post",
        url: "<?= site_url('neraca/tambahdataakun') ?>",
        data: {
            noakun: no
        },
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodal').html(response.data).show();
                $('#modaltambahdataakun').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
</script>