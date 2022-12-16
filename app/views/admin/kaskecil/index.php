<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light btntambah">
                <i class="fa fa-plus-square"></i> Tambah Kas
            </button>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-bordered display nowrap" id="datakaskecil" width="100%">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="check-all">
                        </th>
                        <th>No</th>
                        <th>Jml Kas(Rp)</th>
                        <th>Periode Awal</th>
                        <th>Periode Akhir</th>
                        <th>Stt</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            <?= form_close(); ?>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script>
function tampildatakas() {
    table = $('#datakaskecil').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('admin/kaskecil/ambildata') ?>",
            "type": "POST"
        },


        "columnDefs": [{
            "targets": [0],
            "orderable": false,
            "width": 3
        }],

    });

    $('.btntambah').click(function(e) {
        $.ajax({
            url: "<?= site_url('admin/kaskecil/tambah') ?>",
            success: function(response) {
                $('.viewmodal').html(response).show();
                $('#modaltambah').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    });
}

$(document).ready(function() {
    tampildatakas();
});

function aktif(id) {
    Swal.fire({
        title: 'Ubah Status Kunci',
        text: "Yakin di Kunci ?",
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
                url: "<?= site_url('admin/kaskecil/ubahstatus') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Sukses',
                            text: response.sukses,
                            showHideTransition: 'slide',
                            icon: 'success',
                            position: 'bottom-right'
                        });
                        tampildatakas();
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

function hapus(id) {
    Swal.fire({
        title: 'Hapus Kas Kecil',
        text: "Yakin di hapus ?",
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
                url: "<?= site_url('admin/kaskecil/hapus') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Sukses',
                            text: response.sukses,
                            showHideTransition: 'slide',
                            icon: 'success',
                            position: 'bottom-right'
                        });
                        tampildatakas();
                    } else {
                        $.toast({
                            heading: 'Error',
                            text: response.error,
                            showHideTransition: 'slide',
                            icon: 'error',
                            position: 'bottom-right'
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
</script>