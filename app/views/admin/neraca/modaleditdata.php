<div class="modal fade" id="modalakunneraca" tabindex="-1" role="dialog" aria-labelledby="tambahmember"
    aria-hidden="true">
    <div class="modal-dialog animated slideInRight" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahmember">Edit Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/neraca/updatedata', ['class' => 'formtambah']); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>No.Akun`</label>
                    <input type="text" class="form-control" name="noakun" value="<?= $noakun; ?>" id="noakun" readonly>
                </div>
                <div class="form-group">
                    <label>No.Akun`</label>
                    <input type="text" class="form-control" name="namaakun" value="<?= $namaakun; ?>" id="namaakun"
                        readonly>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" class="form-control" name="tgl" value="<?= $tgl; ?>" id="tgl">
                </div>
                <div class="form-group">
                    <label>Jumlah (Rp)</label>
                    <input type="text" class="form-control" name="jml" value="<?= $jml; ?>" id="jml">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btnsimpan">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function() {
    //setting currency
    $('#jml').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

    $('.formtambah').submit(function(e) {
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $('.btnsimpan').attr('disabled', 'disabled');
                $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if (response.error) {
                    $('.msg').fadeIn();
                    $('.msg').html(response.error);
                }
                if (response.sukses) {
                    $.toast({
                        heading: 'Berhasil',
                        text: response.sukses,
                        icon: 'success',
                        loader: true,
                    });
                    tampilakun();
                    $('#modalakunneraca').modal('hide');
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