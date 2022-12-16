<div class="modal fade" id="modalpilih" tabindex="-1" role="dialog" aria-labelledby="modalpilihLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalpilihLabel">Pilih Nama Yang Menerima !</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('beli/cetakpengeluarankas', ['target' => '_blank']) ?>
            <input type="hidden" name="faktur" value="<?= $faktur; ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Pilih Nama Yang Menerima :</label>
                    <select name="namauser" id="namauser" class="form-control form-control-sm" required>
                        <option value="">-Pilih-</option>
                        <?php foreach ($datauser->result_array() as $d) : ?>
                        <option value="<?= $d['usernama']; ?>"><?= $d['usernama']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><i class="fa fa-print"></i> Lanjut Cetak</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>