<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<link href="<?= base_url(); ?>assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="modal fade bd-example-modal-lg" id="modaldaftarproduk" tabindex="-1" role="dialog"
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
                <table class="table table-sm table-striped table-bordered display nowrap" id="dataproduk" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barcode</th>
                            <th>Nama Produk</th>
                            <th>Harga Beli (Rp)</th>
                            <th>Margin(%)</th>
                            <th>Harga Jual (Rp)</th>
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
            "url": "<?= site_url('beli/ambildatadaftarproduk') ?>",
            "type": "POST"
        },


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

function pilih(kode) {
    $('#kode').val(kode);
    $('#modaldaftarproduk').on('hidden.bs.modal', function(e) {
        $.ajax({
            type: "post",
            url: "<?= site_url('beli/ambildataproduk') ?>",
            data: {
                kode: kode
            },
            dataType: "json",
            success: function(response) {
                if (response.ada) {
                    let data = response.ada;
                    $('#kode').removeClass('is-invalid');
                    $('#kode').addClass('is-valid');
                    $('.errorKode').html('');

                    $('#namaproduk').removeClass('is-invalid');
                    $('#namaproduk').addClass('is-valid');
                    $('.errorNamaProduk').html('');

                    $('#kode').val(data.kode);
                    $('#namaproduk').val(data.namaproduk);
                    $('#hargabeli').val(data.hargabeli);
                    $('#hargajual').val(data.hargajual);
                    $('#margin').val(data.margin);
                    $('#namasatuan').val(data.namasatuan);
                    $('#idsatuan').val(data.idsatuan);
                    $('#qtysatuan').val(data.jmleceran);

                    $('#jml').focus();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
    $('#modaldaftarproduk').modal('hide');
}
</script>