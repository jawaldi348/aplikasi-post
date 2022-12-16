<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>

<table class="table table-sm table-striped" style="font-size:11pt; width: 100%;" id="dataakunneraca">
    <thead>
        <tr>
            <th style="width: 7%;">No</th>
            <th>Tanggal</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th style="width: 10%;">#</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($datadetail->result_array() as $r) :
        ?>
        <tr>
            <td><?= $nomor++; ?></td>
            <td><?= $r['tgl']; ?></td>
            <td><?= number_format($r['debit'], 0, ",", "."); ?></td>
            <td><?= number_format($r['kredit'], 0, ",", "."); ?></td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" title="Hapus Data"
                    onclick="hapus('<?= $r['id'] ?>')">
                    <i class="fa fa-trash-alt"></i>
                </button>
            </td>
        </tr>
        <?php
        endforeach;
        ?>
    </tbody>

</table>
<script>
function hapus(id) {
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
                url: "<?= site_url('akunneraca/hapusDetail') ?>",
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
                        tampildatadetail();
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

function detailakun(kode) {
    window.location.href = ('<?= site_url('akunneraca/detail/') ?>' + kode);
}
$(document).ready(function() {
    var table = $('#dataakunneraca').DataTable({
        responsive: true,

    });
});
</script>