<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30 card-body text-center">
        <h4 class="card-title font-20 mt-0">Detail Item Penjualan</h4>
        <p class="card-text">
        <table class="table table-sm table-striped" style="font-size:11pt; width: 100%;" id="datadetail">
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 20%;">Kode</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Jml.Return <i class="fa fa-undo-alt" style="font-size: 12px;"></i></th>
                    <th>Harga (Rp)</th>
                    <th>Sub.Total(Rp)</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                <?php $nomor = 0;
                    foreach ($datadetail->result_array() as $r) : $nomor++; ?>
                <tr>
                    <td><?= $nomor; ?></td>
                    <td><?= $r['kodebarcode']; ?></td>
                    <td><?= $r['namaproduk']; ?></td>
                    <td style="text-align: right;"><?= $r['detjualjml'] . ' ' . $r['satnama']; ?></td>
                    <td style="text-align: center;"><?= $r['detjualjmlreturn']; ?></td>
                    <td style="text-align: right;"><?= number_format($r['detjualharga'], 2, ".", ","); ?></td>
                    <td style="text-align: right;"><?= number_format($r['detjualsubtotal'], 2, ".", ","); ?></td>
                    <td>
                        <button title="Return Produk ini" type="button" class="btn btn-sm btn-primary"
                            onclick="returnjual('<?= $r['detjualid'] ?>','<?= $r['detjualfaktur'] ?>')">
                            <i class="fa fa-exchange-alt"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </p>
    </div>
</div>
<script>
$(document).ready(function() {
    var table = $('#datadetail').DataTable({
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,

    });
});

function returnjual(id, faktur) {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/penjualan/return_produk') ?>",
        data: {
            id: id,
            faktur: faktur
        },
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodalreturn').html(response.data).show();
                $('#modalreturnproduk').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
</script>