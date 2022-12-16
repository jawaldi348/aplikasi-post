<div class="modal fade" id="modaltambahakun" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/akunneraca/simpanakun', ['class' => 'frmsimpan']) ?>
            <div class="modal-body">
                <div class="pesan" style="display: none;"></div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label col-form-label-sm">Kode Akun</label>
                    <div class="col-sm-1">
                        <input type="text" class="form-control form-control-sm" name="kodeakun1">
                    </div>
                    <div class="col-sm-1">
                        -
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control form-control-sm" name="kodeakun2" placeholder="XXX"
                            maxlength="3">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label col-form-label-sm">Nama Akun</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm" name="namaakun">
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btnsimpan">
                    Simpan
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.frmsimpan').submit(function(e) {
        e.preventDefault();
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
                    $('.pesan').html(response.error).show();
                }
                if (response.sukses) {
                    $.toast({
                        heading: 'Berhasil',
                        text: response.sukses,
                        icon: 'success',
                        loader: true,
                    });
                    tampildataakun();
                    $('#modaltambahakun').modal('hide');
                }
            },
            complete: function() {
                $('.btnsimpan').removeAttr('disabled');
                $('.btnsimpan').html('Simpan');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });

        return false;
    });
});
</script>