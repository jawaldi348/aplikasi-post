<div class="col-sm-12">
    <div class="card m-b-30">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    Input Item Produk/Barang
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-info btnreload" title="Refresh Form">
                        <i class="fa fa-redo-alt"></i> Reload Form
                    </button>
                </div>
            </div>

        </div>
        <div class="card-body">

            <?= form_open('beli/simpanitem', ['class' => 'formsimpanitem']) ?>
            <table class="table table-sm table-striped table-sm" style="width:100%; font-size:10pt;">
                <input type="hidden" value="<?= $faktur ?>" name="faktur" id="faktur">
                <input type="hidden" value="<?= $tglfaktur ?>" name="tglfaktur" id="tglfaktur">
                <input type="hidden" name="iddetail" id="iddetail">
                <thead>
                    <tr>
                        <th style="width:15%;">Kode</th>
                        <th>Produk</th>
                        <th style="width:10%;">Hrg.Beli</th>
                        <th style="width: 5%;">Margin(%)</th>
                        <th style="width:10%;">Hrg.Jual</th>
                        <th style="width: 5%;">
                            Jml
                        </th>
                        <th style="width: 10%;">Satuan</th>
                    </tr>
                    <tr>
                        <th>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm"
                                    aria-describedby="btnaddprodukbaru" name="kode" id="kode">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary btn-sm" title="Tambah Produk Baru"
                                        type="button" id="btnaddprodukbaru">
                                        <i class="fa fa-plus-square"></i></button>
                                </div>
                            </div>
                            <div class="invalid-feedback errorKode">
                            </div>
                        </th>
                        <th>
                            <input type="text" name="namaproduk" data-toggle="tooltip" data-placement="top"
                                id="namaproduk" class="form-control form-control-sm" readonly="readonly">
                            <div class="invalid-feedback errorNamaProduk">
                            </div>
                        </th>
                        <th>
                            <input type="text" name="hargabeli" id="hargabeli" class="form-control form-control-sm">
                        </th>
                        <th>
                            <input type="text" name="margin" id="margin" class="form-control form-control-sm">
                        </th>
                        <th>
                            <input type="text" name="hargajual" id="hargajual" class="form-control form-control-sm">
                        </th>
                        <th>
                            <input type="text" name="jml" id="jml" class="form-control form-control-sm">
                        </th>
                        <th>
                            <div class="input-group">
                                <input type="text" name="namasatuan" id="namasatuan"
                                    class="form-control form-control-sm" disabled>
                                <input type="hidden" name="idsatuan" id="idsatuan">
                                <input type="hidden" name="qtysatuan" id="qtysatuan">
                                <input type="hidden" name="idprodukharga" id="idprodukharga">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary btn-sm tombolcarisatuan" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </th>
                    </tr>
                </thead>

            </table>
            <table class="table table-sm table-striped table-sm" style="width:100%; font-size:10pt;">
                <tr>
                    <th style="width: 7%;">Dis(%)</th>
                    <th style="width: 7%;">Dis(Rp)</th>
                    <th style="width: 10%;">Sub.Total (Rp)</th>
                    <th style="width: 10%;">Tgl.Expired</th>
                    <th>
                        Aksi
                    </th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>
                        <input type="text" value="0" name="dispersenitem" id="dispersenitem"
                            class="form-control form-control-sm">
                    </th>
                    <th>
                        <input type="text" value="0" name="disuangitem" id="disuangitem"
                            class="form-control form-control-sm">
                    </th>
                    <th>
                        <input readonly type="text" name="subtotalitem" id="subtotalitem"
                            class="form-control form-control-sm">
                    </th>
                    <th>
                        <input type="date" name="tgled" id="tgled" class="form-control form-control-sm">
                    </th>
                    <th>
                        <button type="submit" class="btn btn-primary btn-sm" title="Tambahkan Item">
                            <i class="fa fa-plus-square"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-sm btncancel" title="Cancel">
                            <i class="fa fa-eraser"></i>
                        </button>
                    </th>
                </tr>
            </table>
            <?= form_close(); ?>

        </div>
    </div>

