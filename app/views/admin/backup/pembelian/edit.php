<!-- DatePicker -->
<link href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
<!-- end -->
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-pinterest btn-sm"
                onclick="location.href=('<?= site_url('admin/pembelian/data') ?>')">
                <i class="fa fa-backward" aria-hidden="true"></i> Kembali
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-3">
                    <label for="faktur">Faktur Pembelian</label>
                    <input type="text" name="faktur" id="faktur" class="form-control" value="<?= $nofaktur; ?>"
                        readonly="readonly">
                </div>
                <div class="col-sm-3">
                    <label for="tgl">Tgl.Faktur</label>
                    <input type="text" name="tgl" id="tgl" class="form-control" value="<?= $tglbeli; ?>"
                        disabled="disabled">
                </div>
                <div class="col-sm-3">
                    <label for="pemasok">Pemasok</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="namapemasok" id="namapemasok"
                            readonly="readonly" value="<?= $namapemasok; ?>">
                        <input type="hidden" class="form-control form-control-sm" name="idpemasok" id="idpemasok"
                            readonly="readonly" value="<?= $idpemasok ?>">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="">Aksi</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-twitter btntampilforminput waves-effect"
                                data-toggle="tooltip" data-placement="top"
                                data-original-title="Tampilkan Form Input Item">
                                <i class="fa fa-download"></i>
                            </button>
                        </div>&nbsp;
                        <div class="input-group-append">
                            <button type="button" class="btn btn-instagram btntampildatadetail waves-effect"
                                data-toggle="tooltip" data-placement="top" data-original-title="Tampilkan Detail Item">
                                <i class="fa fa-list-alt"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row mt-2 viewformeditdetail" style="display: none;">

            </div>
            <div class="row mt-2 viewtampildatadetail" style="display: none;">

            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/novinaldi/pembelian/edit.js') ?>"></script>