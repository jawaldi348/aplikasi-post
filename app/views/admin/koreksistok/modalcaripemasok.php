<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<!-- Modal -->
<div class="modal fade" id="modalcaripemasok" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cari Data Pemasok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped table-bordered display nowrap" id="datapemasok" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pemasok</th>
                            <th>Alamat</th>
                            <th>Telp</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor = 0;
                        foreach ($datapemasok->result_array() as $row) : $nomor++; ?>
                        <tr>
                            <td><?= $nomor; ?></td>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['alamat']; ?></td>
                            <td><?= $row['telp']; ?></td>
                            <td>
                                <button type="button" class="btn btn-outline-info"
                                    onclick="pilih('<?= $row['id'] ?>','<?= $row['nama'] ?>')">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
function pilih(id, nama) {
    $('#idpemasok').val(id);
    $('#namapemasok').val(nama);
    $('#modalcaripemasok').on('hidden.bs.modal', function(e) {
        tampilforminputproduk();
    });
    $('#modalcaripemasok').modal('hide');
}
$(document).ready(function() {
    table = $('#datapemasok').DataTable({
        responsive: true,
    });
});
</script>