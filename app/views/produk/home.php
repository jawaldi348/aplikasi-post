@extends(layouts/index)
@section(content)
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="card border-light mb-1 animated flipInY">
                        <div class="card-header" style="background-color: #aff76a; font-weight: bold; color:#000;">Kelola Produk</div>
                        <a href="<?= site_url('produk') ?>">
                            <div class="card-body" style="cursor: pointer;">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <span class="text-muted">Data Produk</span>
                                    </div>
                                    <div class="col-lg-4">
                                        <i class="fa fa-tasks" style="color: #aff76a; font-size:72px;"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection