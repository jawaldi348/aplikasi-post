<div class="modal fade" id="modaltambahakun" tabindex="-1" role="dialog" aria-labelledby="tambahdata"
    aria-hidden="true">
    <div class="modal-dialog modal-lg animated slideInLeft" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahdata">Tambah Akun Biaya</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('biaya/simpanakun', ['class' => 'formsimpan']); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">No.Akun</label>
                    <div class="col-sm-1">
                        <input type="text" name="noakun1" id="noakun1" class="form-control form-control-sm" readonly
                            value="6-">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="noakun2" id="noakun2" class="form-control form-control-sm" autofocus>
                        <div class="invalid-feedback erronoakun2">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Nama Akun</label>
                    <div class="col-sm-4">
                        <input type="text" name="namaakun" id="namaakun" class="form-control form-control-sm">
                        <div class="invalid-feedback errornamaakun">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.formsimpan').submit(function(e) {
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $('.btnsimpan').attr('disabled', 'disabled');
                $('.btnsimpan').html(
                    '<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if (response.error) {
                    if (response.error.noakun2) {
                        $('#noakun2').addClass('is-invalid');
                        $('.errornoakun2').html(response.error.noakun2);
                    } else {
                        $('#noakun2').removeClass('is-invalid');
                        $('#noakun2').addClass('is-valid');
                        $('.errornoakun2').html('');
                    }
                    if (response.error.namaakun) {
                        $('#namaakun').addClass('is-invalid');
                        $('.errornamaakun').html(response.error.namaakun);
                    } else {
                        $('#namaakun').removeClass('is-invalid');
                        $('#namaakun').addClass('is-valid');
                        $('.errornamaakun').html('');
                    }
                }
                if (response.sukses) {
                    window.location.reload();
                }
            },
            complete: function() {
                $('.btnsimpan').removeAttr('disabled');
                $('.btnsimpan').html('Simpan Data');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });

        return false;
    });
});
</script>