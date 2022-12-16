<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>

<div class="modal fade bd-example-modal-lg" id="modalcaripemasok" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">
                    Cari Pemasok
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped table-bordered display nowrap" id="datapemasok" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pemasok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nomor = 1;
                        if ($datapemasok->num_rows() > 0) {
                            foreach ($datapemasok->result_array() as $r) :
                        ?>
                        <tr>
                            <td style="text-align: center;"><?= $nomor++; ?></td>
                            <td>
                                <?= $r['namapemasok']; ?>
                            </td>
                            <td>
                                <button onclick="pilih('<?= $r['idpemasok'] ?>','<?= $r['namapemasok'] ?>')"
                                    type="button" class="btn btn-sm btn-info">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                        <?php
                            endforeach;
                        } else {
                            echo '<tr><th colspan="3">Data tidak ditemukan...</th></tr>';
                        }
                        ?>
                    </tbody>
                </table>

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
    table = $('#datapemasok').DataTable({
        responsive: true,
    });
});
</script>