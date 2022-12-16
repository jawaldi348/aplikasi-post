<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<style>
table#dataproduk.dataTable tbody tr:hover {
    background-color: #FABB51;
    cursor: pointer;
}
</style>
<div class="modal fade bd-example-modal-lg" id="modaldatacariproduk" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Cari Data Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-sm table-striped display nowrap" id="dataproduk" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barcode</th>
                                <th>Nama Produk</th>
                                <th>Pemasok</th>
                                <th>Harga Jual (Rp)</th>
                                <th>Stok Tersedia</th>
                                <!-- <th>Aksi</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nomor = 0;
                            foreach ($tampildata->result_array() as $rr) :
                                $nomor++;
                            ?>
                            <tr>
                                <td><?= $nomor; ?></td>
                                <td class="kodebarcode"><?= $rr['kodebarcode']; ?></td>
                                <td class="namaproduk"><?= $rr['namaproduk']; ?></td>
                                <?php
                                    // Ambil data supplier Pembelian Produk
                                    $query_pembelianproduk = $this->db->query("SELECT idpemasok,pemasok.`nama` as namapemasok FROM pembelian JOIN pemasok ON pemasok.`id`=idpemasok JOIN pembelian_detail ON nofaktur=detfaktur
JOIN produk ON kodebarcode=detkodebarcode WHERE detkodebarcode = '" . $rr['kodebarcode'] . "' ");
                                    $datapemasok = '';
                                    foreach ($query_pembelianproduk->result_array() as $d) :
                                        $datapemasok .= $d['namapemasok'] . "<br>";
                                    endforeach;
                                    // End
                                    ?>
                                <td class="datapemasok"><?= $datapemasok; ?></td>
                                <td><?= number_format($rr['harga_jual_eceran'], 2, ",", "."); ?></td>
                                <td><?= number_format($rr['stok_tersedia'], 2); ?></td>
                                <!-- <td> -->
                                <!-- <button type="button" class="btn btn-sm btn-outline-info btnpilih"
                                        onclick="pilih('<? //= $rr['kodebarcode'] 
                                                        ?>','<? //= $rr['namaproduk'] 
                                                                ?>')">
                                        Pilih
                                    </button> -->
                                <!-- </td> -->
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
function pilih(kode, namaproduk) {
    $('#kode').val(kode);
    $('#tnamaproduk').val(namaproduk);
    $('#modaldatacariproduk').on('hidden.bs.modal', function(e) {
        // $('#kode').focus();
        detailproduk();
    });
    $('#modaldatacariproduk').modal('hide');
}
$(document).ready(function() {
    $('#dataproduk tbody').on('click', 'tr', function() {
        var currentRow = $(this).closest("tr");

        var kode = currentRow.find("td:eq(1)").text(); // get item name
        var namaproduk = currentRow.find("td:eq(2)").text(); // get item name
        $('#kode').val(kode);
        $('#tnamaproduk').val(namaproduk);
        $('#modaldatacariproduk').on('hidden.bs.modal', function(e) {
            // $('#kode').focus();
            detailproduk();
        });
        $('#modaldatacariproduk').modal('hide');
    });
    var table = $('#dataproduk').DataTable({
        // rowReorder: {
        //     selector: 'td:nth-child(2)'
        // },
        responsive: true,
    });
});
</script>