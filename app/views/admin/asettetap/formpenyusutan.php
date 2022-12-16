<div class="modal fade" id="modalpenyusutan" tabindex="-1" role="dialog" aria-labelledby="modalpenyusutan"
    aria-hidden="true">
    <div class="modal-dialog modal-lg animated slideInLeft" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalpenyusutan">Penyusutan Akun <?= $noakun; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('aset-tetap/simpanpenyusutan', ['class' => 'formsimpan']) ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Tgl.Aset</label>
                    <div class="col-sm-4">
                        <input type="hidden" name="noakun" id="noakun" value="<?= $noakun; ?>">
                        <input type="date" class="form-control form-control-sm" id="tgl" name="tgl">
                        <div class="invalid-feedback errortgl">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">ID</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm" id="idpenyusutan" name="idpenyusutan"
                            readonly placeholder="Auto">
                        <div class="invalid-feedback erroridpenyusutan">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Harga (Rp)</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control-sm form-control" id="harga" name="harga"
                            autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Keterangan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control-sm form-control" id="ket" name="ket" autocomplete="off"
                            placeholder="Tambahkan Keterangan jika ada...">
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
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function() {
    $('#harga').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

    $('#tgl').change(function() {
        let tgl = $(this).val();
        $.ajax({
            type: "post",
            url: "<?= site_url('aset-tetap/buatidpenyusutan') ?>",
            data: {
                tgl: tgl,
                noakun: $('#noakun').val()
            },
            cache: false,
            dataType: "json",
            success: function(response) {
                if (response.idpenyusutan) {
                    $('#idpenyusutan').val(response.idpenyusutan);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

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
                    if (response.error.tgl) {
                        $('#tgl').addClass('is-invalid');
                        $('.errortgl').html(response.error.tgl)
                    } else {
                        $('#tgl').removeClass('is-invalid');
                        $('.errortgl').html('');
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