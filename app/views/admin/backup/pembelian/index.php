<!-- DataTable -->
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">

<!-- DatePicker -->
<link href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
<style>
label {
    font-size: 10pt;
}
</style>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-pinterest waves-effect waves-light"
                onclick="window.location='<?= site_url('admin/pembelian/datatransaksi') ?>'">
                <i class="fa fa-tasks"></i> Lihat Data Transaksi
            </button>
        </div>
        <div class="card-body">
            <!-- Faktur  -->
            <fieldset>
                <legend style="font-size: 12pt;">Input Faktur, Tgl.Faktur Dan Pemasok</legend>
                <?= form_open('admin/pembelian/simpanpembelian', ['class' => 'formfaktur']) ?>
                <div class="msg" style="display: none;"></div>
                <table class="table table-sm">
                    <tr>
                        <th>
                            <input type="text" class="form-control" name="nofaktur" id="nofaktur"
                                placeholder="No.Faktur Pembelian" autofocus="autofocus" autocomplete="off">
                        </th>
                        <th>
                            <input type="text" class="form-control" name="tglbeli" id="tglbeli"
                                placeholder="2020-01-01">
                        </th>
                        <th>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="namapemasok"
                                    id="namapemasok" disabled="disabled" style="cursor:auto;">
                                <input type="hidden" name="idpemasok" id="idpemasok">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm dropdown-toggle btncaripemasok" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item cariData" href="#">
                                            <i class="fa fa-search" style="color:blue;"></i> Cari Data
                                        </a>
                                        <a class="dropdown-item addDataPemasok" href="#">
                                            <i class="fa fa-plus-circle" style="color:blue;"></i> Tambah Data
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </th>
                        <th style="text-align: right;">
                            <button type="submit" class="btn btn-success waves-effect waves-light btnsimpan"
                                data-placement="top" data-toggle="tooltip" title=""
                                data-original-title="Simpan Faktur Terlebih dahulu">
                                Simpan
                            </button>
                            <button type="button" data-placement="top" data-toggle="tooltip" title=""
                                data-original-title="Batalkan Transaksi"
                                class="btn btn-danger waves-effect waves-light btnbataltransaksi">
                                <i class="fa fa-ban"></i>
                            </button>
                        </th>
                    </tr>
                </table>
                <?= form_close(); ?>
            </fieldset>
            <!-- end -->

            <div class="forminputitempembelian" style="display: none;">
                <fieldset>
                    <legend>Input Item :</legend>
                    <table class="table table-sm">
                        <tr style="font-size: 10pt;">
                            <td>Kode Barcode/Produk</td>
                            <td>Satuan Beli</td>
                            <td>Jml Per-Satuan</td>
                            <td>Tgl.Kadaluarsa</td>
                            <td>Hrg.Beli Per-Satuan (Rp.)</td>
                            <td></td>
                        </tr>
                        <tr style="text-align: left;">
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" name="kodebarcode"
                                        id="kodebarcode" placeholder="Inputkan Kode">
                                    <div class="input-group-append">
                                        <button class="btn btn-pinterest dropdown-toggle btn-sm" type="button"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">Aksi</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item cariDataProduk" href="#">
                                                <i class="fa fa-search" style="color:brown;"></i> Cari Data
                                            </a>
                                            <a class="dropdown-item tambahProduk" href="#">
                                                <i class="fa fa-plus-circle" style="color:brown;"></i> Tambah Data
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="namasatuan" id="namasatuan"
                                        class="form-control form-control-sm" readonly="readonly">
                                    <input type="hidden" name="idsatuan" id="idsatuan">
                                    <input type="hidden" name="qtydefault" id="qtydefault">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary btn-sm carisatuanproduk" type="button">
                                            <i class="fa fa-search-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="number" name="jml" id="jml" class="form-control form-control-sm"
                                    autocomplete="off">
                            </td>
                            <td>
                                <input type="text" name="tgled" id="tgled" class="form-control form-control-sm">
                            </td>
                            <td>
                                <input type="text" name="hrgbeli" id="hrgbeli" class="form-control form-control-sm">
                            </td>
                            <td>
                                <button type="button"
                                    class="btn btn-googleplus m-b-10 m-l-10 waves-effect waves-light btnsimpanitem"
                                    data-placement="top" data-toggle="tooltip" title=""
                                    data-original-title="Simpan Item">
                                    <i class="fa fa-plus-square"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="viewnamaproduk" style="display: none;">
                            <td colspan="6" class="namaproduk">
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div class="card m-b-30">
                <h5 class="card-header bg-primary text-white mt-0" style="font-family: monospace;">Data Item Pembelian
                </h5>
                <div class="card-body">
                    <p class="card-text tampildetailpembelian">

                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<!-- JQuery DatePicker -->
<script src="<?php echo base_url() ?>assets/plugins/timepicker/moment.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.js"></script>

<!-- JQuery DataTable -->
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<script>
$(document).ready(function(e) {
    $('#tglbeli').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false
    });
    $('#tgled').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false
    });
});
</script>
<script src="<?= base_url('assets/novinaldi/pembelian/index.js') ?>"></script>