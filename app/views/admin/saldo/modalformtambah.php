<div class="modal fade bd-example-modal-lg" id="modaltambah" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Saldo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('saldo/simpan', ['class' => 'formsimpan']); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="tgl" class="col-sm-4 col-form-label">Kode <small>* Otomatis di Generate</small></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="kode" name="kode" required
                            value="<?= date('Y-m-d'); ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tgl" class="col-sm-4 col-form-label">Tanggal</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control form-control-sm" id="tgl" name="tgl" required
                            value="<?= date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tgl" class="col-sm-4 col-form-label">Jumlah Saldo</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="jmlsaldo" name="jmlsaldo" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
function buatkode() {
    let tgl = $('#tgl').val();
    $.ajax({
        type: "post",
        url: "<?= site_url('saldo/buatkode') ?>",
        data: {
            tgl: tgl
        },
        dataType: "json",
        success: function(response) {
            if (response.sukses) {
                $('#kode').val(response.sukses);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
                icon: 'error',
                title: xhr.status,
                html: xhr.responseText + "\n" + thrownError,
            });
        }
    });
}
$(document).ready(function() {
    buatkode();
    $('#jmlsaldo').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '0'
    });

    $('.formsimpan').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    $.toast({
                        heading: 'Berhasil',
                        text: `${response.sukses}`,
                        icon: 'success',
                        loader: true,
                        loaderBg: '#9EC600',
                        position: 'top-right'
                    });
                    tampildatasaldo();
                    $('#modaltambah').modal('hide');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: xhr.status,
                    html: xhr.responseText + "\n" + thrownError,
                });
            }
        });
        return false;
    });

    $('#tgl').change(function(e) {
        buatkode();
    });
});
</script>