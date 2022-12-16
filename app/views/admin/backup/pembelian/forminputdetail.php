<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="d-flex justify-content-between bg-success text-white">
            <div>
                <h4 class="card-header mt-0">Input Item Produk</h4>
            </div>
            <div>
                <button class="btn btn-sm btn-danger btntutup" type="button">
                    <i class="fa fa-window-close"></i> Tutup
                </button>
                <button class="btn btn-sm btn-pinterest btnrefresh" type="button">
                    <i class="fa fa-recycle"></i> Refresh Data
                </button>
            </div>
        </div>
        <div class="card-body">
            <?= form_open('admin/pembelian/simpanitem', ['class' => 'forminputitem']) ?>
            <input type="hidden" name="faktur" id="faktur" value="<?= $faktur; ?>">
            <div class="msgdetail" style="display: none;"></div>
            <div class="row">
                <div class="col-sm-3">
                    <label for="kode">Kode Barcode</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="kode" id="kode">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-info btncariproduk">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="kode">Nama Produk</label>
                    <input type="text" class="form-control" name="namaproduk" id="namaproduk" readonly="readonly"
                        data-container="body" data-toggle="popover" data-placement="bottom">
                    <input type="hidden" class="form-control form-control-sm" name="stoktersedia" id="stoktersedia">
                </div>
                <div class="col-sm-2">
                    <label for="kode">Satuan</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="namasatuan" id="namasatuan"
                            readonly>
                        <input type="hidden" class="form-control form-control-sm" name="idsatuan" id="idsatuan">
                        <input type="hidden" class="form-control form-control-sm" name="jmleceran" id="jmleceran">
                        <input type="hidden" class="form-control form-control-sm" name="idprodukharga"
                            id="idprodukharga">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-instagram btncarisatuan">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="kode">Hrg.Beli (Rp)</label>
                    <input type="text" class="form-control" name="hargabeli" id="hargabeli" style="cursor: pointer;"
                        readonly="readonly"
                        title="Klik 2x, jika ingin dirubah. Enter untuk Update. Tekan 'Esc' untuk membatalkan"
                        data-toggle="tooltip" data-placement="bottom">
                </div>
                <div class="col-sm-2">
                    <label for="kode">Hrg.Jual (Rp)</label>
                    <input type="text" class="form-control" name="hargajual" id="hargajual" readonly="readonly"
                        style="cursor: pointer;"
                        title="Klik 2x, jika ingin dirubah. Enter untuk Update. Tekan 'Esc' untuk membatalkan"
                        data-toggle="tooltip" data-placement="bottom">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <label for="kode">Expired Date</label>
                    <input type="text" class="form-control" name="ed" id="ed" placeholder="Isi, Jika Ada"
                        readonly="readonly">
                </div>
                <div class="col-sm-3">
                    <label for="kode">Jumlah Beli</label>
                    <input type="number" class="form-control" name="jmlbeli" id="jmlbeli" value="1">
                </div>
                <div class="col-sm-2">
                    <label for="kode">Aksi</label>
                    <div class="input-group">
                        <button type="submit" class="btn btn-success waves-effect waves-light btnadditem">
                            <i class="fa fa-plus-circle"></i> Add
                        </button>
                    </div>
                </div>

            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
});
</script>
<script src="<?= base_url('assets/novinaldi/pembelian/forminputdetail.js') ?>"></script>