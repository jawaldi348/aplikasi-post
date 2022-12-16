<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="modal fade" id="modalcarimemberseluruh" tabindex="-1" role="dialog"
    aria-labelledby="modalcarimemberseluruhLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalcarimemberseluruhLabel">Daftar Tabungan Diskon Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('pengambilandiskon/simpantemp', ['class' => 'formsimpan']) ?>
            <input type="hidden" name="ambilkode" value="<?= $kodepengambilan; ?>">
            <div class="modal-body">
                <p>
                    <button type="submit" class="btn btn-success btn-sm btnpilihcentang">Pilih Data Yang
                        Di-Centang</button>
                </p>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm" id="datacarimember" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="check-all">
                                </th>
                                <th>Kode Member</th>
                                <th>Nama Member</th>
                                <th>Total Tabungan</th>
                                <th>Digunakan</th>
                                <th>Diambil</th>
                                <th>Sisa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nomor = 0;
                            $totalsisadiskon = 0;
                            foreach ($datamember->result_array() as $row) : $nomor++;
                                $kodemember = $row['jualmemberkode'];
                                // query memperoleh tabungan diskon
                                $query_tabungandiskon = $this->db->query("SELECT IFNULL(ROUND(SUM(jualtotalbersih * ($diskonsetting / 100)),0),0) AS totaldiskon FROM penjualan WHERE jualmemberkode='$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$tglsekarang' AND (jualstatusbayar='T' OR jualstatusbayar='K')")->row_array();

                                $query_diskondigunakan = $this->db->query("SELECT IFNULL(SUM(jualtotalbersih),0) AS totaldigunakan FROM penjualan WHERE jualmemberkode='$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$tglsekarang' AND jualstatusbayar='M'")->row_array();

                                $query_diskondiambil = $this->db->query("SELECT IFNULL(SUM(detambiljumlah),0) AS totaldiambil FROM pengambilan_diskon_detail JOIN pengambilan_diskon ON  detambilkode=ambilkode WHERE detambilmemberkode = '$kodemember' AND ambiltgl <= '$tglsekarang'")->row_array();

                                $totaldiskon = $query_tabungandiskon['totaldiskon'];
                                $totaldigunakan = $query_diskondigunakan['totaldigunakan'];
                                $totaldiambil = $query_diskondiambil['totaldiambil'];
                                $sisadiskon = $totaldiskon - ($totaldigunakan + $totaldiambil);

                                $totalsisadiskon = $totalsisadiskon + $sisadiskon;

                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="check-item" name="memberkode[]"
                                        value="<?= $row['jualmemberkode'] ?>">
                                </td>
                                <td><?= $row['jualmemberkode']; ?></td>
                                <td><?= $row['membernama']; ?></td>
                                <td style="text-align: right;"><?= number_format($totaldiskon, 0, ",", ".") ?></td>
                                <td style="text-align: right;"><?= number_format($totaldigunakan, 0, ",", "."); ?></td>
                                <td style="text-align: right;"><?= number_format($totaldiambil, 0, ",", "."); ?></td>
                                <td style="text-align: right;"><?= number_format($sisadiskon, 0, ",", "."); ?>
                                    <input type="hidden" name="sisadiskon[]" id="sisadiskon"
                                        value="<?= $sisadiskon; ?>">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="text-align: center; font-weight: bold;">
                                <td colspan="6">Total Tabungan Diskon Member</td>
                                <td style="text-align: right;"><?= number_format($totalsisadiskon, 0, ",", "."); ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
function pilihdata(kode, nama, sisa) {
    $('#kodemember').val(kode);
    $('#namamember').val(nama);
    $('#totaltabungan').autoNumeric('set', sisa);
    $('#modalcarimember').on('hidden.bs.modal', function(e) {
        $('#jumlahambil').focus();
    });
    $('#modalcarimember').modal('hide');
}
$(document).ready(function() {
    var table = $('#datacarimember').DataTable({
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,

    });

    $('#check-all').click(function(e) {
        if ($(this).is(":checked")) {
            $(".check-item").prop("checked", true);
        } else {
            $(".check-item").prop("checked", false);
        }
    });

    $('.formsimpan').submit(function(e) {
        e.preventDefault();
        let pilih = $('.check-item:checked');

        if (pilih.length === 0) {
            Swal.fire('Perhatian', 'Tidak Ada member yang dipilih !', 'warning');
        } else {
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('.btnpilihcentang').html('<i class="fa fa-spin fa-spinner"></i>');
                    $('.btnpilihcentang').prop('disabled', true);
                },
                complete: function() {
                    $('.btnpilihcentang').html('Pilih Data Yang Di-Centang');
                    $('.btnpilihcentang').prop('disabled', false);
                },
                success: function(response) {

                    if (response.sukses) {
                        $('#modalcarimemberseluruh').on('hidden.bs.modal', function(e) {
                            $('#totaltabunganseluruh').autoNumeric('set', response
                                .sukses
                                .totaltabungandiskon);
                        });
                        $('#modalcarimemberseluruh').modal('hide');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" +
                        thrownError);
                }
            });
        }

        return false;

    });
});
</script>