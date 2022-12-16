<!-- DatePicker -->
<link href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
<!-- end -->
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-primary btn-sm btndatatransaksi">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Data Transaksi
            </button>
        </div>
        <div class="card-body">
            <?= form_open('admin/pembelian/simpanfaktur', ['class' => 'formfaktur']) ?>
            <div class="msg" style="display: none;"></div>
            <div class="row">
                <div class="col-sm-3">
                    <label for="faktur">Faktur Pembelian</label>
                    <input type="text" name="faktur" id="faktur" class="form-control" autofocus="autofocus"
                        autocomplete="off" placeholder="Isi Faktur Pembelian">
                </div>
                <div class="col-sm-3">
                    <label for="tgl">Tgl.Faktur</label>
                    <input type="text" name="tgl" id="tgl" class="form-control" placeholder="">
                </div>
                <div class="col-sm-3">
                    <label for="pemasok">Pemasok</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="namapemasok" id="namapemasok"
                            readonly="readonly">
                        <input type="hidden" class="form-control form-control-sm" name="idpemasok" id="idpemasok"
                            readonly="readonly" value="1">
                        <div class="input-group-append">
                            <button class="btn btn-pinterest dropdown-toggle btn-sm" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item caridatapemasok" href="#">
                                    <i class="fa fa-search" style="color:brown;"></i> Cari Data
                                </a>
                                <a class="dropdown-item tambahdatapemasok" href="#">
                                    <i class="fa fa-plus" style="color:brown;"></i> Tambah Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="">Aksi</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-success waves-effect btnsimpan" data-toggle="tooltip"
                                data-placement="top" data-original-title="Simpan Faktur">
                                <i class="fa fa-save"></i>
                            </button>&nbsp;
                            <button type="button" class="btn btn-danger btnbatalkantransaksi waves-effect"
                                data-toggle="tooltip" data-placement="top" data-original-title="Batalkan Transaksi">
                                <i class="fa fa-ban"></i>
                            </button>&nbsp;
                            <button type="button" class="btn btn-instagram btnreload waves-effect" data-toggle="tooltip"
                                data-placement="top" data-original-title="Refresh Halaman"
                                onclick="window.location.reload();">
                                <i class="fa fa-redo-alt"></i>
                            </button>&nbsp;
                            <button type="button" class="btn btn-twitter btntampilforminput waves-effect"
                                data-toggle="tooltip" data-placement="top"
                                data-original-title="Tampilkan Form Input Item">
                                <i class="fa fa-list"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <?= form_close(); ?>
            <div class="row mt-3 viewforminputdetail" style="display: none;">

            </div>
            <div class="row mt-2 viewtampildatadetail" style="display: none;">

            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<!-- JQuery DatePicker -->
<script src="<?php echo base_url() ?>assets/plugins/timepicker/moment.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.js"></script>
<script>
$(document).ready(function(e) {
    $('#tgl').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        month: true
    });
});
</script>
<script src="<?= base_url('assets/novinaldi/pembelian/input.js') ?>"></script>