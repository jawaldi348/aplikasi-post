<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog" aria-labelledby="tambahkaskecil"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahkaskecil">Form Tambah Kas Kecil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/kaskecil/simpandata', ['class' => 'form']); ?>
            <div class="modal-body">
                <div class="msg" style="display: none;"></div>
                <div class="form-group">
                    <label>Jumlah Uang</label>
                    <input type="text" name="jml" id="jml" class="form-control" value="0">
                </div>
                <div class="form-group">
                    <label>Periode Awal</label>
                    <input type="date" name="awal" id="awal" class="form-control" value="0">
                </div>
                <div class="form-group">
                    <label>Periode Akhir</label>
                    <input type="date" name="akhir" id="akhir" class="form-control" value="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btnsimpan">Simpan</button>
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
        mDec: '2'
    });

    $('.form').submit(function(e) {
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
                    $('.msg').html(response.error).fadeIn();;
                }
                if (response.sukses) {
                    tampildatakas();
                    $.toast({
                        heading: 'Sukses',
                        text: response.sukses,
                        showHideTransition: 'slide',
                        icon: 'success',
                        position: 'bottom-right'
                    });
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