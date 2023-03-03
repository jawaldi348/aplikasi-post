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
            </div>
        </div>
    </div>
</div>
@endsection
@section(script)
<script src="<?= assets() ?>plugins/select2/select2.min.js"></script>
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
    });
</script>
@endsection