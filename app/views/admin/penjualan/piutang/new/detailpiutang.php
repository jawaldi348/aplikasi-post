<div class="col-lg-12">
    <div class="card border-light animated slideInUp">
        <div class="card-header">
            <button type="button" class="btn btn-warning btn-sm"
                onclick="location.href=('<?= site_url('admin/penjualan/daftar-piutang-pelanggan') ?>')">
                <i class="fa fa-fast-backward"></i> Kembali
            </button>
            <button type="button" class="btn btn-sm btn-primary btnDataPiutang">
                Tampil Data Piutang
            </button>
            <button type="button" class="btn btn-sm btn-info btnDataSudahBayar">
                Piutang Sudah Bayar
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <tr>
                            <td style="width: 15%;">Kode Member</td>
                            <td style="width: 1%;">:</td>
                            <td style="width: 20%;">
                                <?= $memberkode; ?>
                                <input type="hidden" name="kodemember" id="kodemember" value="<?= $memberkode; ?>">
                            </td>
                            <td style="width: 15%; text-align: right;">Alamat</td>
                            <td style="width: 1%;">:</td>
                            <td style="width: 30%;"><?= $memberalamat; ?></td>
                        </tr>
                        <tr>
                            <td>Nama Member</td>
                            <td>:</td>
                            <td><?= $membernama; ?></td>
                            <td colspan="3"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row viewdetaildata" style="display: none;">

            </div>

        </div>

    </div>
</div>
<script>
function tampilPiutangBelumBayar() {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/penjualan/tampilPiutangBelumBayar') ?>",
        data: {
            kodemember: $('#kodemember').val()
        },
        dataType: "json",
        beforeSend: function() {
            $('.viewdetaildata').html('<i class="fa fa-spin fa-spinner"></i> Silahkan tunggu');
        },
        success: function(response) {
            if (response.data) {
                $('.viewdetaildata').html(response.data).show();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function tampilDataPiutangSudahBayar() {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/penjualan/tampilDataPiutangSudahBayar') ?>",
        data: {
            kodemember: $('#kodemember').val()
        },
        dataType: "json",
        beforeSend: function() {
            $('.viewdetaildata').html('<i class="fa fa-spin fa-spinner"></i> Silahkan tunggu');
        },
        success: function(response) {
            if (response.data) {
                $('.viewdetaildata').html(response.data).show();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function() {
    tampilPiutangBelumBayar();

    $('.btnDataPiutang').click(function(e) {
        e.preventDefault();
        tampilPiutangBelumBayar();
    });

    $('.btnDataSudahBayar').click(function(e) {
        e.preventDefault();
        tampilDataPiutangSudahBayar();
    });
});
</script>