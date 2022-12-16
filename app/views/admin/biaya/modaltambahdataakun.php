<div class="modal fade" id="modaltambahtransaksi" tabindex="-1" role="dialog" aria-labelledby="tambahdataakun"
    aria-hidden="true">
    <div class="modal-dialog animated slideInDown" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahdataakun">Tambah Data Akun <?= $noakun; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('biaya/simpandataakun', ['class' => 'formsimpan']) ?>
            <div class="modal-body">
                <input type="hidden" name="noakun" value="<?= $noakun; ?>">
                <div class="form-group">
                    <label for="">No.Transaksi <span class="badge badge-info">Otomatis</span></label>
                    <input type="text" readonly value="<?= $notrans; ?>" name="notrans" id="notrans"
                        class="form-control form-control-sm">
                </div>
                <div class="form-group">
                    <label for="">Tanggal</label>
                    <input type="date" value="<?= date('Y-m-d'); ?>" name="tgl" id="tgl"
                        class="form-control form-control-sm">
                    <div class="invalid-feedback errortgl">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Jumlah (Rp)</label>
                    <input type="text" value="" name="jml" id="jml" class="form-control form-control-sm">
                    <div class="invalid-feedback errorjml">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Tambah Keterangan</label>
                    <input type="text" value="" name="ket" id="ket" class="form-control form-control-sm">
                    <div class="invalid-feedback errorket">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btnsimpan">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function(e) {
    //setting currency
    $('#jml').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

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
                    if (response.error.tgl) {
                        $('#tgl').addClass('is-invalid');
                        $('.errortgl').html(response.error.tgl);
                    } else {
                        $('#tgl').removeClass('is-invalid');
                        $('#tgl').addClass('is-valid');
                        $('.errortgl').html('');
                    }
                    if (response.error.jml) {
                        $('#jml').addClass('is-invalid');
                        $('.errorjml').html(response.error.jml);
                    } else {
                        $('#jml').removeClass('is-invalid');
                        $('#jml').addClass('is-valid');
                        $('.errorjml').html('');
                    }
                }
                if (response.sukses) {
                    $.toast({
                        heading: 'Berhasil',
                        text: response.sukses,
                        icon: 'success',
                        loader: true,
                    });
                    tampildetaildatatransaksi();
                    $('#modaltambahtransaksi').modal('hide');
                }
            },
            complete: function() {
                $('.btnsimpan').removeAttr('disabled');
                $('.btnsimpan').html('Simpan Data');
            }
        });

        return false;
    });
});
</script>