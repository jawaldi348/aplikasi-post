<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="modal fade" id="modaldetailitem" tabindex="-1" role="dialog" aria-labelledby="modaldetailitemLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaldetailitemLabel">Detail Item <strong><?= $faktur; ?></strong></h5>
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
                            <th>Jumlah</th>
                            <th>Harga Beli(Rp)</th>
                            <th>Diskon</th>
                            <th>Harga Jual(Rp)</th>
                            <th>Sub.Total(Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nomor = 0;
                        foreach ($tampildetail->result_array() as $r) :
                            $nomor++;
                        ?>
                        <tr>
                            <td><?= $nomor; ?></td>
                            <td><?= $r['detkodebarcode']; ?></td>
                            <td><?= $r['namaproduk']; ?></td>
                            <td><?= number_format($r['detjml'], 0) . " $r[satnama]"; ?></td>
                            <td><?= number_format($r['dethrgbelikotor'], 2, ",", "."); ?></td>
                            <td>

                            </td>
                            <td><?= number_format($r['dethrgjual'], 2, ",", "."); ?></td>
                            <td><?= number_format($r['detsubtotal'], 2, ".", ","); ?></td>
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