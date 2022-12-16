<!-- DataTables -->
<link href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<link href="<?= base_url() ?>assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<div class="col-sm-12">
    <table class="table table-bordered table-striped table-sm" id="datadetail" style="font-size: 11pt;width:100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Barang</th>
                <th style="width:10%;">Expired Date</th>
                <th style="width:5%;">Jml.Beli</th>
                <th style="width:5%;">Satuan</th>
                <th style="width:5%;">Jml.Return</th>
                <th style="width:10%;">Harga Beli(Rp)</th>
                <th style="width:15%;">Sub.Total(Rp)</th>
                <th style="width:10%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $nomor = 0;
            $total_subtotal = 0;
            if (count($datadetail) > 0) :
                foreach ($datadetail as $d) : $nomor++;
                    $total_subtotal = $total_subtotal + $d->detsubtotal; ?>
            <tr>
                <td><?= $nomor; ?></td>
                <td><?= $d->detkodebarcode; ?></td>
                <td><?= $d->namaproduk; ?></td>
                <td><?= $d->dettglexpired; ?></td>
                <td><?= $d->detjml; ?></td>
                <td><?= $d->satnama; ?></td>
                <td><?= $d->detjmlreturn; ?></td>
                <td style="text-align: right;"><?= number_format($d->dethrgbeli, 2, ".", ","); ?></td>
                <td style="text-align: right;"><?= number_format($d->detsubtotal, 2, ".", ","); ?></td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-sm btn-outline-success"
                        onclick="kembalikan('<?= $d->detid ?>')" title="Return Produk" onclick="">
                        <i class="fa fa-exchange-alt"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
$(document).ready(function() {
    $('#datadetail').DataTable();
});

function kembalikan(id) {
    $.ajax({
        type: "post",
        url: "<?= site_url('beli/detailReturnProduk') ?>",
        data: {
            id: id
        },
        dataType: "json",
        success: function(response) {
            $('.viewmodal').html(response.data).show();
            // $('#modalreturn').on('shown.bs.modal', function(e) {
            //     $('.modal .modal-dialog').attr('class', 'modal-dialog  ' + $(this).data(
            //         "animation") + '  animated');
            // });
            $('#modalreturn').modal({
                keyboard: false
            });
            $('#modalreturn').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
</script>