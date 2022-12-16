<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-warning"
                onclick="window.location='<?= site_url('admin/produk/home') ?>'">
                &laquo; Kembali
            </button>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-bordered display nowrap" id="dataproduk" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Produk</th>
                        <th>Satuan</th>
                        <th>Harga Beli (Rp)</th>
                        <th>Harga Jual (Rp)</th>
                        <th>Stok Tersedia</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function showDetail(kode) {
    window.location.href = ("<?= site_url('stokproduk/detailproduk/') ?>" + kode);
}

function tampildata() {
    table = $('#dataproduk').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('stokproduk/ambildataproduk') ?>",
            "type": "POST"
        },


        "columnDefs": [{
            "targets": [0],
            "orderable": false,
            "width": 5
        }, {
            "targets": [4, 5, 6],
            "className": 'text-right',
            "orderable": false,
        }],

    });
}
$(document).ready(function() {
    tampildata();
});
</script>