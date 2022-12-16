<!-- Modal -->
<div class="modal fade" id="modalCetakLabel" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Cetak Label Barcode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/produk/cetak-barcode-produk', ['target' => '_blank']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Kode Barcode</label>
                    <input type="text" name="tkode" id="tkode" class="form-control" readonly
                        value="<?= $kodebarcode; ?>">
                </div>
                <div class="form-group">
                    <label for="">Nama Produk</label>
                    <input type="text" name="tnamaproduk" id="tnamaproduk" class="form-control" readonly
                        value="<?= $namaproduk; ?>">
                </div>
                <div class="form-group">
                    <label for="">Jumlah Dicetak</label>
                    <input type="number" name="jmlcetak" id="jmlcetak" class="form-control" value="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Cetak</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>