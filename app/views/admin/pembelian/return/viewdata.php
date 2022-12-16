<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<link href="<?= base_url(); ?>assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<table class="table table-sm table-striped display nowrap" id="ambildatareturn" style="width: 100%;">
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl.Return</th>
            <th>Faktur</th>
            <th>Pemasok</th>
            <th>Kode/Produk</th>
            <th>Jml.Return</th>
            <th>Status</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
function tampil() {
    table = $('#ambildatareturn').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('beli/return_ambildata') ?>",
            "type": "POST"
        },
        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 5
            },
            // {
            //     "targets": [5, 6, 8],
            //     "orderable": false,
            // }
        ],

    });
}
$(document).ready(function() {
    tampil();
});

function hapusreturn(id, faktur) {
    Swal.fire({
        title: 'Hapus Data Return',
        html: `Yakin di hapus ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus !',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "<?= site_url('beli/return_hapusitem') ?>",
                data: {
                    id: id,
                    faktur: faktur
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Berhasil',
                            text: `${response.sukses}`,
                            icon: 'success',
                            loader: true,
                            loaderBg: '#9EC600',
                            position: 'bottom-center'
                        });
                        tampil();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });
}
</script>