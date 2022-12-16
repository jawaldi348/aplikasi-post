<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<!-- Modal -->
<div class="modal fade" id="modalsemuadatapenjualan" tabindex="-1" role="dialog"
    aria-labelledby="modalsemuadatapenjualan" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalsemuadatapenjualan">Semua Data Transaksi Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table style="font-size: 10pt;" class="table table-sm table-striped table-bordered display nowrap"
                    id="datatransaksi" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Faktur</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Stt.Bayar</th>
                            <th>Total (Rp)</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>

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
    table = $('#datatransaksi').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('admin/penjualan/return-ambildatapenjualan') ?>",
            "type": "POST"
        },


        "columnDefs": [{
            "targets": [0],
            "orderable": false,
            "width": 3
        }]
    });
});

function pilih(faktur, tgl, member) {
    $('#faktur').val(faktur);
    $('#tgl').val(tgl);
    $('#member').val(member);
    $('#modalsemuadatapenjualan').on('hidden.bs.modal', function(e) {
        tampildetailitempenjualan();
    });
    $('#modalsemuadatapenjualan').modal('hide');
}
</script>