<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card border-light mb-1">
        <div class="card-header">
            <button type="button" class="btn btn-sm btn-warning"
                onclick="window.location='<?= site_url('pengambilandiskon/input') ?>'">
                &laquo; Kembali Ke-Input
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm" id="datapengambilan" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pengambilan</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Total (Rp)</th>
                            <th>Pilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor = 0;
                        foreach ($datapengambilan->result_array() as $rr) : $nomor++; ?>
                        <tr>
                            <td><?= $nomor; ?></td>
                            <td><?= $rr['ambilkode']; ?></td>
                            <td><?= date('d-m-Y', strtotime($rr['ambiltgl'])); ?></td>
                            <td>
                                <?php
                                    if ($rr['ambiljenis'] == 0) {
                                        echo '<span class="badge badge-info">Perorangan</span>';
                                    } else {
                                        echo '<span class="badge badge-success">Keseluruhan Member</span>';
                                    }
                                    ?>
                            </td>
                            <td style="text-align: right;">
                                <?= number_format($rr['ambiltotal'], 0, ",", ".") ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-info btn-sm dropdown-toggle" href="#" role="button"
                                        id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Aksi
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" onclick="hapuspengambilan('<?= $rr['ambilkode'] ?>')"
                                            href="#">Hapus</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    var table = $('#datapengambilan').DataTable({
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,

    });
});

function hapuspengambilan(kode) {
    Swal.fire({
        title: 'Hapus Data Pengambilan',
        text: `Yakin di hapus ?`,
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
                url: '<?= site_url('pengambilandiskon/hapusdata') ?>',
                data: {
                    kode: kode
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        window.location.reload();
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