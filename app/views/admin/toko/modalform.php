<div class="modal fade bd-example-modal-lg" id="modalupdate" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update Data Toko</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('admin/toko/update', ['class' => 'formtoko']) ?>
            <div class="msg" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Nama Toko</label>
                    <input type="text" name="namatoko" value="<?= $d['nmtoko']; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Alamat</label>
                    <input type="text" name="alamat" value="<?= $d['alamat']; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Telp</label>
                    <input type="text" name="telp" value="<?= $d['telp']; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">No.Handphone</label>
                    <input type="text" name="hp" value="<?= $d['hp']; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Nama Pemilik Toko</label>
                    <input type="text" name="pemilik" value="<?= $d['pemilik']; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Upload Logo</label>
                    <input type="file" name="logo" accept=".png,.jpg">
                </div>
                <div class="form-group">
                    <label for="">Tulisan DiBawah Struk</label>
                    <input type="text" name="tulisanstruk" value="<?= $d['tulisanstruk']; ?>" class="form-control">
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