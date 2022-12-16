<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="editpemasok" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editpemasok">Form Edit Pemasok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/pemasok/updatedata', ['class' => 'formedit']); ?>
            <input type="hidden" name="id" value="<?= $id; ?>">
            <div class="modal-body">
                <div class="msg" style="display: none;"></div>
                <div class="form-group">
                    <label>Nama Pemasok</label>
                    <input type="text" class="form-control" name="nama" id="nama" value="<?= $nama; ?>">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" class="form-control" name="alamat" id="alamat" value="<?= $alamat; ?>">
                </div>
                <div class="form-group">
                    <label>Telp/HP</label>
                    <input type="text" class="form-control" name="telp" id="telp" value="<?= $telp; ?>">
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