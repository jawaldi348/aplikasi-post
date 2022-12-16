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
            <th>No Akun</th>
            <th>Nama Akun</th>
            <th style="width: 20%;">Saldo (Rp)</th>
            <th style="width: 10%;">#</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($tampildata->result_array() as $r) :
        ?>
        <tr>
            <td><?= $nomor++; ?></td>
            <td><?= $r['akunkode']; ?></td>
            <td><?= $r['akunnama']; ?></td>
            <td style="text-align: right;">
                <?php
                    $noakun = $r['akunkode'];
                    $querysaldo = $this->db->query("SELECT 
                    CASE a.`jenis` WHEN 'D' THEN jumlah  ELSE 0 END AS masuk,
                    CASE a.`jenis` WHEN 'K' THEN jumlah ELSE 0 END AS keluar
                    FROM `akun_neraca_detail` a WHERE a.`kodeakun` = '$noakun' ORDER BY tgl ASC");
                    $saldo_akhir = 0;
                    foreach ($querysaldo->result_array() as $akhir) :
                        $saldo_akhir = ($saldo_akhir + $akhir['masuk']) - $akhir['keluar'];
                    endforeach;

                    echo number_format($saldo_akhir, 0, ",", ".");
                    ?>
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-danger" title="Hapus Data Akun"
                    onclick="hapusakun('<?= $r['akunkode'] ?>')">
                    <i class="fa fa-trash-alt"></i>
                </button>
                <button type="button" class="btn btn-sm btn-info" title="Detail Akun"
                    onclick="detailakun('<?= sha1($r['akunkode']) ?>')">
                    <i class="fa fa-hand-point-right"></i>
                </button>
            </td>
        </tr>
        <?php
        endforeach;
        ?>
    </tbody>

</table>
<script>
function hapusakun(kode) {
    Swal.fire({
        title: 'Hapus Akun',
        html: `Yakin menghapus akun ini ? <br><strong>Menghapus Akun ini, akan mengakibatkan semua data termasuk data detail terhapus...</strong>`,
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
                url: "<?= site_url('akunneraca/hapusAkun') ?>",
                data: {
                    kode: kode,
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
                        tampildataakun();
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