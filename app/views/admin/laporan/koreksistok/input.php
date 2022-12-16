<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-primary text-white">
            Cetak Berdasarkan Periode Tanggal
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Pilih Periode</label>
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
                <div class="col-sm-1">
                    <button type="submit" class="btn btn-sm btn-primary btntampil">Tampil</button>
                </div>
            </div>
            <div class="row viewtampildata" style="display: none;"></div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-success text-white">
            Cetak Per-No.Koreksi
        </div>
        <div class="card-body">
            <?= form_open('laporan/cetak-per-no-koreksi', ['class' => 'formcetaknokoreksi', 'target' => '_blank']); ?>
            <?= $this->session->flashdata('pesan'); ?>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Cari No.Koreksi</label>
                <div class="col-sm-4">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-sm" placeholder="No.Koreksi"
                            name="nokoreksi" id="nokoreksi" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary btn-sm" type="button" id="btncaridatakoreksi">
                                <i class="fa fa-fw fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Pemasok</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Pemasok" name="pemasok"
                        id="pemasok" readonly>
                </div>

                <div class="col-sm-4">
                    <button type="submit" class="btn btn-sm btn-primary btncetak">
                        <i class="fa fa-fw fa-print"></i> Cetak
                    </button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<div class="viewmodalcari" style="display: none;"></div>
<script>
$(document).ready(function() {
    $('.btntampil').click(function(e) {
        e.preventDefault();
        let tglawal = $('#tglawal').val();
        let tglakhir = $('#tglakhir').val();

        var top = window.screen.height - 600;
        top = top > 0 ? top / 2 : 0;

        var left = window.screen.width - 800;
        left = left > 0 ? left / 2 : 0;

        var url = "<?= site_url('laporan/cetak-koreksi-stok/') ?>" + tglawal + '/' + tglakhir;
        var uploadWin = window.open(url,
            "Koreksi Stok",
            "width=800,height=600" + ",top=" + top +
            ",left=" + left);
        uploadWin.moveTo(left, top);
        uploadWin.focus();
    });

    $('#btncaridatakoreksi').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('laporan/modalcarikoreksistok') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalcari').html(response.data).show();
                    $('#modalcarikoreksi').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    });

});
</script>