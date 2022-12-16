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
            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light btntambah"
                onclick="window.location=('<?= site_url('koreksistok/input') ?>')">
                <i class="fa fa-plus-square"></i> Input Koreksi Stok
            </button>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-bordered display nowrap" id="datakoreksi" width="100%">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>No.Koreksi</th>
                        <th style="width: 10%;">Tanggal</th>
                        <th style="width: 15%;">Pemasok</th>
                        <th style="width: 5%;">Jml.Item</th>
                        <th style="width: 5%;">#</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="viewmodalitem" style="display: none;"></div>
<script>
function item(no) {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/koreksistok/detailitem') ?>",
        data: {
            no: no
        },
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodalitem').html(response.data).show();
                $('#modaldetailitem').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function edit(no) {
    window.location.href = ("<?= site_url('admin/koreksistok/edit/') ?>") + no;
}

function tampildata() {
    table = $('#datakoreksi').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('koreksistok/ambildata') ?>",
            "type": "POST"
        },


        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [5],
                "orderable": false,
            },
            {
                "targets": [4, 5],
                "className": 'text-center'
            }
        ],

    });
}

function hapus(no) {
    Swal.fire({
        title: 'Hapus Data',
        html: `Yakin menghapus data koreksi stok ini ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "<?= site_url('koreksistok/hapusnokoreksi') ?>",
                data: {
                    no: no
                },
                dataType: 'json',
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            html: response.sukses
                        }).then((result) => {
                            if (result.value) {
                                window.location.reload();
                            }
                        })
                    }
                    if (response.error) {
                        Swal.fire(
                            'Error',
                            response.error,
                            'error'
                        );
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    })
}
$(document).ready(function() {
    tampildata();
});
</script>