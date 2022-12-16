<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog" aria-labelledby="modaltambahLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaltambahLabel">Tambah Stok Produk Kadaluarsa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('stokproduk/simpanstokkadaluarsa', ['class' => 'formsimpan']); ?>
            <input type="hidden" name="kode" id="kode" value="<?= $kode; ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label>Tgl.Kadaluarsa</label>
                    <input required type="date" class="form-control form-control-sm" value="<?= date('Y-m-d'); ?>"
                        name="tgl" id="tgl">
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input required type="text" class="form-control form-control-sm" value="" name="jml" id="jml">
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
$(document).ready(function() {
    $('#jml').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '0'
    });

    $('.formsimpan').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('.btnsimpan').prop('disabled', true);
                $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            complete: function() {
                $('.btnsimpan').prop('disabled', false);
                $('.btnsimpan').html('Simpan');
            },
            success: function(response) {
                if (response.sukses) {
                    Swal.fire({
                        icon: 'success',
                        title: `Berhasil`,
                        html: `${response.sukses}`,
                    }).then((result) => {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: xhr.status,
                    html: xhr.responseText + "\n" + thrownError,
                });
            }
        });
        return false;
    });
});
</script>