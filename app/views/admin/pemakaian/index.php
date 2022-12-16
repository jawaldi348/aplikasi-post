<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-primary btn-sm"
                onclick="window.location='<?= site_url('pemakaian/input') ?>'">
                <i class="fa fa-fw fa-plus-circle"></i> Input Pemakaian
            </button>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-bordered display nowrap" id="datapemakaian" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Pemakaian</th>
                        <th>Tanggal</th>
                        <th>Akun</th>
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
<div class="viewmodal" style="display: none;"></div>
<script>
function tampildatapemakaian() {
    table = $('#datapemakaian').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('pemakaian/ambildatapemakaian') ?>",
            "type": "POST"
        },


        "columnDefs": [{
                "targets": [0, 6],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [4],
                "className": 'text-center'
            },
            {
                "targets": [5],
                "className": 'text-right'
            }
        ],
    });
}

function hapus(faktur) {
    Swal.fire({
        title: 'Hapus',
        html: `Yakin hapus transaksi pemakaian Faktur <strong>${faktur}</strong> ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, <i class="fa fa-trash"></i> Hapus !',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "<?= site_url('pemakaian/hapustransaksi') ?>",
                data: {
                    faktur: faktur
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            loader: true,
                            position: 'mid-center',
                            hideAfter: 2000,
                            stack: false
                        });
                        tampildatapemakaian();
                    }
                    if (response.error) {
                        $.toast({
                            heading: 'Error',
                            text: response.error,
                            icon: 'error',
                            loader: true,
                            position: 'mid-center',
                            hideAfter: 2000,
                            stack: false
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" +
                        thrownError);
                }
            });
        }
    })
}

function edit(faktur) {
    window.location = "<?= site_url('pemakaian/edit/') ?>" + faktur;
}

function item(faktur) {
    $.ajax({
        type: "post",
        url: "<?= site_url('pemakaian/detailitempemakaian') ?>",
        data: {
            faktur: faktur
        },
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodal').html(response.data).show();
                $('#modaldetailitem').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}
$(document).ready(function() {
    tampildatapemakaian();
});
</script>