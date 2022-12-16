<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css') ?>">
<script src="<?= base_url('assets/plugins/select2/select2.min.js') ?>"></script>
<div class="modal fade bd-example-modal-lg" id="viewmodal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Harga Produk Per-Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/produk/simpanhargaproduk', ['class' => 'formsimpan']) ?>
            <input type="hidden" name="kode" value="<?= $kode; ?>">
            <div class="modal-body">
                <div class="msg" style="display:none;"></div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Kode Barcode</label>
                    <div class="col-sm-10">
                        <input type="text" name="kode" id="kode" class="form-control" readonly value="<?= $kode; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Produk</label>
                    <div class="col-sm-10">
                        <input type="text" name="nama" id="nama" class="form-control" readonly value="<?= $nama; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Pilih Satuan</label>
                    <div class="col-sm-6 viewsatuan">
                        <select name="satuan" id="satuan" class="form-control" style="width: 100%;">

                        </select>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-pinterest btn-round m-b-10 m-l-10 waves-effect waves-light"
                            onclick="tambahsatuan();">
                            <i class="fa fa-plus-square"></i>
                        </button>
                        <button type="button"
                            class="btn btn-skype btn-round m-b-10 m-l-10 waves-effect waves-light tombolrefresh">
                            <i class="fa fa-sync fa-spin"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Harga Modal(Rp) </label>
                    <div class="col-sm-6">
                        <input type="number" name="hrgmodal" id="hrgmodal" value="0" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Margin(%) </label>
                    <div class="col-sm-6">
                        <input type="text" name="margin" id="margin" value="0" class="form-control" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Harga Jual(Rp) </label>
                    <div class="col-sm-6">
                        <input type="number" name="hrgjual" id="hrgjual" value="0" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"> Qty Default </label>
                    <div class="col-sm-6">
                        <input type="number" name="qty" id="qty" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btnsimpan">Simpan</button>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/novinaldi/produk/modaltambahharga.js'); ?>"></script>
<script>
// Perhitungan Margin
$(document).on('keyup', '#margin', function(e) {
    let hargamodal = $('#hrgmodal').val();
    let hargajual = $('#hrgjual').val();
    let margin = $(this).val();

    let hitungHargajual;
    hitungHargajual = parseInt(hargamodal) + ((parseInt(hargamodal) * parseFloat(margin)) /
        100);

    $('#hrgjual').val(hitungHargajual);
});

$(document).on('keyup', '#hrgjual', function(e) {
    let hargamodal = $('#hrgmodal').val();
    let hargajual = $(this).val();
    let margin = $('#margin').val();

    let hitungMargin;
    let kurangi;
    kurangi = parseInt(hargajual) - parseInt(hargamodal);
    hitungMargin = (kurangi / hargamodal) * 100;

    $('#margin').val(hitungMargin.toFixed(2));
});
</script>