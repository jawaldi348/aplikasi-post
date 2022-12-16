<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30 animated fadeInDownBig">
        <div class="card-header bg-default">
            Daftar Hutang Dari Pembelian Yang Belum di Bayarkan <button type="button"
                onclick="window.location.href=('<?= site_url('admin/pembelian/view') ?>')"
                class="btn btn-sm btn-warning">Kembali</button>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-bordered display nowrap" id="datahutang" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Faktur</th>
                        <th>Tgl.Faktur</th>
                        <th>Tgl.Jatuh Tempo</th>
                        <th>Total Pembayaran</th>
                        <th>Jumlah Item</th>
                        <th>Tgl.Pembayaran</th>
                        <th>Status Pembayaran</th>
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
$(document).ready(function() {
    table = $('#datahutang').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": './ambildatahutang',
            "type": "POST"
        },


        "columnDefs": [{
            "targets": [0],
            "orderable": false,
            "width": 5
        }],

    });
});

function bayar(faktur) {
    window.location.href = ("<?= site_url('admin/hutang/bayar/') ?>") + faktur;
}
</script>