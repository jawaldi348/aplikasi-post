@extends(layouts/index)
@section(content)
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-warning" onclick="window.location='<?= site_url('member') ?>'">
                &laquo; Kembali
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped table-table-responsive table-sm">
                <thead>
                    <tr>
                        <td style="width: 20%;">Kode Member</td>
                        <td style="width: 1%;">:</td>
                        <td><?= $data['kode_member']; ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">Nama</td>
                        <td style="width: 1%;">:</td>
                        <td><?= $data['nama_member']; ?></td>
                    </tr>
                    <tr>
                        <td>Jenkel</td>
                        <td>:</td>
                        <td><?= $data['jenkel_member']; ?></td>
                    </tr>
                    <tr>
                        <td>Tempat/Tgl.Lahir</td>
                        <td>:</td>
                        <td><?= $data['tempat_lahir'] . ' / ' . $data['tanggal_lahir']; ?></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td><?= $data['alamat_member']; ?></td>
                    </tr>
                    <tr>
                        <td>Telp/HP</td>
                        <td>:</td>
                        <td><?= $data['telp_member']; ?></td>
                    </tr>
                    <tr>
                        <td>Barcode</td>
                        <td>:</td>
                        <td>
                            <img class="img-responsive" src="<?= base_url() . getenv('PATH_BARCODE') . $data['barcode_member'] ?>" style="width:20%;">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <button type="button" class="btn btn-sm btn-success" onclick="cetakkartu('<?= $data['kode_member'] ?>')">Cetak Kartu Anggota</button>
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

        var url = "<?= site_url('member/cetak-kartu/') ?>" + kode;
        var uploadWin = window.open(url,
            "Koreksi Stok",
            "width=800,height=600" + ",top=" + top +
            ",left=" + left);
        uploadWin.moveTo(left, top);
        uploadWin.focus();
    }
</script>
@endsection