<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">
            <button type="button" class="btn btn-sm btn-primary btnaddakun">
                <i class="fa fa-plus-circle"></i> Tambah Akun Biaya
            </button>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped" style="font-size:11pt; width: 100%;" id="databiaya">
                <thead>
                    <tr>
                        <th>No Akun</th>
                        <th>Nama Akun</th>
                        <th>Total (Rp)</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataakun->result_array() as $r) : ?>
                    <tr>
                        <td><?= $r['noakun']; ?></td>
                        <td><?= $r['namaakun']; ?></td>
                        <td style="text-align: right;">
                            <?php
                                $noakun = $r['noakun'];
                                $querysaldo = $this->db->query("SELECT 
                                CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun' ORDER BY transtgl ASC;");
                                $saldo_akhir = 0;
                                foreach ($querysaldo->result_array() as $akhir) :
                                    $saldo_akhir = ($saldo_akhir + $akhir['masuk']) - $akhir['keluar'];
                                endforeach;

                                echo number_format($saldo_akhir, 0, ",", ".");
                                ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info"
                                onclick="window.location=('<?= site_url('biaya/detail/' . $r['noakun']) ?>')">
                                Detail
                            </button>
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="hapusakun('<?= $r['noakun'] ?>');">
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
<div class="viewmodal" style="display:none;"></div>
<script>
$(document).ready(function() {
    var table = $('#databiaya').DataTable({
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,

    });

    $('.btnaddakun').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('biaya/modaltambahakun') ?>",
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
    });
});

function hapusakun(noakun) {
    Swal.fire({
        title: 'Hapus Akun',
        html: `Yakin akun ${noakun} di hapus ?`,
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
                url: "<?= site_url('biaya/hapusakun') ?>",
                data: {
                    noakun: noakun,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.sukses) {
                        window.location.reload();
                    }
                    if (response.error) {
                        Swal.fire(
                            'Error',
                            response.error,
                            'error'
                        )
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