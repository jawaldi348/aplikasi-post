<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card border-light animated slideInUp">
        <div class="card-header">
            <button type="button" class="btn btn-warning btn-sm"
                onclick="location.href=('<?= site_url('admin/penjualan/index') ?>')">
                <i class="fa fa-fast-backward"></i> Kembali
            </button>
        </div>
        <div class="card-body">
            <table style="font-size: 10pt;" class="table table-sm table-striped table-bordered display nowrap"
                id="datapiutang" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Member</th>
                        <th>Nama Member</th>
                        <th>Total<br>Piutang(Rp)</th>
                        <th>Total<br>Bayar(Rp)</th>
                        <th>Sisa(Rp)</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function tampildatamemberpiutang() {
    table = $('#datapiutang').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('admin/penjualan/ambilDataDaftarPiutang') ?>",
            "type": "POST"
        },


        "columnDefs": [{
            "targets": [0],
            "orderable": false,
            "width": 3
        }, {
            "targets": [3, 4, 5],
            "className": "text-right",
        }]
    });
}

function detail(kodemember) {
    window.location = "<?= site_url('admin/penjualan/detail-piutang-pelanggan/') ?>" + kodemember;
}
$(document).ready(function() {
    tampildatamemberpiutang();
});
</script>