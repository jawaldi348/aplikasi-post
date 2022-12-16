<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">
            <button type="button" class="btn btn-sm btn-warning"
                onclick="window.location='<?= site_url('biaya/index') ?>'">
                <i class="fa fa-backward"></i> Kembali
            </button>
            <button type="button" class="btn btn-sm btn-primary btntambahbiaya">
                <i class="fa fa-money-bill"></i> Tambah Biaya Pengeluaran
            </button>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped">
                <tr>
                    <td style="font-weight: bold; width: 15%;">No.Akun</td>
                    <td style="width: 1%;">:</td>
                    <td><?= $data['noakun']; ?></td>
                    <input type="hidden" name="noakun" id="noakun" value="<?= $data['noakun']; ?>">
                </tr>
                <tr>
                    <td style="font-weight: bold; width: 15%;">Nama Akun</td>
                    <td style="width: 1%;">:</td>
                    <td><?= $data['namaakun']; ?></td>
                </tr>
            </table>
            <div class="row detaildatatransaksi" style="display: none;">

            </div>
        </div>
    </div>
</div>
<div class="viewmodaltransaksi" style="display: none;"></div>
<script>
function tampildetaildatatransaksi() {
    let noakun = $('#noakun').val();
    $.ajax({
        type: "post",
        url: "<?= site_url('biaya/modaldetaildatatransaksi') ?>",
        data: {
            noakun: noakun
        },
        dataType: "json",
        beforeSend: function() {
            $('.detaildatatransaksi').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function(response) {
            if (response.data) {
                $('.detaildatatransaksi').html(response.data).show();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

$(document).ready(function() {
    tampildetaildatatransaksi();

    $('.btntambahbiaya').click(function(e) {
        e.preventDefault();
        let noakun = $('#noakun').val();
        $.ajax({
            type: "post",
            url: "<?= site_url('biaya/modaltambahtransaksiakun') ?>",
            data: {
                noakun: noakun
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodaltransaksi').html(response.data).show();
                    $('#modaltambahtransaksi').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
});
</script>