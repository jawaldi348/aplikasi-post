@extends(layouts/index)
@section(content)
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <a href="<?= site_url('produk/home') ?>" class="btn btn-warning btn-sm">&laquo; Kembali</a>
            <a href="<?= site_url('produk/create') ?>" class="btn btn-primary btn-sm"><i class="fa fa-fw fa-plus-circle"></i> Tambah Produk</a>
        </div>
    </div>
</div>
@endsection