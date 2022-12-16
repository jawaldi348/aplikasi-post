<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-warning"
                onclick="window.location='<?= site_url('admin/produk/index') ?>'">
                <i class="fa fa-fw fa-step-backward"></i> Kembali
            </button>
        </div>
        <?= form_open('admin/produk/hapustemp_multiple', ['class' => 'formdelete']) ?>
        <div class="card-body">
            <div class="card-text">
                <div class="d-flex justify-content-start">
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-fw fa-trash-alt"></i> Hapus Yang di Pilih
                    </button>
                </div>
                <br>
                <?= $this->session->flashdata('msg'); ?>
                <table class="table table-sm table-striped table-bordered display nowrap" id="dataproduk" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="check-all">
                            </th>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
<script src="<?= base_url(); ?>assets/novinaldi/produk/temp.js"></script>