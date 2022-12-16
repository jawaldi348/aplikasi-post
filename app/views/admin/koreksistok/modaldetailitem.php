<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="modal fade bd-example-modal-lg" id="modaldetailitem" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #02516e;">
                <h5 class="modal-title" id="exampleModalCenterTitle">Detail Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-sm table-striped table-bordered display nowrap" id="datadetailitem"
                    width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barcode</th>
                            <th>Nama Produk</th>
                            <th>Stok<br>Lalu</th>
                            <th>Stok<br>Sekarang</th>
                            <th>Alasan</th>
                            <th>Selisih</th>
                            <th>Harga Beli</th>
                            <th>Sub.Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nomor = 0;
                        foreach ($datadetail->result_array() as $r) :
                            $nomor++;
                            $subtotal = $r['selisih'] * $r['hargabeli'];
                            $subtotal_x = preg_replace(
                                '/(-)([\d\.\,]+)/ui',
                                '($2)',
                                number_format($subtotal, 2, ',', '.')
                            );
                        ?>
                        <tr>
                            <th><?= $nomor++; ?></th>
                            <td><?= $r['kode']; ?></td>
                            <td><?= $r['namaproduk']; ?></td>
                            <td><?= number_format($r['stoklalu'], 0, ",", "."); ?></td>
                            <td><?= number_format($r['stoksekarang'], 0, ",", "."); ?></td>
                            <td><?= $r['alasan']; ?></td>
                            <td><?= number_format($r['selisih'], 0, ",", "."); ?></td>
                            <td style="text-align: right;"><?= number_format($r['hargabeli'], 2, ",", "."); ?></td>
                            <td style="text-align: right;"><?= $subtotal_x; ?></td>
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
    table = $('#datadetailitem').DataTable({
        responsive: true,
    });
});
</script>