</div>
<div class="viewmodalforminputproduk" style="display: none;"></div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
// Hitung Diskon Item
function diskon_item() {
    let hargabeliitem = $('#hargabeli').autoNumeric('get');
    let jmlitem = $('#jml').autoNumeric('get');

    let dispersenitem = $('#dispersenitem').autoNumeric('get');
    let disuangitem = $('#disuangitem').autoNumeric('get');
    let subtotalitem;
    let hitung_subtotalkotor = parseFloat(hargabeliitem) * parseFloat(jmlitem);

    let hitung_subtotalbersih = parseFloat(hitung_subtotalkotor) - (parseFloat(hitung_subtotalkotor) * parseFloat(
        dispersenitem) / 100) - parseFloat(disuangitem);

    $('#subtotalitem').autoNumeric('set', hitung_subtotalbersih);
}
// End
function cariproduk() {
    $.ajax({
        url: "<?= site_url('beli/daftarproduksemua') ?>",
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodal').html(response.data).show();
                $('#modaldaftarproduk').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function ambildetaildataproduk(kode) {
    $.ajax({
        type: "post",
        url: "<?= site_url('beli/ambildataproduk') ?>",
        data: {
            kode: kode
        },
        dataType: "json",
        success: function(response) {
            if (response.ada) {
                let data = response.ada;

                $('#kode').removeClass('is-invalid');
                $('#kode').addClass('is-valid');
                $('.errorKode').html('');

                $('#namaproduk').removeClass('is-invalid');
                $('#namaproduk').addClass('is-valid');
                $('.errorNamaProduk').html('');

                $('#kode').val(data.kode);
                $('#namaproduk').val(data.namaproduk);
                $('#hargabeli').val(data.hargabeli);
                $('#hargajual').val(data.hargajual);
                $('#margin').val(data.margin);
                $('#namasatuan').val(data.namasatuan);
                $('#idsatuan').val(data.idsatuan);
                $('#qtysatuan').val(data.jmleceran);

                $('#jml').focus();
            }

            if (response.datadetail) {
                $('.viewmodal').html(response.datadetail).show();
                $('#modalcariproduk').modal('show');
            }

            if (response.error) {
                Swal.fire({
                    icon: 'warning',
                    title: `Maaf`,
                    text: `${response.error}`,
                }).then((result) => {
                    if (result.value) {
                        tampilforminput();
                    }
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

// Function menampilkan log harga dari item barang
function loghargaproduk() {
    let kode = $('#kode').val();

    $.ajax({
        type: "post",
        url: "<?= site_url('beli/tampiloghargaproduk') ?>",
        data: {
            kode: kode
        },
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodal').html(response.data).show();
                $('#modallogharga').modal('show');
            }

            if (response.error) {
                $.toast({
                    heading: 'Error',
                    text: `${response.error}`,
                    icon: 'error',
                    loader: true,
                    loaderBg: '#9EC600',
                    position: 'top-right'
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function() {
    $('#dispersenitem').keyup(function(e) {
        diskon_item();
    });
    $('#disuangitem').keyup(function(e) {
        diskon_item();
    });

    $('#jml').keyup(function(e) {
        diskon_item();
    });

    $('[data-toggle="tooltip"]').tooltip();

    // Tekan tombol esc
    $(this).keydown(function(e) {
        if (e.keyCode === 27) {
            e.preventDefault();
            $('#kode').focus();
        }
    });
    //setting currency
    $('#subtotalitem').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    $('#dispersenitem').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });
    $('#disuangitem').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });
    $('#hargabeli').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    $('#margin').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    $('#hargajual').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    $('#jml').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });


    // ! reload Form input
    $('.btnreload').click(function(e) {
        tampilforminput();
        datadetailpembelian();
    });
    $('.btncancel').click(function(e) {
        tampilforminput();
    });

    // Input Kode Barcode atau Produk
    $('#kode').keydown(function(e) {
        let kode = $(this).val();
        if (e.keyCode === 13) {
            e.preventDefault();
            if (kode.length === 0) {
                cariproduk();
            } else {
                ambildetaildataproduk(kode);
            }
        }

        // Tekan F1 untuk input produk baru
        if (e.keyCode === 112) {
            e.preventDefault();

            // var top = window.screen.height - 600;
            // top = top > 0 ? top / 2 : 0;

            // var left = window.screen.width - 800;
            // left = left > 0 ? left / 2 : 0;

            // // var url = '.././pemasok/index';
            // var uploadWin = window.open("<?= site_url('admin/produk/add') ?>",
            //     "Tambah Produk",
            //     "width=800,height=600" + ",top=" + top +
            //     ",left=" + left);
            // uploadWin.moveTo(left, top);
            // uploadWin.focus();
            $.ajax({
                url: "<?= site_url('beli/forminputproduk') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.viewmodalforminputproduk').html(response.data).show();
                        $('#modalforminputproduk').on('shown.bs.modal', function(e) {
                            $('#produkkode').focus();
                        });
                        $('#modalforminputproduk').modal('show');
                    }
                }
            });
        }
    });

    // Modal Tambah Produk Baru
    $('#btnaddprodukbaru').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('beli/forminputproduk') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalforminputproduk').html(response.data).show();
                    $('#modalforminputproduk').on('shown.bs.modal', function(e) {
                        $('#produkkode').focus();
                    });
                    $('#modalforminputproduk').modal('show');
                }
            }
        });
    });

    // Cari Satuan
    $('.tombolcarisatuan').click(function(e) {
        e.preventDefault();
        let kode = $('#kode').val();

        $.ajax({
            type: "post",
            url: "<?= site_url('beli/carisatuanproduk') ?>",
            data: {
                kode: kode
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalcarisatuan').html(response.data).show();
                    $('#modalcarisatuanproduk').modal('show');
                }
                if (response.error) {
                    $.toast({
                        heading: 'Information',
                        text: `${response.error}`,
                        icon: 'error',
                        loader: true,
                        loaderBg: '#9EC600',
                        position: 'bottom-center'
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

    // Simpan Item
    $('.formsimpanitem').submit(function(e) {
        e.preventDefault();
        let faktur = $('#faktur').val();
        let kode = $('#kode').val();
        if (faktur.length === 0) {
            Swal.fire('Perhatian', 'Faktur tidak boleh kosong, simpan faktur terlebih dahulu',
                'warning');
        } else {
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        if (response.error.kode) {
                            $('#kode').addClass('is-invalid');
                            $('.errorKode').html(response.error.kode)
                        } else {
                            $('#kode').removeClass('is-invalid');
                            $('.errorKode').html('');
                        }

                        if (response.error.namaproduk) {
                            $('#namaproduk').addClass('is-invalid');
                            $('.errorNamaProduk').html(response.error.namaproduk)
                        } else {
                            $('#namaproduk').removeClass('is-invalid');
                            $('.errorNamaProduk').html('');
                        }

                        $.toast({
                            heading: 'Error',
                            text: `${response.error}`,
                            icon: 'error',
                            loader: true,
                            loaderBg: '#9EC600',
                            position: 'top-right'
                        });
                    } else {
                        $.toast({
                            heading: 'Berhasil',
                            text: `${response.sukses}`,
                            icon: 'success',
                            loader: true,
                            loaderBg: '#9EC600',
                            position: 'top-right'
                        });
                        tampilforminput();
                        datadetailpembelian();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }

        return false;
    });

    $('#hargabeli').keydown(function(e) {
        if (e.keyCode === 112) {
            e.preventDefault();
            loghargaproduk();
        }
    });

    $('#margin').keydown(function(e) {
        if (e.keyCode === 112) {
            e.preventDefault();
            loghargaproduk();
        }
    });

    $('#hargajual').keydown(function(e) {
        if (e.keyCode === 112) {
            e.preventDefault();
            loghargaproduk();
        }
    });
});

$(document).on('keyup', '#margin', function(e) {
    let margin = $(this).val();
    let hargabeli = $('#hargabeli').val();
    let konversi_hargabeli = hargabeli.replace(",", "");

    hitung_hargajual = parseFloat(konversi_hargabeli) + ((parseFloat(konversi_hargabeli) *
        parseFloat(margin)) / 100);

    $('#hargajual').autoNumeric('set', hitung_hargajual);
});

$(document).on('keyup', '#hargajual', function(e) {
    let hargajual = $(this).autoNumeric('get');
    let hargabeli = $('#hargabeli').autoNumeric('get');

    let hitunglaba;
    hitunglaba = parseFloat(hargajual) - parseFloat(hargabeli);

    let margin;
    margin = (hitunglaba / hargabeli) * 100;

    $('#margin').autoNumeric('set', margin);
});
</script>