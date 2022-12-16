<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<link href="<?= base_url(); ?>assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-sm-12">
    <div class="card m-b-30">
        <div class="card-header">
            <button type="button" class="btn btn-outline-warning btn-sm"
                onclick="window.location.href=('<?= site_url('beli/index') ?>')">
                <i class="fa fa-backward"></i> Kembali</button>

            <button type="button" class="btn btn-outline-info btn-sm btnreloaddatahutang">
                <i class="fa fa-recycle"></i> Reload Data</button>
        </div>
        <div class="card-body">
            <div class="card-text">
                <table class="table table-sm table-bordered display nowrap" id="datahutang" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Faktur</th>
                            <th>Tgl.Faktur</th>
                            <th>Pemasok</th>
                            <th>Jatuh Tempo</th>
                            <th>Total Kotor(Rp.)</th>
                            <th>Total Bersih(Rp.)</th>
                            <th>Stt.Bayar</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function bayar(a) {
    url = "<?= site_url('beli/bayar-hutang/') ?>" + a;
    window.open(url, '_blank');
}

function editbayar(a) {
    window.location = "<?= site_url('beli/edit-bayar-hutang/') ?>" + a;
}

function tampil() {
    table = $('#datahutang').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('beli/ambildatahutang') ?>",
            "type": "POST"
        },
        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [5, 6, 8],
                "orderable": false,
            }
        ],

    });
}
$(document).ready(function() {
    tampil();
    $('.btnreloaddatahutang').click(function(e) {
        e.preventDefault();
        tampil();

    });
});
</script>