<!-- Modal -->
<div class="modal fade" id="modallogharga" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #baf1f7;font-weight: bold;">
                <h5 class="modal-title" id="exampleModalLabel">Menampilkan Log Pembelian Untuk Produk
                    "<?= $namaproduk; ?>"</h5>
                <button type="button" class="btn btn-danger btn-round btn-sm" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped" style="width: 100%;" id="datalog">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Harga Beli</th>
                            <th>Margin(%)</th>
                            <th>Harga Jual</th>
                            <th>Satuan</th>
                            <th>Pemasok</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor = 0;
                        foreach ($tampildata as $row) : $nomor++; ?>
                        <tr>
                            <td><?= $nomor; ?></td>
                            <td><?= $row->kode; ?></td>
                            <td><?= $row->namaproduk; ?></td>
                            <td style="text-align: right;"><?= number_format($row->hargabeli, 2, ".", ","); ?></td>
                            <td style="text-align: right;"><?= number_format($row->margin, 2, ".", ","); ?></td>
                            <td style="text-align: right;"><?= number_format($row->hargajual, 2, ".", ","); ?></td>
                            <td><?= $row->satnama; ?></td>
                            <td><?= $row->namapemasok; ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-info"
                                    onclick="pilih('<?= $row->hargabeli ?>','<?= $row->margin ?>','<?= $row->hargajual ?>')">
                                    <i class="fa fa-hand-point-up"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
function pilih(beli, margin, jual) {
    $('#hargabeli').autoNumeric('set', beli);
    $('#margin').autoNumeric('set', margin);
    $('#hargajual').autoNumeric('set', jual);

    $('#modallogharga').modal('hide');
}
</script>