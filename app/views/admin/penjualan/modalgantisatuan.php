<div class="modal fade bd-example-modal-lg" id="modalgantisatuan" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Satuan Produk : <?= $namaproduk; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/penjualan/updategantisatuan', ['class' => 'formsimpan']) ?>

            <input type="hidden" name="jualfaktur" id="jualfaktur" value="<?= $jualfaktur; ?>">
            <input type="hidden" name="id_tempjual" id="id_tempjual" value="<?= $id_tempjual; ?>">
            <input type="hidden" name="jualjml" id="jualjml" value="<?= $jualjml; ?>">
            <div class="modal-body">
                <div class="msg" style="display: none;"></div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Pilih Satuan</label>
                    <div class="col-sm-9">
                        <select name="satuan" id="satuan" class="form-control">
                            <option value="">-Pilih-</option>
                            <?php foreach ($satuanprodukharga->result_array() as $row) : ?>
                            <option value="<?= $row['id']; ?>"><?= $row['satnama']; ?></option>
                            <?php endforeach; ?>
                        </select>
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
<script>
$(document).ready(function() {
    $('.formsimpan').submit(function(e) {
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
                    $('.msg').html(response.error).show();
                }
                if (response.sukses) {
                    $.toast({
                        heading: 'Berhasil',
                        text: response.sukses,
                        showHideTransition: 'slide',
                        icon: 'success',
                        position: 'bottom-center'
                    });
                    $('#modalgantisatuan').modal('hide');
                    tampildatatemppenjualan();
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

    // Enter pada input select satuan
    $('#satuan').keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault(e);
            $('.btnsimpan').focus();
        }
    });
});

function tampildatatemppenjualan() {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/penjualan/tampildatatemp') ?>",
        data: {
            jualfaktur: $('#faktur').val()
        },
        beforeSend: function() {
            $('.viewtampildetailpenjualan').html('<i class="fa fa-spin fa-spinner"></i> Tunggu').show();
        },
        success: function(response) {
            $('.viewtampildetailpenjualan').html(response).show();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}
</script>