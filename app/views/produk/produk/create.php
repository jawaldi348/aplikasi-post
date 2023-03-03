@extends(layouts/index)
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
        </div>
    </div>
</div>
@endsection