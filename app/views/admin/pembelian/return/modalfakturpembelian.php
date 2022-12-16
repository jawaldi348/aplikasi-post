<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="modal fade bd-example-modal-xl" id="modalfakturpembelian" tabindex="-1" role="dialog"
    aria-labelledby="modalfakturpembelianLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalfakturpembelianLabel">Cari Faktur Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped display nowrap" id="datafakturpembelian"
                    style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Faktur</th>
                            <th>Tgl.Faktur</th>
                            <th>Pemasok</th>
                            <th>Jenis Pembayaran</th>
                            <th>Total Bersih(Rp.)</th>
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
function tampil() {
    table = $('#datafakturpembelian').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('beli/return_ambildatafakturpembelian') ?>",
            "type": "POST",
        },
        "columnDefs": [{
            "targets": [0],
            "orderable": false,
            "width": 5
        }],

    });
}

function pilih(faktur, tgl, id, nama) {
    $('#faktur').val(faktur);
    $('#tglbeli').val(tgl);
    $('#idpemasok').val(id);
    $('#namapemasok').val(nama);
    $('#modalfakturpembelian').on('hidden.bs.modal', function(e) {
        tampilkanitemfaktur();
    });
    $('#modalfakturpembelian').modal('hide');
}
$(document).ready(function() {
    tampil();
});
</script>