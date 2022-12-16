<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<link href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
<div class="modal fade bd-example-modal-lg" id="modaledititem" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Item Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/pembelian/updateitem', ['class' => 'formedititem']) ?>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="table-responsive text-nowrap">
                    <div class="msg" style="display: none;"></div>
                    <!--Table-->
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td style="width: 20%;">Kode Barcode</td>
                                <td style="width: 1%;">:</td>
                                <td><?= $kodebarcode; ?></td>
                            </tr>
                            <tr>
                                <td>Nama Produk</td>
                                <td>:</td>
                                <td><?= $namaproduk; ?></td>
                            </tr>
                            <tr>
                                <td>Satuan</td>
                                <td>:</td>
                                <td>
                                    <?= $namasatuan; ?>
                                    <input type="hidden" name="idsatuan" value="<?= $idsatuan; ?>">
                                    <input type="hidden" name="jmldefault" value="<?= $jmldefault; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>Tgl.Kadaluarsa</td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="tgled" id="tgled" value="<?= $tgled; ?>"
                                        readonly="readonly" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>Harga Beli (Rp)</td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="hargabeli" id="hargabeli" value="<?= $hargabeli; ?>"
                                        class="form-control"
                                        title="Jika ingin meng-edit harga ini, langsung tekan enter untuk mengupdate"
                                        data-toggle="tooltip" data-placement="top">
                                </td>
                            </tr>
                            <tr>
                                <td>Harga Jual (Rp)</td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="hargajual" id="hargajual" value="<?= $hargajual; ?>"
                                        class="form-control"
                                        title="Jika ingin meng-edit harga ini, langsung tekan enter untuk mengupdate"
                                        data-toggle="tooltip" data-placement="top">
                                </td>
                            </tr>
                            <tr>
                                <td>Jumlah Beli</td>
                                <td>:</td>
                                <td>
                                    <input type="text" value="<?= $jmlbeli; ?>" id="jmlbeli" name="jmlbeli"
                                        class="form-control">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success waves-effect waves-light btnsimpan">Update</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/moment.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.js"></script>
<script>
$(document).ready(function() {
    // $('[data-toggle="tooltip"]').tooltip();
    // $('#tgled').bootstrapMaterialDatePicker({
    //     weekStart: 0,
    //     time: false,
    //     month: true
    // });

    $('#hargabeli').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });

    $('#hargajual').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    $('#jmlbeli').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });

    $('.formedititem').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Update Item',
            text: "Yakin di lanjutkan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function(e) {
                        $('.btnsimpan').attr('disabled', 'disabled');
                        $('.btnsimpan').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function(e) {
                        $('.btnsimpan').removeAttr('disabled');
                        $('.btnsimpan').html('Update');
                    },
                    success: function(response) {
                        if (response.error) {
                            $('.msg').html(response.error).show();
                        } else {
                            $.toast({
                                heading: 'Berhasil',
                                text: response.sukses,
                                showHideTransition: 'slide',
                                icon: 'success',
                                position: 'top-center'
                            });
                            $('#modaledititem').on('hidden.bs.modal', function(e) {
                                tampildatadetail();
                            })
                            $('#modaledititem').modal('hide');
                        }

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            }
        })
        return false;
    });
});
</script>