<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>

<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-body">
            <table class="table table-sm table-striped" style="font-size:11pt; width: 100%;" id="datadetail">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No.Transaksi</th>
                        <th>Tgl.Transaksi</th>
                        <th>Jumlah (Rp)</th>
                        <th style="width: 20%;">Ket</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $nomor = 0;
                    foreach ($datatransaksi->result_array() as $r) : $nomor++; ?>
                    <tr>
                        <td><?= $nomor; ?></td>
                        <td><?= $r['transno']; ?></td>
                        <td><?= date('d-m-Y', strtotime($r['transtgl'])); ?></td>
                        <td style="text-align: right;"><?= number_format($r['transjml'], 0, ",", "."); ?></td>
                        <td><?= $r['transket']; ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="hapustransaksi('<?= $r['transid'] ?>');">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function hapustransaksi(id) {
    Swal.fire({
        title: 'Hapus Transaksi',
        html: `Yakin transaksi ini di hapus ?`,
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
                url: "<?= site_url('biaya/hapustransaksiakun') ?>",
                data: {
                    id: id,
                },
                dataType: 'json',
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
                        tampildetaildatatransaksi();
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
    var table = $('#datadetail').DataTable({
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,

    });
});
</script>