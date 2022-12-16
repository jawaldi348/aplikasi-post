<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">
            <button type="button" class="btn btn-sm btn-primary" onclick="tambahakun();">
                <i class="fa fa-plus"></i> Tambah Akun Aset Tetap
            </button>
        </div>
        <div class="card-body">
            <blockquote class="card-bodyquote">
                <div class="alert alert-info"><i class="fa fa-info-circle"></i> Info <p>
                        Menampilkan akun aset, silahkan klik tombol detail dari masing-masing akun untuk menambahkan
                        detail aset.
                    </p>
                </div>
            </blockquote>
            <table class="table table-sm table-striped table-bordered display nowrap" id="dataakun" width="100%">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th>No.Akun</th>
                        <th>Nama Akun</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $nomor = 0;
                    foreach ($tampildata->result_array() as $row) :
                        $nomor++;
                    ?>
                    <tr>
                        <td><?= $nomor; ?></td>
                        <td><?= $row['noakun'] ?></td>
                        <td><?= $row['namaakun'] ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="hapus('<?= $row['noakun'] ?>','<?= $row['namaakun'] ?>')">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                            <?php
                                if ($row['akunpenyusutan'] == 0) {
                                ?>
                            <button type="button" class="btn btn-sm btn-info"
                                onclick="detail('<?= sha1($row['noakun']) ?>','<?= $row['namaakun'] ?>')">
                                <i class="fa fa-hand-point-right"></i> Detail
                            </button>
                            <?php
                                } else {
                                ?>
                            <button type="button" class="btn btn-sm btn-success"
                                onclick="penyusutan('<?= $row['noakun'] ?>')">
                                <i class="fa fa-plus"></i> Penyusutan
                            </button>
                            <button type="button" class="btn btn-sm btn-info"
                                onclick="detail('<?= sha1($row['noakun']) ?>','<?= $row['namaakun'] ?>')">
                                <i class="fa fa-hand-point-right"></i> Lihat Detail
                            </button>
                            <?php
                                }
                                ?>
                        </td>
                    </tr>
                    <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<div class="viewmodalpenyusutan" style="display: none;"></div>
<script>
function penyusutan(noakun) {
    $.ajax({
        type: 'post',
        data: {
            noakun: noakun,
        },
        url: "<?= site_url('aset-tetap/formpenyusutan') ?>",
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodalpenyusutan').html(response.data).show();
                $('#modalpenyusutan').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function tambahakun() {
    $.ajax({
        url: "<?= site_url('aset-tetap/formtambahakun') ?>",
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodal').html(response.data).show();
                $('#modaltambahakun').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function detail(noakun, namaakun) {
    window.location = "<?= site_url('aset-tetap/detail/') ?>" + noakun;
}

function hapus(noakun, namaakun) {
    Swal.fire({
        title: 'Hapus Akun',
        html: `Yakin akun <strong>${noakun} - ${namaakun}</strong> di hapus ?`,
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
                url: "<?= site_url('aset-tetap/hapusakun') ?>",
                data: {
                    noakun: noakun,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire(
                            'Berhasil',
                            response.sukses,
                            'success'
                        ).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.value) {
                                window.location.reload();
                            }
                        });
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
    var table = $('#dataakun').DataTable({
        rowReorder: {
            selector: 'td:nth-child(1)'
        },
        responsive: true,

    });
});
</script>