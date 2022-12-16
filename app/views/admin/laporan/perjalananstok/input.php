<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <!-- <button type="button" class="btn btn-sm btn-primary waves-effect waves-light btntambah">
                <i class="fa fa-plus-square"></i>
            </button> -->
        </div>
        <div class="card-body">
            <?= form_open('admin/laporan/tampilPerjalananStok', ['class' => 'formaksi']) ?>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Sortir</label>
                <div class="col-sm-2">
                    <input type="date" name="tglawal" id="tglawal" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
                <div class="col-sm-1">
                    s.d
                </div>
                <div class="col-sm-2">
                    <input type="date" name="tglakhir" id="tglakhir" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-sm btn-primary btntampil">Tampil</button>
                    <button type="button" class="btn btn-sm btn-danger btntutup"><i class="fa fa-ban"></i>
                        Tutup</button>
                </div>
            </div>
            <?= form_close(); ?>
            <div class="row viewtampildata" style="display: none;"></div>
        </div>

    </div>
</div>
<!-- <div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-primary text-white">
            Cetak Format Excel
        </div>
        <div class="card-body">
            <?//= form_open('admin/laporan/exportPersediaanStok') ?>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Sortir</label>
                <div class="col-sm-2">
                    <input type="date" name="tglawal" id="tglawal" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
                <div class="col-sm-1">
                    s.d
                </div>
                <div class="col-sm-2">
                    <input type="date" name="tglakhir" id="tglakhir" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-sm btn-success btntampil">
                        <i class="fa fa-file-excel"></i> Export Excel
                    </button>
                </div>
            </div>
            <?//= form_close(); ?>
        </div>

    </div>
</div> -->
<script>
$(document).ready(function() {
    $('.formaksi').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function(e) {
                $('.viewtampildata').html(
                    '<i class="fa fa-spinner fa-spin"></i>&nbsp;Mohon ditunggu, karena menampilkan semua data produk'
                ).show();
            },
            success: function(response) {
                if (response.data) {
                    $('.viewtampildata').html(response.data).show();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
                    width: '50%'
                })
            }
        });
        return false;
    });

    $('.btntutup').click(function(e) {
        e.preventDefault();
        $('.viewtampildata').hide();
    });
});
</script>