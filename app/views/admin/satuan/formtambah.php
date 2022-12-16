<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog" aria-labelledby="tambahsatuan" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahsatuan">Form Tambah Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/satuan/simpandata', ['class' => 'formtambah']); ?>
            <div class="modal-body">
                <div class="msg" style="display: none;"></div>
                <div class="form-group">
                    <label>Nama Satuan (Singkat)</label>
                    <input type="text" class="form-control" placeholder="Cth : PCS" name="nama">
                </div>
                <div class="form-group">
                    <label>Detail (Optional)</label>
                    <input type="text" class="form-control" placeholder="Cth : PIECE" name="ket">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btnsimpan">Simpan Data</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>