<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="editkategori" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editkategori">Form Edit Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/kategori/updatedata', ['class' => 'formedit']); ?>
            <input type="hidden" name="id" value="<?= $katid; ?>">
            <div class="modal-body">
                <div class="msg" style="display: none;"></div>
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" class="form-control" value="<?= $katnama; ?>"
                        placeholder="Inputkan Kategori Produk" name="nama">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btnsimpan">Update Data</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>