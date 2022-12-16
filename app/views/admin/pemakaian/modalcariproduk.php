<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="modal fade bd-example-modal-lg" id="modalcariproduk" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Cari Data Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="aksi" id="aksi" value="<?= $aksi; ?>">
                <table class="table table-sm table-striped table-bordered display nowrap" id="dataproduk" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barcode</th>
                            <th>Nama Produk</th>
                            <th>Supplier</th>
                            <th>Satuan</th>
                            <th>Harga Jual (Rp)</th>
                            <th>Stok Tersedia</th>
                            <th>Aksi</th>
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
    table = $('#dataproduk').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('pemakaian/ambildataproduk') ?>",
            "type": "POST",
        },


        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [5],
                "className": 'text-right'
            },
            {
                "targets": [6],
                "className": 'text-right'
            },
            {
                "targets": [7],
                "className": 'text-right'
            }
        ],

    });

    $('[type="search"]').keydown(function(e) {
        if (e.keyCode == 9) {
            e.preventDefault();
            $('.btnpilih').focus();
        }
    });
});

function pilih(kode, namaproduk) {
    let aksi = $('#aksi').val();
    $('#kodebarcode').val(kode);
    $('#namaproduk').val(namaproduk);
    $('.namaproduk').html(namaproduk);
    $('#modalcariproduk').on('hidden.bs.modal', function(e) {
        if (aksi == 'insert') {
            temppemakaian();
        } else {
            pemakaiandetail();
        }
        // tampilforminputproduk();
    });
    $('#modalcariproduk').modal('hide');
}
</script>