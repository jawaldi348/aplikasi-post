<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="modal fade" id="modalcarimember" tabindex="-1" role="dialog" aria-labelledby="modalcarimemberLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalcarimemberLabel">Cari Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped table-bordered display nowrap" id="datamember" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 10%;">Kode Member</th>
                            <th>Nama Member</th>
                            <th>Alamat</th>
                            <th style="width: 5%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nomor = 1;
                        if ($datamember->num_rows() > 0) {
                            foreach ($datamember->result_array() as $r) :
                        ?>
                        <tr>
                            <td style="text-align: center;"><?= $nomor++; ?></td>
                            <td>
                                <?= $r['jualmemberkode']; ?>
                            </td>
                            <td><?= $r['membernama']; ?></td>
                            <td><?= $r['memberalamat']; ?></td>
                            <td>
                                <button onclick="pilih('<?= $r['jualmemberkode'] ?>','<?= $r['membernama'] ?>')"
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
function pilih(id, nama) {
    $('#idmember').val(id);
    $('#namamember').val(nama);
    $('#modalcarimember').modal('hide');
}
$(document).ready(function() {
    table = $('#datamember').DataTable({
        responsive: true,
    });
});
</script>