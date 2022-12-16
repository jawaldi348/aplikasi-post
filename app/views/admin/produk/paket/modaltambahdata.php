<!-- Modal -->
<div class="modal fade" id="modaltambahdata" tabindex="-1" role="dialog" aria-labelledby="modaltambahdataLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaltambahdataLabel">Tambah Produk Paket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/produk/paketsimpandata', ['class' => 'formsimpan']); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Kode Produk</label>
                    <input type="text" class="form-control-sm form-control" name="kodeproduk" id="kodeproduk">
                    <div class="invalid-feedback errorkodeproduk">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Nama Paket</label>
                    <input type="text" class="form-control-sm form-control" name="namapaket" id="namapaket">
                    <div class="invalid-feedback errornamapaket">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm btnsimpan">Simpan</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
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
            success: function(response) {
                if (response.error) {
                    if (response.error.kodeproduk) {
                        $('#kodeproduk').addClass('is-invalid');
                        $('.errorkodeproduk').html(response.error.kodeproduk)
                    } else {
                        $('#kodeproduk').removeClass('is-invalid');
                        $('.errorkodeproduk').html('');
                    }
                    if (response.error.namapaket) {
                        $('#namapaket').addClass('is-invalid');
                        $('.errornamapaket').html(response.error.namapaket)
                    } else {
                        $('#namapaket').removeClass('is-invalid');
                        $('.errornamapaket').html('');
                    }

                } else {
                    Swal.fire({
                        icon: 'success',
                        html: `${response.sukses}`,
                        title: 'Berhasil',
                    }).then((result) => {
                        if (result.value) {
                            tampildataprodukpaket();
                        }
                    });
                    $('#modaltambahdata').modal('hide');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
        return false;
    });
});
</script>