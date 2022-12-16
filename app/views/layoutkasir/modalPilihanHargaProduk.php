<!-- Modal -->
<div class="modal fade" id="modal_pilihanHargaProduk" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Ganti Harga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('kasir/updateHargaPenjualanKasir', ['class' => 'frmupdateharga']); ?>
            <div class="modal-body">
                <table class="table table-sm table-striped" style="width: 100%;">
                    <tr>
                        <td style="width: 15%;">Kode Barcode</td>
                        <td style="width: 2%;">:</td>
                        <td style="width: 83%;"><?= $kode ?>
                            <input type="hidden" name="gantiKodeBarcode" value="<?= $kode ?>">
                            <input type="hidden" name="gantiId" value="<?= $id ?>">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 15%;">Nama Produk</td>
                        <td style="width: 2%;">:</td>
                        <td style="width: 83%;"><?= $namaproduk ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: left;padding-left: 10px;">
                            <input type="radio" name="piliharga" value="<?= $hargaeceran ?>"> Harga Eceran
                            <br>
                            <input type="radio" name="piliharga" value="<?= $hargagrosiran ?>"> Harga Reseller
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btnsimpan">Ganti Harga</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.frmupdateharga').submit(function(e) {
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
                if (response.sukses) {
                    $.toast({
                        heading: 'Berhasil',
                        text: response.sukses,
                        position: 'mid-center',
                        stack: false,
                        showHideTransition: 'slide',
                        icon: 'success',
                    });
                    tampildatatemppenjualan();
                    $('#modal_pilihanHargaProduk').modal('hide');
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