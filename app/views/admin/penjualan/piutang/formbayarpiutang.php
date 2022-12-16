<div class="col-lg-12">
    <div class="card border-light animated slideInUp">
        <div class="card-header">
            <button type="button" class="btn btn-warning btn-sm"
                onclick="location.href=('<?= site_url('admin/penjualan/all-data-piutang') ?>')">
                <i class="fa fa-fast-backward"></i> Kembali
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <table class="table table-sm table-striped" style="font-size: 10pt;">
                        <tr>
                            <td style="width: 20%;">Faktur</td>
                            <td style="width: 1%;">:</td>
                            <td><?= $row['jualfaktur']; ?></td>
                        </tr>
                        <tr>
                            <td style="width: 20%;">Tgl.Faktur</td>
                            <td style="width: 1%;">:</td>
                            <td><?= date('d-m-Y H:i:s', strtotime($row['jualtgl'])); ?></td>
                        </tr>
                        <tr>
                            <td>Member</td>
                            <td>:</td>
                            <td><?= $member; ?></td>
                        </tr>
                        <tr>
                            <td>Pelanggan</td>
                            <td>:</td>
                            <td><?= $row['jualnapel']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Kotor (Rp.)</td>
                            <td>:</td>
                            <td style="text-align: right;"><?= number_format($row['jualtotalkotor'], 2, ".", ","); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Diskon (%)</td>
                            <td>:</td>
                            <td style="text-align: right;"><?= number_format($row['jualdispersen'], 2, ".", ","); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Diskon (Rp)</td>
                            <td>:</td>
                            <td style="text-align: right;"><?= number_format($row['jualdisuang'], 2, ".", ","); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Bersih (Rp.)</td>
                            <td>:</td>
                            <td style="text-align: right;"><?= number_format($row['jualtotalbersih'], 2, ".", ","); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Stt.Bayar</td>
                            <td>:</td>
                            <td>
                                <?php
                                if ($row['jualstatuslunas'] == 1) {
                                    echo '<span class="badge badge-success">Lunas,</span> Tgl.Pembayaran .<strong>' . date('d-m-Y', strtotime($row['jualtglbayarkredit'])) . '</strong>';
                                } else {
                                    echo '<span class="badge badge-danger">Belum Lunas,</span> Tgl.Jatuh Tempo .<strong>' . date('d-m-Y', strtotime($row['jualtgljatuhtempo'])) . '</strong>';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-sm-6">
                    <?= form_open('admin/penjualan/simpanbayarpiutang', ['class' => 'formbayar']); ?>
                    <input type="hidden" name="jualfaktur" value="<?= $row['jualfaktur']; ?>">
                    <input type="hidden" name="totalbersih" value="<?= $row['jualtotalbersih']; ?>">
                    <div class="col-lg-12">
                        <div class="card border-light animated zoomInDown">
                            <div class="card-header" style="background-color: #9bfafa; color:#000; font-weight: bold;">
                                Form Pembayaran
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Tgl.Pembayaran</label>
                                    <?php
                                    if ($row['jualtglbayarkredit'] == '' || $row['jualtglbayarkredit'] == NULL) {
                                    ?>
                                    <input type="date" class="form-control form-control-sm"
                                        value="<?= $row['jualtglbayarkredit']; ?>" name="tglbayar" id="tglbayar">
                                    <?php
                                    } else {

                                    ?>
                                    <input type="date" class="form-control form-control-sm"
                                        value="<?= date('Y-m-d'); ?>" name="tglbayar" id="tglbayar">
                                    <?php
                                    }
                                    ?>
                                    <div class="invalid-feedback errortglbayar">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="">Jumlah Pembayaran</label>
                                    <input type="text" name="jmlbayar" id="jmlbayar"
                                        class="form-control-sm form-control" value="<?= $row['jualjmlbayarkredit']; ?>">
                                    <div class="invalid-feedback errorjmlbayar">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Keterangan</label>
                                    <input type="text" name="ket" id="ket" value="<?= $row['jualketkredit']; ?>"
                                        class="form-control-sm form-control">
                                    <div class="invalid-feedback errorket">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit"
                                        class="btnsimpan btn btn-sm btn-success btn-block">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function() {
    //setting currency
    $('#jmlbayar').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });

    $('.formbayar').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function(e) {
                $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
                $('.btnsimpan').prop('disabled', true);
            },
            complete: function(e) {
                $('.btnsimpan').html('Simpan');
                $('.btnsimpan').prop('disabled', false);
            },
            success: function(response) {
                if (response.error) {
                    if (response.error.tglbayar) {
                        $('#tglbayar').addClass('is-invalid');
                        $('.errortglbayar').html(response.error.tglbayar)
                    } else {
                        $('#tglbayar').removeClass('is-invalid');
                        $('.errortglbayar').html('');
                    }

                    if (response.error.jmlbayar) {
                        $('#jmlbayar').addClass('is-invalid');
                        $('.errorjmlbayar').html(response.error.jmlbayar)
                    } else {
                        $('#jmlbayar').removeClass('is-invalid');
                        $('.errorjmlbayar').html('');
                    }

                    if (response.error.ket) {
                        $('#ket').addClass('is-invalid');
                        $('.errorket').html(response.error.ket)
                    } else {
                        $('#ket').removeClass('is-invalid');
                        $('.errorket').html('');
                    }

                } else {
                    Swal.fire({
                        title: `${response.sukses}`,
                        icon: 'success',
                    }).then((result) => {
                        if (result.value) {
                            window.location =
                                "<?= site_url('admin/penjualan/all-data-piutang') ?>";
                        }
                    })
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