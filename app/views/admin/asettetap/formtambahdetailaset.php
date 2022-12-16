<div class="modal fade" id="modaltambahaset" tabindex="-1" role="dialog" aria-labelledby="modaltambahaset"
    aria-hidden="true">
    <div class="modal-dialog modal-lg animated slideInLeft" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaltambahaset">Tambah Akun Aset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('aset-tetap/simpandetailaset', ['class' => 'formsimpan']) ?>
            <div class="modal-body">
                <div class="alert alert-info"><i class="fa fa-info"></i> Silahkan tambahkan aset. Untuk No.Akun
                    <strong><?= $noakun; ?></strong>, Nama Akun <strong><?= $namaakun; ?></strong>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Tgl.Aset</label>
                    <div class="col-sm-4">
                        <input type="hidden" name="noakun" id="noakun" value="<?= $noakun; ?>">
                        <input type="hidden" name="akunpenyusutan" id="akunpenyusutan" value="<?= $akunpenyusutan; ?>">
                        <input type="date" class="form-control form-control-sm" id="tglaset" name="tglaset">
                        <div class="invalid-feedback errortglaset">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">ID</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm" id="idaset" name="idaset" readonly
                            placeholder="Auto">
                        <div class="invalid-feedback erroridaset">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Nama Aset</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm" id="namaaset" name="namaaset">
                        <div class="invalid-feedback errornamaaset">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Jumlah</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control-sm form-control" id="jmlaset" name="jmlaset"
                            autocomplete="off">
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
                    <label for="" class="col-sm-2 col-form-label">Sub.Total</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control-sm form-control" id="subtotal" name="subtotal"
                            autocomplete="off" disabled>
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
    $('#subtotal').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

    $('#harga').keyup(function(e) {
        let jml = document.getElementById('jmlaset').value;
        let harga = document.getElementById('harga').value;
        subtotal = parseInt(jml) * parseInt(harga);

        $('#subtotal').autoNumeric('set', subtotal);
    });

    $('#tglaset').change(function() {
        let tgl = $(this).val();
        $.ajax({
            type: "post",
            url: "<?= site_url('aset-tetap/buatid') ?>",
            data: {
                tgl: tgl,
                noakun: $('#noakun').val()
            },
            cache: false,
            dataType: "json",
            success: function(response) {
                if (response.idaset) {
                    $('#idaset').val(response.idaset);
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
                    if (response.error.tglaset) {
                        $('#tglaset').addClass('is-invalid');
                        $('.errortglaset').html(response.error.tglaset)
                    } else {
                        $('#tglaset').removeClass('is-invalid');
                        $('.errortglaset').html('');
                    }
                    if (response.error.idaset) {
                        $('#idaset').addClass('is-invalid');
                        $('.erroridaset').html(response.error.idaset)
                    } else {
                        $('#idaset').removeClass('is-invalid');
                        $('.erroridaset').html('');
                    }
                    if (response.error.namaaset) {
                        $('#namaaset').addClass('is-invalid');
                        $('.errornamaaset').html(response.error.namaaset)
                    } else {
                        $('#namaaset').removeClass('is-invalid');
                        $('.errornamaaset').html('');
                    }

                }

                if (response.sukses) {
                    Swal.fire({
                        icon: 'success',
                        title: `Berhasil`,
                        text: `${response.sukses}`,
                    }).then((result) => {
                        $('#modaltambahaset').modal('hide');
                        tampildatadetail();
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