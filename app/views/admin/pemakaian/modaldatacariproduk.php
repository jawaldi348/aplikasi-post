<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="modal fade bd-example-modal-lg" id="modaldatacariproduk" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Cari Dengan Keyword :
                    <?= "<strong>$keyword</strong>"; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="aksi" id="aksi" value="<?= $aksi; ?>">
                <table class="table table-sm table-striped table-bordered display nowrap" id="dataproduk" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barcode</th>
                            <th>Nama Produk</th>
                            <th>Pemasok</th>
                            <th>Harga Beli (Rp)</th>
                            <th>Stok Tersedia</th>
                            <th>Aksi</th>
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
                            <td><?= number_format($rr['harga_beli_eceran'], 2, ",", "."); ?></td>
                            <td><?= number_format($rr['stok_tersedia'], 2); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-info btnpilih"
                                    onclick="pilih('<?= $rr['kodebarcode'] ?>','<?= $rr['namaproduk'] ?>')">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
$(document).ready(function() {
    table = $('#dataproduk').DataTable({
        responsive: true,
    });

    $('[type="search"]').keydown(function(e) {
        if (e.keyCode == 9) {
            e.preventDefault();
            $('.btnpilih').focus();
        }
    });
});

function pilih(kode, namaproduk) {
    let aksi = $('#aksi').val();
    $('#kodebarcode').val(kode);
    $('#namaproduk').val(namaproduk);
    $('.namaproduk').html(namaproduk);
    $('#modaldatacariproduk').on('hidden.bs.modal', function(e) {
        if (aksi == 'insert') {
            temppemakaian();
        } else {
            pemakaiandetail();
        }
        // tampilforminputproduk();
    });
    $('#modaldatacariproduk').modal('hide');
}
</script>