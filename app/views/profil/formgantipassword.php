<div class="modal fade bd-example-modal-lg" id="modalgantipassword" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Ganti Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('profil/updatepassword', ['class' => 'formgantipassword']) ?>
            <div class="msg" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Password Lama</label>
                    <input type="password" name="passlama" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Password Baru</label>
                    <input type="password" name="passbaru" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Ulangi Password Baru</label>
                    <input type="password" name="ulangipass" class="form-control">
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
<script src="<?= base_url('assets/novinaldi/profil.js') ?>"></script>