<div class="modal fade bd-example-modal-lg" id="modalsatuanharga" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-default">
                <h5 class="modal-title" id="exampleModalLabel">Cari Satuan Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-sm" id="dataproduk" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Satuan</th>
                            <th>Qty Default</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor = 0;
                        foreach ($datasatuan as $d) : $nomor++; ?>
                        <tr>
                            <td><?= $nomor; ?></td>
                            <td><?= $d->satnama; ?></td>
                            <td><?= $d->qty; ?></td>
                            <td>
                                <button type="button" class="btn btn-info"
                                    onclick="pilih('<?= $d->satnama ?>','<?= $d->idsat ?>','<?= $d->qty ?>')">Pilih</button>
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
function pilih(nama, id, qty) {
    $('#namasatuan').val(nama);
    $('#idsatuan').val(id);
    $('#qtydefault').val(qty);

    $('#modalsatuanharga').modal('hide');
}
</script>