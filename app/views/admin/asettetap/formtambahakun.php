<!-- Modal -->
<div class="modal fade" id="modaltambahakun" tabindex="-1" role="dialog" aria-labelledby="modaltambahakun"
    aria-hidden="true">
    <div class="modal-dialog modal-lg animated zoomInUp" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaltambahakun">Tambah Akun Aset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('aset-tetap/simpanakun', ['class' => 'formsimpan']) ?>
            <div class="modal-body">
                <div class="alert alert-info"><i class="fa fa-info"></i> Silahkan tambahkan akun aset. Untuk No.Akun
                    Harus dimulai dengan angka <strong>1-2xx atau 1-3xx</strong></div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">No.Akun</label>
                    <div class="col-sm-1">
                        <input type="text" class="form-control form-control-sm" id="noakunawal" value="1-"
                            name="noakunawal" readonly>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control form-control-sm" id="noakunakhir"
                            placeholder="Isi : 2xx atau 3xx" name="noakunakhir">
                        <div class="invalid-feedback errornoakunakhir">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Nama Akun</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm" id="namaakun" name="namaakun">
                        <div class="invalid-feedback errornamaakun">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Akun Penyusutan ?</label>
                    <div class="col-sm-10">
                        <select name="penyusutan" id="penyusutan" class="form-contro form-control-sm">
                            <option value="" selected>-Pilih-</option>
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                        <div class="invalid-feedback errorpenyusutan">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="success" class="btn btn-success btnsimpan">Simpan</button>
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
                $('.btnsimpan').prop('disabled', true);
                $('.btnsimpan').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            complete: function() {
                $('.btnsimpan').prop('disabled', false);
                $('.btnsimpan').html('Simpan');
            },
            success: function(response) {
                if (response.error) {
                    if (response.error.namaakun) {
                        $('#namaakun').addClass('is-invalid');
                        $('.errornamaakun').html(response.error.namaakun)
                    } else {
                        $('#namaakun').removeClass('is-invalid');
                        $('.errornamaakun').html('');
                    }
                    if (response.error.noakunakhir) {
                        $('#noakunakhir').addClass('is-invalid');
                        $('.errornoakunakhir').html(response.error.noakunakhir)
                    } else {
                        $('#noakunakhir').removeClass('is-invalid');
                        $('.errornoakunakhir').html('');
                    }
                    if (response.error.penyusutan) {
                        $('#penyusutan').addClass('is-invalid');
                        $('.errorpenyusutan').html(response.error.penyusutan)
                    } else {
                        $('#penyusutan').removeClass('is-invalid');
                        $('.errorpenyusutan').html('');
                    }
                }

                if (response.sukses) {
                    Swal.fire({
                        icon: 'success',
                        title: `Berhasil`,
                        text: `${response.sukses}`,
                    }).then((result) => {
                        window.location.reload();
                    });
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