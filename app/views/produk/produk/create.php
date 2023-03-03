@extends(layouts/index)
@section(style)
<link rel="stylesheet" href="<?= assets() ?>plugins/select2/select2.min.css">
@endsection
@section(content)
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <a href="<?= site_url('produk') ?>" class="btn btn-warning">&laquo; Kembali</a>
        </div>
        <div class="card-body">
            <div class="alert alert-info">Silahkan Tambahkan Data Produk Melalui Form Berikut :</div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kode Barcode/Produk<sup style="color: red;">*</sup></label>
                <div class="col-sm-8">
                    <input type="text" name="kode" class="form-control" autofocus="autofocus" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Produk<sup style="color: red;">*</sup></label>
                <div class="col-sm-10">
                    <input type="text" name="nama" class="form-control" placeholder="Isikan Dengan Lengkap Nama Produk">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Satuan<sup style="color: red;">*</sup></label>
                <div class="col-sm-4">
                    <select name="satuan" id="satuan" class="form-control"></select>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-primary btn-round btn-sm create_satuan" title="Tambah Satuan"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kategori</label>
                <div class="col-sm-4">
                    <select name="kategori" id="kategori" class="form-control"></select>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-info btn-round btn-sm create_kategori" title="Tambah Kategori"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Stok Yang Tersedia</label>
                <div class="col-sm-4">
                    <input type="number" name="stok" id="stok" class="form-control" value="0">
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info" style="font-style: italic;">- Silahkan Isi Ketersedian Stok Secara Keseluruhan</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Harga Modal/Beli (Rp)</label>
                <div class="col-sm-4">
                    <input type="text" name="harga_beli" id="harga_beli" class="form-control" value="0" style="text-align: right;">
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info" style="font-style: italic;">- Input Harga beli dalam satuan terkecil</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Margin (%)</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <input type="text" name="margin" id="margin" class="form-control" value="0" style="text-align: right;">
                        <div class="input-group-append"><span class="input-group-text">%</span></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info">- Berikan tanda titik (.) untuk bilangan desimal</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Harga Jual Eceran (Rp)</label>
                <div class="col-sm-4">
                    <input type="text" name="harga_jual" id="harga_jual" class="form-control" value="0" style="text-align: right;">
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info" style="font-style: italic;">- Input Harga Jual Eceran dalam satuan terkecil</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Harga Jual Reseller (Rp)</label>
                <div class="col-sm-4">
                    <input type="text" name="harga_grosir" id="harga_grosir" class="form-control" value="0" style="text-align: right;">
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info" style="font-style: italic;">- Input Harga Jual Reseller dalam satuan terkecil</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Qty Default Per-Satuan</label>
                <div class="col-sm-4">
                    <input type="number" name="jumlah" id="jumlah" class="form-control" value="1">
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info" style="font-style: italic;">- Isi quantity default berdasarkan satuan yang dipilih. Digunakan untuk transaksi penjualan.</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="form-quick" data-quick=""></div>
<div id="tampil-modal"></div>
@endsection
@section(script)
<script src="<?= assets() ?>plugins/select2/select2.min.js"></script>
<script src="<?= assets() ?>js/autoNumeric.js"></script>
<script>
    $(document).ready(function(e) {
        $('#satuan').select2({
            placeholder: 'Pilih satuan produk',
            ajax: {
                url: BASE_URL + 'satuan/autocomplete',
                type: 'get',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#kategori').select2({
            placeholder: 'Pilih kategori produk',
            ajax: {
                url: BASE_URL + 'kategori/autocomplete',
                type: 'get',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#harga_beli').autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '2'
        });
        $('#margin').autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '2'
        });
        $('#harga_jual').autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '2'
        });
        $('#harga_grosir').autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '2'
        });
    });

    $(document).on('keyup', '#margin', function(e) {
        let margin = $(this).val();
        let hargaBeli = $('#harga_beli').val();
        let replace_input = hargaBeli.replace(".", "");
        hitung_hargajual = parseFloat(replace_input) + ((parseFloat(replace_input) * parseFloat(margin)) / 100);
        $('#harga_jual').autoNumeric('set', hitung_hargajual);
    });

    $(document).on('keyup', '#harga_jual', function(e) {
        let hargaJual = $(this).autoNumeric('get');
        let hargaBeli = $('#harga_beli').autoNumeric('get');

        let hitunglaba;
        hitunglaba = parseFloat(hargaJual) - parseFloat(hargaBeli);

        let margin;
        margin = (hitunglaba / hargaBeli) * 100;
        if (margin > 0) {
            $('#margin').autoNumeric('set', margin);
        }
    });

    $(document).on('click', '.create_satuan', function(e) {
        $.post(BASE_URL + 'satuan/create', function(resp) {
            $('#form-quick').attr('data-quick', 'formSatuan');
            $('#tampil-modal').show();
            $('#tampil-modal').html(resp);
            const modalForm = document.querySelector('#modal-form');
            modalForm.classList.add('animated', 'zoomIn');
            $('#modal-form').modal('show');
        });
    });

    $(document).on('click', '.create_kategori', function(e) {
        $.post(BASE_URL + 'kategori/create', function(resp) {
            $('#form-quick').attr('data-quick', 'formKategori');
            $('#tampil-modal').show();
            $('#tampil-modal').html(resp);
            const modalForm = document.querySelector('#modal-form');
            modalForm.classList.add('animated', 'zoomIn');
            $('#modal-form').modal('show');
        });
    });

    $(document).on('submit', '.form_data', function(e) {
        e.preventDefault();
        var form_quick = $('#form-quick').attr('data-quick');
        var data = $('.form_data').serialize();
        if (form_quick == 'formSatuan') {
            $.post(BASE_URL + 'satuan/store-quick', data, function(response) {
                var resp = eval('(' + response + ')');
                if (resp.status == true) {
                    var newOption = new Option(resp.data.nama_satuan, resp.data.id_satuan, true, true);
                    $('#satuan').append(newOption).trigger('change');
                    $('#modal-form').modal('hide');
                    $.toast({
                        heading: 'Success!',
                        text: resp.message,
                        icon: 'success',
                        loader: true,
                    });
                } else {
                    $.toast({
                        heading: 'Error!',
                        text: resp.message,
                        icon: 'error',
                        loader: true,
                    });
                }
            });
        } else if (form_quick == 'formKategori') {
            $.post(BASE_URL + 'kategori/store-quick', data, function(response) {
                var resp = eval('(' + response + ')');
                if (resp.status == true) {
                    var newOption = new Option(resp.data.nama_kategori, resp.data.id_kategori, true, true);
                    $('#kategori').append(newOption).trigger('change');
                    $('#modal-form').modal('hide');
                    $.toast({
                        heading: 'Success!',
                        text: resp.message,
                        icon: 'success',
                        loader: true,
                    });
                } else {
                    $.toast({
                        heading: 'Error!',
                        text: resp.message,
                        icon: 'error',
                        loader: true,
                    });
                }
            });
        }
    });
</script>
@endsection