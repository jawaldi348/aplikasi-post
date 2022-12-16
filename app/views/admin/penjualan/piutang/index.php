<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card border-light animated slideInRight">
        <div class="card-header">
            <button type="button" class="btn btn-warning btn-sm"
                onclick="location.href=('<?= site_url('admin/penjualan/index') ?>')">
                <i class="fa fa-fast-backward"></i> Kembali
            </button>
        </div>
        <div class="card-body">
            <div class="col-lg-12">
                <table style="font-size: 10pt;" class="table table-sm table-striped table-bordered display nowrap"
                    id="datapiutang" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Faktur</th>
                            <th>Tgl.Faktur</th>
                            <th>Tgl.Tempo</th>
                            <th>Member</th>
                            <th>Stt.Lunas</th>
                            <th>User Input</th>
                            <th>Jml.Item</th>
                            <th>Total (Rp)</th>
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
function tampildatahutang() {
    table = $('#datapiutang').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": './ambildata_piutang',
            "type": "POST"
        },


        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 3
            },
            // {
            //     "targets": [6],
            //     "className": "text-center",
            // }, {
            //     "targets": [7],
            //     "className": "text-right",
            // }, {
            //     "targets": [8],
            //     "width": 5
            // }
        ]
    });
}

function bayarpiutang(faktur) {
    window.location = "<?= site_url('admin/penjualan/bayar-piutang/') ?>" + faktur;
}
$(document).ready(function() {
    tampildatahutang();
});
</script>