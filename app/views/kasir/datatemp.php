<table class="table table-striped table-sm table-bordered" style="width: 100%;">
    <thead class="table-light">
        <tr>
            <th style="text-align: right; font-size:16pt;">
                <?php $jmlitem = 0;
                $totalitem = 0;
                foreach ($data->result_array() as $rrr) : $jmlitem = $jmlitem + 1;
                    $totalitem = $totalitem + $rrr['jml'];
                ?>
                <?php endforeach; ?>
                <?= "Jml.Item : $jmlitem ($totalitem)"; ?>
            </th>
            <th colspan="9" style="text-align: right; font-size:18pt;">
                <?php $total_subtotal = 0;
                foreach ($data->result_array() as $rr) : $total_subtotal = $total_subtotal + $rr['subtotal']; ?>
                <?php endforeach; ?>
                <?= 'Rp. ' . number_format($total_subtotal, 2, ",", "."); ?>
            </th>
            <input type="hidden" value="<?= number_format($total_subtotal, 2, ".", ""); ?>" name="total_subtotal"
                id="total_subtotal">
        </tr>
        <tr style="font-size: 10pt;">
            <th style="width: 3%;">#</th>
            <th style="width: 10%;">Kode</th>
            <th style="width: 20%;">Item</th>
            <th style="width: 3%;">Qty</th>
            <th style="width: 3%;">Sat.</th>
            <th style="width: 10%; text-align: right;">Harga(Rp)</th>
            <th style="width: 3%;">Disc</th>
            <th style="width: 10%; text-align: right;">Sub.Total</th>
            <th style="width: 5%; text-align: right;">#</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 0;
        foreach ($data->result_array() as $row) :
            $nomor++;
        ?>
        <tr style="font-size: 10pt;">
            <td><?= $nomor; ?></td>
            <td><?= $row['kode']; ?></td>
            <td><?= $row['namaproduk']; ?></td>
            <td style="text-align: center;"><?= $row['jml']; ?></td>
            <td style="text-align: center;">
                <a href="#"
                    onclick="gantisatuan('<?= $row['kode']; ?>','<?= $row['id']; ?>','<?= $row['jml']; ?>')"><?= $row['namasatuan']; ?></a>
            </td>
            <td style="text-align: right;"><?= number_format($row['harga'], 0, ".", ",") ?></td>
            <td>
                <?php
                    if ($row['dispersen'] == '0') {
                        echo number_format($row['disuang'], 0, ".", ",");
                    } elseif ($row['disuang'] == '0') {
                        echo $row['dispersen'] . ' %';
                    } else {
                        echo '0';
                    }
                    ?>
            </td>
            <td style="text-align: right;">
                <?= number_format($row['subtotal'], 0, ".", ",") ?>
            </td>
            <td style="text-align: right;">
                <!-- <button type="button" class="btn btn-sm btn-outline-info">
                    <i class="fa fa-tags"></i>
                </button> -->
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusitem('<?= $row['id'] ?>')">
                    <i class="fa fa-trash-alt fa-sm"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
function gantisatuan(kode, id, jml) {
    $.ajax({
        type: "post",
        url: "<?= site_url('kasir/gantisatuan') ?>",
        data: {
            kode: kode,
            id: id,
            jualfaktur: $('#jualfaktur').val(),
            jml: jml
        },
        dataType: 'json',
        success: function(response) {
            if (response.sukses) {
                $('.viewmodalgantisatuan').html(response.sukses.tampilmodal).show();
                $('#modalgantisatuan').modal('show');
            } else {
                $.toast({
                    heading: 'Error',
                    text: response.error,
                    showHideTransition: 'slide',
                    icon: 'error',
                    position: 'top-center'
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function hapusitem(id) {
    Swal.fire({
        title: 'Hapus Item',
        text: "Yakin menghapus item ini",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "<?= site_url('kasir/hapusitem') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Sukses',
                            text: response.sukses,
                            showHideTransition: 'slide',
                            icon: 'success',
                            position: 'bottom-right'
                        });
                        tampildatatemppenjualan();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    })
}
</script>