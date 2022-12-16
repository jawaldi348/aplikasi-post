<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>

<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-primary btntambah">
                <i class="fa fa-plus"></i> Tambah Saldo
            </button>
        </div>
        <div class="card-body">
            <div class="card-text">
                <table class="table table-sm table-striped table-bordered display nowrap" id="datasaldo" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tgl.Input</th>
                            <th>Jumlah Saldo <br>(Rp)</th>
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
<div class="viewmodal" style="display: none;"></div>
<script>
function tampildatasaldo() {
    table = $('#datasaldo').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('admin/saldo/ambildata') ?>",
            "type": "POST"
        },


        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [2],
                "className": 'text-right'
            },
            {
                "targets": [3],
                "orderable": false,
                "width": 5
            }
        ],
    });
}

$(document).ready(function() {
    tampildatasaldo();

    $('.btntambah').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('saldo/tambah') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodal').html(response.data).show();
                    $('#modaltambah').on('shown.bs.modal', function(e) {
                        $('#tgl').focus();
                    });
                    $('#modaltambah').modal('show');
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
    });

});

function hapus(kode) {
    Swal.fire({
        title: 'Hapus',
        text: `Yakin Menghapus Data saldo ini ?`,
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
                url: "<?= site_url('saldo/hapus') ?>",
                data: {
                    kode: kode
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        tampildatasaldo();
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            loader: true,
                        });
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
</script>