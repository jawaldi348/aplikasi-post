<link href="<?= base_url() ?>assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>assets/plugins/select2/select2.min.js"></script>
<!-- Modal -->
<div class="modal fade" id="modalforminputproduk" tabindex="-1" role="dialog"
    aria-labelledby="modalforminputprodukLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg animated slideInUp" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Produk/Barang Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('beli/simpanprodukbaru', ['class' => 'formsimpanprodukbaru']) ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Kode Barcode/Produk<sup style="color: red;">*</sup></label>
                    <div class="col-sm-4">
                        <input class="form-control form-control-sm" type="text" autocomplete="off" name="produkkode"
                            id="produkkode">
                        <div class="invalid-feedback errorprodukkode">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Nama Produk<sup style="color: red;">*</sup></label>
                    <div class="col-sm-8">
                        <input class="form-control form-control-sm" type="text"
                            placeholder="Isikan Dengan Lengkap Nama Produk" name="produknama" id="produknama">
                        <div class="invalid-feedback errorproduknama">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Satuan <sup style="color: red;">*</sup></label>
                    <div class="col-sm-4">
                        <select name="produksatuan" id="produksatuan" class="form-control form-control-sm"
                            style="width: 100%;">
                        </select>
                        <div class="invalid-feedback errorproduksatuan">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-sm btn-primary btntambahsatuanbaru">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Kategori <sup style="color: red;">*</sup></label>
                    <div class="col-sm-4">
                        <select name="produkkategori" id="produkkategori" class="form-control form-control-sm"
                            style="width: 100%;">
                        </select>
                        <div class="invalid-feedback errorprodukkategori">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-sm btn-primary btntambahkategoribaru">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Stok Tersedia</label>
                    <div class="col-sm-4">
                        <input class="form-control form-control-sm" type="text" name="produkstoktersedia"
                            id="produkstoktersedia" value="0">
                        <div class="invalid-feedback errorprodukstoktersedia">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Harga Modal/Beli</label>
                    <div class="col-sm-4">
                        <input class="form-control form-control-sm" type="text" name="produkhargamodal"
                            id="produkhargamodal">
                        <div class="invalid-feedback errorprodukhargamodal">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Margin(%)</label>
                    <div class="col-sm-4">
                        <input class="form-control form-control-sm" type="text" name="produkmargin" id="produkmargin">
                        <div class="invalid-feedback errorprodukmargin">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Harga Jual</label>
                    <div class="col-sm-4">
                        <input class="form-control form-control-sm" type="text" name="produkhargajual"
                            id="produkhargajual">
                        <div class="invalid-feedback errorprodukhargajual">
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btnsimpan">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
function tampildatasatuanproduk() {
    $.ajax({
        url: "<?= site_url('beli/tampildatasatuanproduk') ?>",
        dataType: 'json',
        success: function(response) {
            if (response.data) {
                $('#produksatuan').html(response.data);
                $('#produksatuan').select2({
                    tags: true
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function tampildatakategoriproduk() {
    $.ajax({
        url: "<?= site_url('beli/tampildatakategoriproduk') ?>",
        dataType: 'json',
        success: function(response) {
            if (response.data) {
                $('#produkkategori').html(response.data);
                $('#produkkategori').select2({
                    tags: true
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function() {
    tampildatasatuanproduk();
    tampildatakategoriproduk();

    //setting currency
    $('#produkstoktersedia').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '0'
    });
    //setting currency
    $('#produkhargamodal').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '2'
    });
    //setting currency
    $('#produkhargajual').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '2'
    });
    //setting currency
    $('#produkmargin').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });


    $('.btntambahsatuanbaru').click(function(e) {
        e.preventDefault();
        let produksatuan = $('#produksatuan').val();

        if (produksatuan.length == 0) {
            return false;
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('beli/simpanbaru_satuan') ?>",
                data: {
                    produksatuan: produksatuan
                },
                dataType: "json",
                success: function(response) {
                    tampildatasatuanproduk();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });
    $('.btntambahkategoribaru').click(function(e) {
        e.preventDefault();
        let produkkategori = $('#produkkategori').val();

        if (produkkategori.length == 0) {
            return false;
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('beli/simpanbaru_kategori') ?>",
                data: {
                    produkkategori: produkkategori
                },
                dataType: "json",
                success: function(response) {
                    tampildatakategoriproduk();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });

    $('.formsimpanprodukbaru').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.error) {
                    if (response.error.produkkode) {
                        $('#produkkode').addClass('is-invalid');
                        $('.errorprodukkode').html(response.error.produkkode)
                    } else {
                        $('#produkkode').removeClass('is-invalid');
                        $('.errorprodukkode').html('');
                    }
                    if (response.error.produknama) {
                        $('#produknama').addClass('is-invalid');
                        $('.errorproduknama').html(response.error.produknama)
                    } else {
                        $('#produknama').removeClass('is-invalid');
                        $('.errorproduknama').html('');
                    }

                    if (response.error.produksatuan) {
                        $('#produksatuan').addClass('is-invalid');
                        $('.errorproduksatuan').html(response.error.produksatuan)
                    } else {
                        $('#produksatuan').removeClass('is-invalid');
                        $('.errorproduksatuan').html('');
                    }

                    if (response.error.produkhargamodal) {
                        $('#produkhargamodal').addClass('is-invalid');
                        $('.errorprodukhargamodal').html(response.error.produkhargamodal)
                    } else {
                        $('#produkhargamodal').removeClass('is-invalid');
                        $('.errorprodukhargamodal').html('');
                    }
                    if (response.error.produkhargajual) {
                        $('#produkhargajual').addClass('is-invalid');
                        $('.errorprodukhargajual').html(response.error.produkhargajual)
                    } else {
                        $('#produkhargajual').removeClass('is-invalid');
                        $('.errorprodukhargajual').html('');
                    }

                } else {
                    $.toast({
                        heading: 'Berhasil',
                        text: `${response.sukses}`,
                        icon: 'success',
                        loader: true,
                        loaderBg: '#9EC600',
                        position: 'top-right'
                    });

                    $('#modalforminputproduk').on('hidden.bs.modal', function(e) {
                        $('#kode').val(response.kodeproduk);
                        ambildetaildataproduk(response.kodeproduk);
                    });
                    $('#modalforminputproduk').modal('hide');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
        return false;
    });
});

$(document).on('keyup', '#produkmargin', function(e) {
    let margin = $(this).val();
    let hargabeli = $('#produkhargamodal').val();
    let konversi_hargabeli = hargabeli.replace(".", "");

    hitung_hargajual = parseFloat(konversi_hargabeli) + ((parseFloat(konversi_hargabeli) *
        parseFloat(margin)) / 100);

    $('#produkhargajual').autoNumeric('set', hitung_hargajual);
});

$(document).on('keyup', '#produkhargajual', function(e) {
    let hargajual = $(this).autoNumeric('get');
    let hargabeli = $('#produkhargamodal').autoNumeric('get');

    let hitunglaba;
    hitunglaba = parseFloat(hargajual) - parseFloat(hargabeli);

    let margin;
    margin = (hitunglaba / hargabeli) * 100;

    $('#produkmargin').autoNumeric('set', margin);
});
</script>