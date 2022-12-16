<!-- DataTables -->
<link href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<link href="<?= base_url() ?>assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<div class="modal fade" id="modaldetaildataakun" tabindex="-1" role="dialog" aria-labelledby="tambahdataakun"
    aria-hidden="true">
    <div class="modal-dialog modal-lg animated slideInDown" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahdataakun">Detail Akun <?= $noakun; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-sm" id="dataakun">
                    <thead>
                        <th>No</th>
                        <th>No.Transaksi</th>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Ket</th>
                        <th>#</th>
                    </thead>
                    <tbody>
                        <?php
                        $nomor = 0;
                        foreach ($datadetail->result_array() as $row) :
                            $nomor++;
                        ?>
                        <tr>
                            <td><?= $nomor; ?></td>
                            <td><?= $row['transno']; ?></td>
                            <td><?= date('d-m-Y', strtotime($row['transtgl'])); ?></td>
                            <td><?= number_format($row['masuk'], 0, ".", ","); ?></td>
                            <td><?= number_format($row['keluar'], 0, ".", ","); ?></td>
                            <td><?= $row['transket']; ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="hapus('<?= $row['transid'] ?>')">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#dataakun').DataTable();
});

function hapus(no) {
    Swal.fire({
        title: 'Hapus Transaksi Neraca',
        html: `Yakin di hapus ?`,
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
                url: "<?= site_url('admin/neraca/hapustransaksineraca') ?>",
                data: {
                    id: no,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.sukses,
                        });
                        tampilakun();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    })
}
</script>