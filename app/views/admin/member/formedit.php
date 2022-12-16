<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="editmember" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editmember">Form Edit Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/member/updatedata', ['class' => 'formtambah']); ?>
            <div class="modal-body">
                <input type="hidden" name="kode" value="<?= $kode; ?>">
                <div class="form-group">
                    <label>Nama Member</label>
                    <input type="text" class="form-control" name="nama" id="nama" value="<?= $nama; ?>">
                </div>
                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input type="text" class="form-control form-control-sm" name="tmp" id="tmp"
                        value="<?= $tmplahir; ?>">
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-sm" name="tgl" id="tgl"
                        value="<?= $tgllahir; ?>">
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenkel" id="jenkel" class="form-control-sm form-control">
                        <option value="L" <?php if ($jenkel == 'L') echo 'selected'; ?>>Laki-Laki</option>
                        <option value="P" <?php if ($jenkel == 'P') echo 'selected'; ?>>Perempuan</option>
                    </select>
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
                    tampildatamember();
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