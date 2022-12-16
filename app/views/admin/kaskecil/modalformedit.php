<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="editkaskecil" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editkaskecil">Form Edit Kas Kecil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/kaskecil/updatedata', ['class' => 'form']); ?>
            <input type="hidden" name="id" value="<?= $id; ?>">
            <div class="modal-body">
                <div class="msg" style="display: none;"></div>
                <div class="form-group">
                    <label>Jumlah Uang</label>
                    <input type="text" value="<?= $jml ?>" name="jml" id="jml" class="form-control" value="0">
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
<script>
//Format Rupiah
var rupiah = document.getElementById('jml');
rupiah.addEventListener('keyup', function(e) {
    rupiah.value = formatRupiah(this.value, 'Rp. ');
});


function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
}
</script>