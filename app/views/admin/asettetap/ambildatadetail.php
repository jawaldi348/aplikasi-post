<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-sm-12">
    <p>
    <table class="table table-sm table-striped table-bordered display nowrap" id="datadetail" width="100%">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th>Nama Aset</th>
                <th>Jumlah</th>
                <th>@Harga(Rp)</th>
                <th>Sub.Total(Rp)</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $no = 0;
                foreach ($datadetail->result_array() as $r) :
                    $no++;
                ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $r['detasetnama']; ?></td>
                <td><?= $r['detasetjml']; ?></td>
                <td style="text-align: right;"><?= number_format($r['detasetharga'], 0, ",", "."); ?></td>
                <td style="text-align: right;"><?= number_format($r['detasetsubtotal'], 0, ",", "."); ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapus('<?= $r['detid'] ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
            <?php
                endforeach;
                ?>
        </tbody>
    </table>
    </p>
</div>
<script>
$(document).ready(function() {
    var table = $('#datadetail').DataTable({
        rowReorder: {
            selector: 'td:nth-child(1)'
        },
        responsive: true,

    });
});

function hapus(id) {
    Swal.fire({
        title: 'Hapus Detail Aset',
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
                url: "<?= site_url('aset-tetap/hapusasetdetail') ?>",
                data: {
                    id: id,
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
                                tampildatadetail();
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
</script>