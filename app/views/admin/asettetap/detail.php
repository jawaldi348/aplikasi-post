<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">
            <button type="button" class="btn btn-sm btn-warning"
                onclick="window.location=('<?= site_url('aset-tetap/index') ?>')">
                &laquo; Kembali
            </button>

            <button type="button" class="btn btn-sm btn-primary" onclick="tambahaset();">
                <i class="fa fa-plus"></i> Tambah Detail Aset <?= $namaakun; ?>
            </button>
        </div>
        <div class="card-body">
            <input type="hidden" name="noakun" id="noakun" value="<?= $noakun; ?>">
            <input type="hidden" name="namaakun" id="namaakun" value="<?= $namaakun; ?>">
            <input type="hidden" name="akunpenyusutan" id="akunpenyusutan" value="<?= $akunpenyusutan; ?>">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th style="width: 20%;">No.Akun</th>
                        <th style="width: 1%;">:</th>
                        <th><?= $noakun; ?></th>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Nama Akun</th>
                        <th style="width: 1%;">:</th>
                        <th><?= $namaakun; ?></th>
                    </tr>
                </thead>
            </table>
            <div class="row viewdatadetail" style="display: none;"></div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script>
function tambahaset() {
    let noakun = $('#noakun').val();
    let namaakun = $('#namaakun').val();
    let akunpenyusutan = $('#akunpenyusutan').val();
    $.ajax({
        type: "post",
        url: "<?= site_url('aset-tetap/formtambahdetailaset') ?>",
        data: {
            noakun: noakun,
            namaakun: namaakun,
            akunpenyusutan: akunpenyusutan,
        },
        cache: false,
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodal').html(response.data).show();
                $('#modaltambahaset').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function tampildatadetail() {
    let noakun = $('#noakun').val();
    let akunpenyusutan = $('#akunpenyusutan').val();
    $.ajax({
        type: "post",
        url: "<?= site_url('aset-tetap/ambildatadetail') ?>",
        data: {
            noakun: noakun,
            akunpenyusutan: akunpenyusutan
        },
        dataType: "json",
        cache: false,
        beforeSend: function() {
            $('.viewdatadetail').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function(response) {
            if (response.data) {
                $('.viewdatadetail').html(response.data).show();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function() {
    tampildatadetail();
});
</script>