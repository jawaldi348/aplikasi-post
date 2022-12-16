<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-warning btn-sm"
                onclick="window.location='<?= site_url('admin/produk/paket') ?>'">
                &laquo; Kembali
            </button>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <td style="width: 25%;">Kode Paket</td>
                                    <td style="width: 1%;">:</td>
                                    <td>
                                        <?= $row['kodebarcode']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nama Paket</td>
                                    <td>:</td>
                                    <td><?= $row['namaproduk']; ?></td>
                                </tr>
                                <tr>
                                    <td>Stok Tersedia</td>
                                    <td>:</td>
                                    <td>
                                        <?= number_format($row['stok_tersedia'], 0, ",", "."); ?>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <td style="width: 35%;">Harga Modal/Beli</td>
                                    <td style="width: 1%;">:</td>
                                    <td style="text-align: right;">
                                        <?= number_format($row['harga_beli_eceran'], 2, ",", "."); ?></td>
                                </tr>
                                <tr>
                                    <td>Harga Jual</td>
                                    <td>:</td>
                                    <td style="text-align: right;">
                                        <?= number_format($row['harga_jual_eceran'], 2, ",", "."); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <button type="button" class="btn btn-block btn-primary btn-sm btnedit">
                                            Edit Harga dan Stok
                                        </button>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <?= form_open('admin/produk/paketsimpanitem', ['class' => 'formsimpan']) ?>
                        <input type="hidden" name="idproduk" id="idproduk" value="<?= $row['id']; ?>">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Kode</th>
                                    <th>Item Produk</th>
                                    <th style="width: 10%;">Jml</th>
                                    <th style="width: 15%;">Harga Beli</th>
                                    <th style="width: 15%;">Harga Jual</th>
                                    <th style="width: 3%;">#</th>

                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="kodeproduk"
                                            id="kodeproduk" autofocus="autofocus">
                                        <div class="invalid-feedback errorkodeproduk">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="namaproduk"
                                            id="namaproduk" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="jml" id="jml"
                                            value="1">
                                        <div class="invalid-feedback errorjml">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="hargabeli"
                                            id="hargabeli" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="hargajual"
                                            id="hargajual" readonly>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-sm btn-success btnsimpan"
                                            title="Tambahkan Item">
                                            <i class="fa fa-save"></i>
                                        </button>
                                    </td>
                                </tr>
                            </thead>
                            <tbody class="dataitemproduk" style="display: none;">

                            </tbody>
                        </table>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="viewmodalproduk" style="display: none;"></div>
<div class="viewmodaleditharga" style="display: none;"></div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
function kosonginput() {
    $('#kodeproduk').val('');
    $('#namaproduk').val('');
    $('#jml').val('1');
    $('#hargabeli').val('');
    $('#hargajual').val('');
    $('#kodeproduk').focus();
}

function tampildataitemproduk() {
    let idproduk = $('#idproduk').val();
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/produk/tampildataitemproduk') ?>",
        data: {
            idproduk: idproduk
        },
        dataType: "json",
        beforeSend: function() {
            $('.dataitemproduk').html('<i class="fa fa-spinner fa-spin"></i>').show();
        },
        success: function(response) {
            if (response.data) {
                $('.dataitemproduk').html(response.data).show();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}

function tampilseluruhdataproduk() {
    $.ajax({
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        url: "<?= site_url('admin/produk/paketmodaldataproduk') ?>",
        success: function(response) {
            if (response.data) {
                $('.viewmodalproduk').html(response.data).show();
                $('#modalcariproduk').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}
$(document).ready(function() {
    tampildataitemproduk();

    $('#jml').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

    $(this).keydown(function(e) {
        if (e.keyCode === 27) {
            e.preventDefault();
            $('#kodeproduk').focus();
        }
    });

    // Cari Kode Produk
    $('#kodeproduk').keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            let kode = $(this).val();
            let namaproduk = $('#namaproduk').val();

            if (kode.length === 0) {
                tampilseluruhdataproduk();
            } else {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('admin/produk/paketambildetailproduk') ?>",
                    data: {
                        kode: kode,
                        namaproduk: namaproduk
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.datasatu) {
                            let x = response.datasatu;
                            $('#kodeproduk').val(x.kodeproduk);
                            $('#namaproduk').val(x.namaproduk);
                            $('#hargabeli').val(x.hargabeli);
                            $('#hargajual').val(x.hargajual);
                            $('#jml').focus();
                        }

                        if (response.databanyak) {
                            $('.viewmodalproduk').html(response.databanyak).show();
                            $('#modaldataproduk').modal('show');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            }
        }
    });

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
                    if (response.error.jml) {
                        $('#jml').addClass('is-invalid');
                        $('.errorjml').html(response.error.jml)
                    } else {
                        $('#jml').removeClass('is-invalid');
                        $('.errorjml').html('');
                    }

                } else {
                    // Swal.fire({
                    //     icon: 'success',
                    //     html: `${response.sukses}`,
                    //     title: 'Berhasil',
                    // }).then((result) => {
                    //     if (result.value) {
                    //         tampildataprodukpaket();
                    //     }
                    // });
                    $.toast({
                        heading: 'Berhasil',
                        text: `${response.sukses}`,
                        showHideTransition: 'plain',
                        icon: 'success'
                    });
                    tampildataitemproduk();
                    kosonginput();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
        return false;
    });

    $('.btnedit').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "<?= site_url('admin/produk/paketmodaleditharga') ?>",
            data: {
                idproduk: $('#idproduk').val()
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodaleditharga').html(response.data).show();
                    $('#modaledithargaprodukpaket').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
});
</script>