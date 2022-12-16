<link href="<?= base_url() ?>assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>assets/plugins/select2/select2.min.js"></script>
<?php $r = $datadetail->row_array(); ?>
<div class="modal fade" id="modalreturnproduk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl animated slideInDown" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Return Item Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/penjualan/simpandatareturn', ['class' => 'formreturn']) ?>
            <input type="hidden" name="jualfaktur" id="jualfaktur" value="<?= $r['detjualfaktur'] ?>">
            <input type="hidden" name="id" id="id" value="<?= $r['detjualid'] ?>">
            <input type="hidden" name="qty" id="qty" value="<?= $r['detjualjml'] ?>">
            <input type="hidden" name="kodebarcode" id="kodebarcode" value="<?= $r['kodebarcode'] ?>">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-striped">
                                <tr>
                                    <td style="width: 25%;">Faktur </td>
                                    <td style="width: 1%;">:</td>
                                    <td>
                                        <?= $r['detjualfaktur']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kode Produk</td>
                                    <td>:</td>
                                    <td>
                                        <?= $r['kodebarcode']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nama Produk</td>
                                    <td>:</td>
                                    <td>
                                        <?= $r['namaproduk']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jml / Satuan</td>
                                    <td>:</td>
                                    <td>
                                        <?= $r['detjualjml'] . '/' . $r['satnama']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Harga Jual (Rp)</td>
                                    <td>:</td>
                                    <td style="text-align: right;">
                                        <?= number_format($r['detjualharga'], 2, ".", ","); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sub Total (Rp)</td>
                                    <td>:</td>
                                    <td style="text-align: right;">
                                        <?= number_format($r['detjualsubtotal'], 2, ".", ","); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">ID Return</label>
                                <input type="text" class="form-control-sm form-control" name="idreturn" id="idreturn"
                                    value="<?= $idreturn; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Tgl.Return</label>
                                <input type="date" class="form-control-sm form-control" name="tglreturn" id="tglreturn"
                                    value="<?= date('Y-m-d') ?>">
                                <div class="invalid-feedback errortglreturn">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Jumlah Return</label>
                                <input type="text" class="form-control-sm form-control" name="jmlreturn" id="jmlreturn">
                                <div class="invalid-feedback errorjmlreturn">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Status Return</label>
                                <div class="input-group mb-3">
                                    <select name="stt" id="stt" class="form-control form-control-sm"
                                        style="width: 100%;">
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-sm tambahstatusreturn" type="button">
                                            <i class="fa fa-plus-square"></i>
                                        </button>&nbsp;
                                        <button class="btn btn-danger btn-sm hapusstatusreturn" type="button">
                                            <i class="fa fa-minus-circle"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback errorstt">
                                    </div>
                                </div>
                                <p>
                                    <span class="badge badge-info">Klik Tombol Tambah, jika status tidak ada.</span>
                                </p>
                            </div>

                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <input type="text" class="form-control-sm form-control" name="ket" id="ket">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btnsimpan">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
function tampildata_statusreturn() {
    $.ajax({
        url: "<?= site_url('admin/penjualan/tampildata_statusreturn') ?>",
        dataType: 'json',
        success: function(response) {
            $('#stt').html(response.data);
            $('#stt').select2({
                tags: true
            });
        }
    });
}
$(document).ready(function() {
    tampildata_statusreturn();
    //setting currency
    $('#jmlreturn').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

    $('.tambahstatusreturn').click(function(e) {
        e.preventDefault();
        let stt = $('#stt').val();

        if (stt.length == 0) {
            return false;
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('beli/simpanbaru_statusreturn') ?>",
                data: {
                    stt: stt
                },
                dataType: "json",
                success: function(response) {
                    tampildata_statusreturn();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }

    });

    $('.hapusstatusreturn').click(function(e) {
        e.preventDefault();
        let stt = $('#stt').val();
        $.ajax({
            type: "post",
            url: "<?= site_url('beli/hapus_statusreturn') ?>",
            data: {
                stt: stt
            },
            dataType: "json",
            success: function(response) {
                tampildata_statusreturn();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

    $('.formreturn').submit(function(e) {
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
                    if (response.error.tglreturn) {
                        $('#tglreturn').addClass('is-invalid');
                        $('.errortglreturn').html(response.error.tglreturn)
                    } else {
                        $('#tglreturn').removeClass('is-invalid');
                        $('.errortglreturn').html('');
                    }
                    if (response.error.jmlreturn) {
                        $('#jmlreturn').addClass('is-invalid');
                        $('.errorjmlreturn').html(response.error.jmlreturn)
                    } else {
                        $('#jmlreturn').removeClass('is-invalid');
                        $('.errorjmlreturn').html('');
                    }

                    if (response.error.stt) {
                        $('#stt').addClass('is-invalid');
                        $('.errorstt').html(response.error.stt)
                    } else {
                        $('#stt').removeClass('is-invalid');
                        $('.errorstt').html('');
                    }
                }

                if (response.sukses) {
                    Swal.fire({
                        icon: 'success',
                        title: `Berhasil`,
                        text: `${response.sukses}`,
                    }).then((result) => {
                        tampildetailitempenjualan();
                        $('#modalreturnproduk').modal('hide');
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
        return false;
    });

    $('#tglreturn').change(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "<?= site_url('admin/penjualan/buatnomor_return_lagi') ?>",
            data: {
                tglreturn: $(this).val()
            },
            dataType: "json",
            success: function(response) {
                $('#idreturn').val(response.idreturn);
            }
        });
    });
});
</script>