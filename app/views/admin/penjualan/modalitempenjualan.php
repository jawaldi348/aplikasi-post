<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<!-- Modal -->
<div class="modal fade" id="modaldataitem" tabindex="-1" role="dialog" aria-labelledby="modaldataitem"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaldataitem">Item Penjualan Faktur <?= $faktur; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table style="font-size: 10pt;" class="table table-sm table-striped table-bordered display nowrap"
                    id="dataitem" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Return</th>
                            <th>Harga (Rp)</th>
                            <th>Diskon (Rp)</th>
                            <th>Sub.Total(Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nomor = 0;
                        foreach ($tampildata->result_array() as $r) :
                            $nomor++;
                        ?>
                        <tr>
                            <td><?= $nomor; ?></td>
                            <td><?= $r['detjualkodebarcode']; ?></td>
                            <td><?= $r['namaproduk']; ?></td>
                            <td><?= number_format($r['detjualjml'], 0); ?></td>
                            <td><?= number_format($r['detjualjmlreturn'], 0); ?></td>
                            <td><?= number_format($r['detjualharga'], 2, ".", ","); ?></td>
                            <td>
                                <?php
                                    if ($r['detjualdispersen'] != 0) {
                                        $diskonpersen = (($r['detjualjml'] - $r['detjualjmlreturn']) * $r['detjualharga']) * $r['detjualdispersen'] / 100;

                                        echo number_format($diskonpersen, 2, ".", ",");
                                    } else if ($r['detjualdisuang'] != 0) {
                                        echo number_format($r['detjualdisuang'], 0, ".", ",");
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                            </td>
                            <td><?= number_format($r['detjualsubtotal'], 2, ".", ","); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    table = $('#dataitem').DataTable({
        responsive: true,
        "processing": true,
        "columnDefs": [{
            "targets": [0],
            "orderable": false,
            "width": 3
        }]
    });
});
</script>