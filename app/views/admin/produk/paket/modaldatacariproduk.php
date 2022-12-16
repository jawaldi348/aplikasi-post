<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="modal fade" id="modaldataproduk" tabindex="-1" role="dialog" aria-labelledby="modaldataprodukLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaldataprodukLabel">Data Produ dengan keyword
                    <strong><?= $kode; ?></strong>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped table-bordered display nowrap" id="datacariproduk"
                    width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barcode</th>
                            <th>Nama Produk</th>
                            <th>Harga Modal (Rp)</th>
                            <th>Harga Jual (Rp)</th>
                            <th>Stok Tersedia</th>
                            <th>Aksi</th>
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
                            <td><?= $r['kodebarcode']; ?></td>
                            <td><?= $r['namaproduk']; ?></td>
                            <td><?= number_format($r['harga_beli_eceran'], 2, ",", "."); ?></td>
                            <td><?= number_format($r['harga_jual_eceran'], 2, ",", "."); ?></td>
                            <td><?= number_format($r['stok_tersedia'], 0, ",", "."); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info"
                                    onclick="pilih('<?= $r['kodebarcode'] ?>','<?= $r['namaproduk'] ?>')">
                                    <i class="fa fa-hand-point-up"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    table = $('#datacariproduk').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "order": [],
        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [4],
                "className": 'text-right'
            },
            {
                "targets": [5],
                "className": 'text-right'
            },
            {
                "targets": [6],
                "className": 'text-right'
            }
        ],

    });
});

function pilih(kode, nama) {
    $('#kodeproduk').val(nama);
    // $('#namaproduk').val(nama);
    $('#modaldataproduk').on('hidden.bs.modal', function(e) {
        $('#kodeproduk').focus();
    });
    $('#modaldataproduk').modal('hide');
}
</script>