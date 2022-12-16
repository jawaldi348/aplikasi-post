<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-warning"
                onclick="window.location='<?= site_url('admin/member/index') ?>'">
                &laquo; Kembali
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped table-table-responsive table-sm">
                <thead>
                    <tr>
                        <td style="width: 20%;">Kode Member</td>
                        <td style="width: 1%;">:</td>
                        <td><?= $row['memberkode']; ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">Nama</td>
                        <td style="width: 1%;">:</td>
                        <td><?= $row['membernama']; ?></td>
                    </tr>
                    <tr>
                        <td>Jenkel</td>
                        <td>:</td>
                        <td><?= $row['memberjenkel']; ?></td>
                    </tr>
                    <tr>
                        <td>Tempat/Tgl.Lahir</td>
                        <td>:</td>
                        <td><?= $row['membertmplahir'] . ' / ' . $row['membertgllahir']; ?></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td><?= $row['memberalamat']; ?></td>
                    </tr>
                    <tr>
                        <td>Telp/HP</td>
                        <td>:</td>
                        <td><?= $row['membertelp']; ?></td>
                    </tr>
                    <tr>
                        <td>Barcode</td>
                        <td>:</td>
                        <td>
                            <img class="img-responsive" src="<?php echo base_url() . $pathbarcode; ?>"
                                style="width:20%;">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <button type="button" class="btn btn-sm btn-success"
                                onclick="cetakkartu('<?= sha1($row['memberkode']) ?>')">
                                Cetak Kartu Anggota
                            </button>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
function cetakkartu(kode) {

    var top = window.screen.height - 600;
    top = top > 0 ? top / 2 : 0;

    var left = window.screen.width - 800;
    left = left > 0 ? left / 2 : 0;

    var url = "<?= site_url('admin/member/cetak-kartu-anggota/') ?>" + kode;
    var uploadWin = window.open(url,
        "Koreksi Stok",
        "width=800,height=600" + ",top=" + top +
        ",left=" + left);
    uploadWin.moveTo(left, top);
    uploadWin.focus();
}
</script>