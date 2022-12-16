<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">
            <button type="button" class="btn btn-sm btn-warning"
                onclick="location.href=('<?= site_url('akunneraca/index') ?>');">
                <i class="fa fa-backward"></i> Kembali
            </button>
            <button type="button" class="btn btn-sm btn-success btnTambahTransaksi">
                <i class="fa fa-plus-circle"></i> Tambah Transaksi
            </button>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <td style="width: 15%;">Kode Akun</td>
                    <td style="width: 3%;">:</td>
                    <td>
                        <?= $kode; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 15%;">Nama Akun</td>
                    <td style="width: 3%;">:</td>
                    <td>
                        <?= $nama; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
            </table>

            <div class="tampildatadetail" style="display: none;"></div>
        </div>
    </div>
</div>
<div class="modaltambah" style="display: none;"></div>
<script>
function tampildatadetail() {
    let kode = "<?= $kode; ?>";
    $.ajax({
        type: "post",
        url: "<?= site_url('akunneraca/tampiDataDetail') ?>",
        data: {
            kodeakun: kode
        },
        dataType: "json",
        beforeSend: function() {
            $('.tampildatadetail').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function(response) {
            $('.tampildatadetail').html(response.data).show();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function() {
    tampildatadetail();

    $('.btnTambahTransaksi').click(function(e) {
        e.preventDefault();
        let kode = "<?= $kode; ?>";
        $.ajax({
            type: "post",
            url: "<?= site_url('akunneraca/tambahDetail') ?>",
            data: {
                kode: kode
            },
            dataType: "json",
            beforeSend: function() {
                $('.btnTambahTransaksi').prop('disabled', true);
                $('.btnTambahTransaksi').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            complete: function() {
                $('.btnTambahTransaksi').prop('disabled', false);
                $('.btnTambahTransaksi').html(
                    '<i class="fa fa-plus-circle"></i> Tambah Transaksi');
            },
            success: function(response) {
                if (response.data) {
                    $('.modaltambah').html(response.data).show();
                    $('#modaltambahdetail').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
});
</script>