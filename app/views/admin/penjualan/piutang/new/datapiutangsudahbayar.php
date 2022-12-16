<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="alert alert-success">
        <strong>Daftar Faktur Sudah Di-Bayar</strong>
    </div>
</div>

<div class="col-lg-12">
    <input type="hidden" name="memberx" id="memberx" value="<?= $kodememberx; ?>">
    <table style="font-size: 10pt;" class="table table-sm table-striped table-bordered display nowrap"
        id="datapiutangsudahbayar" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Faktur</th>
                <th>Tanggal</th>
                <th>Total Belanja(Rp)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>
function tampildatapiutangsudahbayar() {
    table = $('#datapiutangsudahbayar').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('admin/penjualan/ambildata_PiutangSudahBayar') ?>",
            "type": "POST",
            "data": {
                kodemember: $('#memberx').val()
            }
        },


        "columnDefs": [

            {
                "targets": [0],
                "orderable": false,
                "width": 5
            },
            // {
            //     "targets": [5],
            //     "className": 'text-right'
            // },
            // {
            //     "targets": [6],
            //     "className": 'text-right'
            // },
            // {
            //     "targets": [8],
            //     "className": 'text-right'
            // }
        ],
    });
}
$(document).ready(function() {
    tampildatapiutangsudahbayar();
});
</script>