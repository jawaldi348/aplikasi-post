<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>

<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-primary btntambah"
                onclick="window.location='<?= site_url('pulsa/input-produk') ?>'">
                <i class="fa fa-plus"></i> Tambah Produk Saldo
            </button>
        </div>
        <div class="card-body">
            <div class="card-text">
                <table class="table table-sm table-striped table-bordered display nowrap" id="datapulsa" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Harga Modal (Rp)</th>
                            <th>Harga Jual (Rp)</th>
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
function hapusproduk(id, kode, nama) {
    Swal.fire({
        title: 'Hapus',
        html: `Yakin Menghapus Produk <strong>${nama}</strong> ?`,
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
                url: "<?= site_url('pulsa/hapusproduk') ?>",
                data: {
                    id: id,
                    kode: kode
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        tampildataproduk();
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            loader: true,
                        });
                    }

                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Maaf !',
                            html: `${response.error}`
                        })
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Swal.fire({
                        icon: 'error',
                        title: xhr.status,
                        html: xhr.responseText + "\n" + thrownError,
                    });
                }
            });
        }
    })
}

function formedit(id) {
    window.location = "<?= site_url('pulsa/edit/') ?>" + id;
}

function tampildataproduk() {
    table = $('#datapulsa').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('admin/pulsa/ambildataprodukpulsa') ?>",
            "type": "POST"
        },


        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [3, 4],
                "className": 'text-right'
            }
        ],
    });
}
$(document).ready(function() {
    tampildataproduk();
});
</script>