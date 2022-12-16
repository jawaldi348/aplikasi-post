<!-- DataTables -->
<link href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<link href="<?= base_url() ?>assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<!-- Required datatable js -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
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
                <table class="table table-bordered" id="datapemasok" style="width:100%;">

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

    $('#modalcaripemasok').modal('hide');
}
$(document).ready(function() {
    $('#datapemasok').DataTable();
});
</script>