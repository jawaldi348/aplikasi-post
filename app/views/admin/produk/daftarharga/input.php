<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css') ?>">
<script src="<?= base_url('assets/plugins/select2/select2.min.js') ?>"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-warning btn-sm"
                onclick="window.location='<?= site_url('admin/produk/home') ?>'">
                &laquo; Kembali
            </button>
        </div>
        <div class="card-body">
            <div class="card-text">
                <?= form_open('admin/produk/cetak-list-harga') ?>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Pilih Produk</label>
                    <div class="col-sm-8">
                        <select class="form-control form-control-sm listproduk" id="list" name="list[]"
                            multiple="multiple" autofocus></select>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-success">Cetak</button>
                    </div>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.listproduk').select2({
        minimumInputLength: 3,
        allowClear: true,
        placeholder: 'Cari Berdasarkan Nama Produk',
        ajax: {
            dataType: 'json',
            url: "<?= site_url('admin/produk/ambillistdataproduk') ?>",
            delay: 800,
            data: function(params) {
                return {
                    search: params.term
                }
            },
            processResults: function(data, page) {
                return {
                    results: data
                };
            },
        }
    });
});
</script>