<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-primary" id="btntambah">
                <i class="fa fa-fw fa-plus-circle"></i> Tambah Data
            </button>
        </div>
        <div class="card-body">
            <p class="card-text">
                <table class="table table-sm table-striped table-bordered display nowrap" id="datauser" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID User</th>
                            <th>Nama Lengkap</th>
                            <th>Status</th>
                            <th>Grup</th>
                            <th style="width: 10%;">#</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </p>
        </div>
    </div>
</div>
<div class="viewform" style="display: none;"></div>
<script>
function hapus(userid) {
    Swal.fire({
        title: 'Hapus User',
        text: `Yakin menghapus user ${userid} ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "<?= site_url('admin/manuser/hapus') ?>",
                type: 'post',
                data: {
                    userid: userid
                },
                dataType: 'json',
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            loader: true,
                            position: 'top-center'
                        });
                        tampildatauser();
                    } else {
                        $.toast({
                            heading: 'Error',
                            text: response.error,
                            icon: 'error',
                            loader: true,
                            position: 'top-center'
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

function edituser(userid) {
    $.ajax({
        url: "<?= site_url('admin/manuser/formedituser') ?>",
        type: 'post',
        data: {
            userid: userid
        },
        success: function(response) {
            $('.viewform').html(response).show();
            $('#modaledit').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}

function editstatus(userid) {
    Swal.fire({
        title: 'Ubah Status User',
        text: `Yakin mengubah status user : ${userid} ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "<?= site_url('admin/manuser/ubahstatus') ?>",
                type: 'post',
                data: {
                    userid: userid
                },
                dataType: 'json',
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            loader: true,
                            position: 'top-center'
                        });
                        tampildatauser();
                    } else {
                        $.toast({
                            heading: 'Error',
                            text: response.error,
                            icon: 'error',
                            loader: true,
                            position: 'top-center'
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

function tampildatauser() {
    table = $('#datauser').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('admin/manuser/ambildata') ?>",
            "type": "POST"
        },


        "columnDefs": [{
            "targets": [0],
            "orderable": false,
            "width": 5
        }],

    });
}
$(document).ready(function() {
    tampildatauser();

    $('#btntambah').click(function(e) {
        $.ajax({
            url: "<?= site_url('admin/manuser/tambah'); ?>",
            success: function(response) {
                $('.viewform').show();
                $('.viewform').html(response);
                $('#modaltambah').on('shown.bs.modal', function(e) {
                    $('input[name="iduser"]').focus();
                })
                $('#modaltambah').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    });
});
</script>