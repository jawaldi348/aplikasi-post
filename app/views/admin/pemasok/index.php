<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<style>
tfoot input {
    width: 100%;
    padding: 3px;
    box-sizing: border-box;
}
</style>
<div class="col-lg-12">
    <div class="card m-b-30">
        <?= form_open('admin/pemasok/delete_multi', ['class' => 'formdelete']); ?>
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-primary" id="btnTambah">
                <i class="fa fa-fw fa-plus-circle"></i> Tambah Data
            </button>
            <button type="submit" class="btn btn-sm btn-danger">
                Hapus Data Yang di Tandai
            </button>
        </div>
        <div class="card-body">
            <p class="card-text">
            <table class="table table-sm table-striped table-bordered display nowrap" id="datapemasok" width="100%">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="check-all">
                        </th>
                        <th>No</th>
                        <th>Nama Pemasok</th>
                        <th>Alamat</th>
                        <th>Telp</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            </p>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<div class="viewform" style="display: none;"></div>
<script src="<?= base_url('assets/novinaldi/pemasok/index.js'); ?>"></script>