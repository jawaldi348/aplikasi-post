<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css') ?>">
<script src="<?= base_url('assets/plugins/select2/select2.min.js') ?>"></script>
<div class="modal fade bd-example-modal-lg" id="viewmodaledit" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Harga Produk Per-Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/produk/updatehargaproduk', ['class' => 'formsimpan']) ?>
            <input type="hidden" name="id" value="<?= $id; ?>">
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
                            <?php foreach ($datasatuan as $s) : ?>
                            <?php if ($s->satid == $idsat) : ?>
                            <option value="<?= $s->satid; ?>" selected="selected"><?= $s->satnama; ?></option>
                            <?php else : ?>
                            <option value="<?= $s->satid; ?>"><?= $s->satnama; ?></option>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Harga Modal(Rp) </label>
                    <div class="col-sm-6">
                        <input type="number" name="hrgmodal" id="hrgmodal" value="<?= $hargamodal; ?>"
                            class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Margin(%) </label>
                    <div class="col-sm-6">
                        <input type="text" name="margin" id="margin" value="<?= $margin; ?>" class="form-control"
                            autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Harga Jual(Rp) </label>
                    <div class="col-sm-6">
                        <input type="number" name="hrgjual" id="hrgjual" value="<?= $hargajual; ?>"
                            class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"> Qty Default </label>
                    <div class="col-sm-6">
                        <input type="number" name="jml" id="jml" value="<?= $jml ?>" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btnsimpan">Update</button>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/novinaldi/produk/modaleditharga.js') ?>"></script>
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