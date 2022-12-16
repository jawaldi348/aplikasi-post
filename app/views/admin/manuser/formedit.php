<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="edituser" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edituser">Form Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/manuser/updatedata', ['class' => 'formtambah']); ?>
            <div class="modal-body">
                <div class="msg" style="display: none;"></div>
                <div class="form-group">
                    <label>ID User</label>
                    <input type="text" class="form-control" name="iduser" id="iduser" value="<?= $userid; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap User</label>
                    <input type="text" class="form-control" name="namalengkap" id="namalengkap"
                        value="<?= $usernama; ?>">
                </div>
                <div class="form-group">
                    <label>Grup</label>
                    <select name="grup" id="grup" class="form-control">
                        <?php foreach ($grup as $g) : ?>
                        <?php if ($g->id == $usergrup) : ?>
                        <option value="<?= $g->id; ?>" selected><?= $g->nmgrup; ?></option>
                        <?php else : ?>
                        <option value="<?= $g->id; ?>"><?= $g->nmgrup; ?></option>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ganti Password</label>
                    <input type="password" class="form-control" name="passbaru" id="passbaru">
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
<script>
$(document).ready(function(e) {
    $('.formtambah').submit(function(e) {
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $('.btnsimpan').attr('disabled', 'disabled');
                $('.btnsimpan').html(
                    '<i class="fa fa-spin fa-spinner"></i> Sedang di Proses');
            },
            success: function(response) {
                if (response.error) {
                    $('.msg').fadeIn();
                    $('.msg').html(response.error);
                }
                if (response.sukses) {
                    $.toast({
                        heading: 'Berhasil',
                        text: response.sukses,
                        icon: 'success',
                        loader: true,
                    });
                    tampildatauser();
                    $('#modaledit').modal('hide');
                }
            },
            complete: function() {
                $('.btnsimpan').removeAttr('disabled');
                $('.btnsimpan').html('Simpan Data');
            }
        });

        return false;
    });
});
</script>