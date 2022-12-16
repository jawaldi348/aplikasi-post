<div class="modal fade" id="modaltambahdetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/akunneraca/simpandetail', ['class' => 'frmsimpan']) ?>
            <input type="hidden" name="kodeakun" value="<?= $kode; ?>">
            <div class="modal-body">
                <div class="pesan" style="display: none;"></div>
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label col-form-label-sm">Tanggal</label>
                    <div class="col-sm-4">
                        <input type="date" class="form-control form-control-sm" name="tanggal"
                            value="<?= date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label col-form-label-sm">Jenis Transaksi</label>
                    <div class="col-sm-4">
                        <select name="jenis" id="jenis" class="form-control form-control-sm">
                            <option value="" selected>-Pilih-</option>
                            <option value="D">Debit</option>
                            <option value="K">Kredit</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label col-form-label-sm">Jumlah (Rp)</label>
                    <div class="col-sm-4">
                        <input type="text" name="jml" id="jml" class="form-control form-control-sm">
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
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function() {
    $('#jml').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

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
                    tampildatadetail();
                    $('#modaltambahdetail').modal('hide');
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