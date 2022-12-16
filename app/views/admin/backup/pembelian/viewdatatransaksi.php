<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-instagram waves-effect waves-light"
                onclick="window.location='<?= site_url('admin/pembelian/index') ?>'">
                <i class="fa fa-backward"></i> Kembali Input Data
            </button>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-bordered display nowrap" id="datatransaksipembelian"
                width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Faktur</th>
                        <th>Tgl.Beli</th>
                        <th>Jml Item Barang</th>
                        <th>Total Pembelian (Rp.)</th>
                        <th>Status Transaksi</th>
                        <th>Pembayaran</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>
</div>
<script src="<?= base_url('assets/novinaldi/pembelian/data.js') ?>"></script>