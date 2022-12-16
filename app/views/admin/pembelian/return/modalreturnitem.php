<link href="<?= base_url() ?>assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>assets/plugins/select2/select2.min.js"></script>
<!-- Modal -->
<?php
$row = $data->row_array();
?>
<div class="modal fade" id="modalreturn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl slideInRight animated" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Return Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('beli/simpan_transaksi_return', ['class' => 'formreturn']) ?>
            <input type="hidden" name="id" value="<?= $row['detid']; ?>">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Faktur</label>
                            <input type="text" readonly class="form-control form-control-sm" name="nofaktur"
                                value="<?= $row['nofaktur']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="">Tgl.Faktur</label>
                            <input type="text" readonly class="form-control form-control-sm"
                                value="<?= $row['tglbeli']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="">Pemasok</label>
                            <input type="text" readonly class="form-control form-control-sm"
                                value="<?= $row['nama']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="">Produk</label>
                            <input type="text" readonly class="form-control form-control-sm"
                                value="<?= $row['namaproduk']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="">Qty Item Pembelian</label>
                            <input type="text" readonly class="form-control form-control-sm"
                                value="<?= $row['detjml'] . '/' . $row['satnama']; ?>">
                            <input type="hidden" name="qty" id="qty" value="<?= $row['detjml']; ?>">
                            <input type="hidden" name="detjmlreturn" id="detjmlreturn"
                                value="<?= $row['detjmlreturn']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="">Jml Item Yang Telah di Return</label>
                            <input type="text" readonly class="form-control form-control-sm"
                                value="<?= $row['detjmlreturn']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">ID Return</label>
                            <input type="text" readonly class="form-control form-control-sm" name="idreturn"
                                value="<?= $idreturn; ?>" id="idreturn">
                        </div>
                        <div class="form-group">
                            <label for="">Tgl.Return</label>
                            <input type="date" class="form-control-sm form-control" name="tglreturn" id="tglreturn"
                                value="<?= date('Y-m-d') ?>" id="tglreturn">
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
                                <select name="stt" id="stt" class="form-control form-control-sm" style="width: 100%;">
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
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
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
        url: "<?= site_url('beli/tampildata_statusreturn') ?>",
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
                        tampilkanitemfaktur();
                        $('#modalreturn').modal('hide');
                    });
                }
                // else {
                //     $.toast({
                //         heading: 'Berhasil',
                //         text: `${response.sukses}`,
                //         icon: 'success',
                //         loader: true,
                //         loaderBg: '#9EC600',
                //         position: 'top-right'
                //     });
                //     tampilforminput();
                //     datadetailpembelian();
                // }
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
            url: "<?= site_url('admin/pembelian/buatnomor_return_lagi') ?>",
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