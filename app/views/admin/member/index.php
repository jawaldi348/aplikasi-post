<link href="<?= base_url(); ?>assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-primary" id="btntambah">
                <i class="fa fa-fw fa-plus-circle"></i> Tambah Data
            </button>
            <!-- <button type="button" class="btn btn-sm btn-success btnsinkronisasi" id="">
                <i class="fa fa-fw fa-sync-alt"></i> Sinkronisasi Data Anggota Koperasi
            </button> -->
        </div>
        <div class="card-body">
            <div class="pesan" style="display: none;"></div>
            <p class="card-text">
            <table class="table table-sm table-striped table-bordered display nowrap" id="datamember" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Member</th>
                        <th>Nama Member</th>
                        <th>Instansi</th>
                        <th>Alamat</th>
                        <!-- <th>Total Tabungan <br>Diskon(Rp)</th> -->
                        <th>Aksi</th>
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
function tampildatamember() {
    table = $('#datamember').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('admin/member/ambildata') ?>",
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
    tampildatamember();

    $('#btntambah').click(function(e) {
        $.ajax({
            url: "<?= site_url('admin/member/tambah'); ?>",
            success: function(response) {
                $('.viewform').show();
                $('.viewform').html(response);
                const element = document.querySelector('#modaltambah');
                element.classList.add('animated', 'flipInX');
                $('#modaltambah').on('shown.bs.modal', function(e) {
                    $('input[name="nama"]').focus();
                })
                $('#modaltambah').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    });

    $('.btnsinkronisasi').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('admin/member/getdatamemberapi') ?>",
            beforeSend: function(e) {
                $('.pesan').html(
                    '<i class="fa fa-spin fa-spinner"></i> Silahkan Tunggu, data sedang diambil di server Koperasi'
                ).show();
            },
            success: function(response) {
                $('.pesan').html(response).show();
            }
        });
    });
});

function detaildata(kode) {
    window.location.href = ("<?= site_url('admin/member/detail/') ?>" + kode);
}

function edit(kode) {
    $.ajax({
        url: "<?= site_url('admin/member/edit') ?>",
        type: 'post',
        data: {
            kode: kode
        },
        cache: false,
        success: function(response) {
            $('.viewform').show();
            $('.viewform').html(response);
            const element = document.querySelector('#modaledit');
            element.classList.add('animated', 'flipInX');
            $('#modaledit').on('shown.bs.modal', function(e) {
                $('input[name="nama"]').focus();
            })
            $('#modaledit').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}

function hapus(kode) {
    Swal.fire({
        title: 'Hapus Member',
        text: `Yakin memhapus member dengan kode ${kode} ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "<?= site_url('admin/member/hapus') ?>",
                type: 'post',
                data: {
                    kode: kode
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
                        tampildatamember();
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