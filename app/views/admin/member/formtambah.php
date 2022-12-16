<div class="modal fade" id="modaltambah" tabindex="-1" role="dialog" aria-labelledby="tambahmember" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahmember">Form Tambah Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/member/simpandata', ['class' => 'formtambah']); ?>
            <div class="modal-body">
                <div class="msg" style="display: none;"></div>
                <div class="form-group">
                    <label>Kode Member</label>
                    <input type="text" class="form-control form-control-sm" name="kode" id="kode"
                        value="<?= $kodemember ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Member</label>
                    <input type="text" class="form-control form-control-sm" name="nama" id="nama">
                </div>
                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input type="text" class="form-control form-control-sm" name="tmp" id="tmp">
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-sm" name="tgl" id="tgl">
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenkel" id="jenkel" class="form-control-sm form-control">
                        <option value="">-Pilih-</option>
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" class="form-control form-control-sm" name="alamat" id="alamat">
                </div>
                <div class="form-group">
                    <label>Telp/HP</label>
                    <input type="text" class="form-control form-control-sm" name="telp" id="telp">
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
                    $('#modaltambah').modal('hide');
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