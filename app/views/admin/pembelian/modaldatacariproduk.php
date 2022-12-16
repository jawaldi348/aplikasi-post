<!-- Modal -->
<div class="modal fade" id="modalcariproduk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Produk (Keyword : <?= $keyword; ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped" style="width: 100%; font-size:10pt;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Harga Beli (Rp)</th>
                            <th>Margin</th>
                            <th>Harga Jual (Rp)</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor = 0;
                        foreach ($dataproduk->result_array() as $r) : $nomor++; ?>
                        <tr>
                            <th><?= $nomor; ?></th>
                            <th><?= $r['kodebarcode']; ?></th>
                            <th><?= $r['namaproduk']; ?></th>
                            <th><?= number_format($r['harga_beli_eceran'], 2, ",", ".") ?></th>
                            <th><?= number_format($r['margin'], 2, ".", ","); ?></th>
                            <th><?= number_format($r['harga_jual_eceran'], 2, ".", ","); ?></th>
                            <th>
                                <button class="btn btn-info btn-sm" type="button"
                                    onclick="pilih('<?= $r['kodebarcode'] ?>','<?= $r['namaproduk'] ?>')">
                                    Pilih
                                </button>
                            </th>
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
function pilih(kode, namaproduk) {
    $('#kode').val(kode);
    $('#namaproduk').val(namaproduk);
    $('#modalcariproduk').on('hidden.bs.modal', function(e) {
        $.ajax({
            type: "post",
            url: "<?= site_url('beli/ambildataproduk') ?>",
            data: {
                kode: kode,
                namaproduk: namaproduk
            },
            dataType: "json",
            success: function(response) {
                if (response.ada) {
                    let data = response.ada;
                    $('#kode').removeClass('is-invalid');
                    $('#kode').addClass('is-valid');
                    $('.errorKode').html('');

                    $('#namaproduk').removeClass('is-invalid');
                    $('#namaproduk').addClass('is-valid');
                    $('.errorNamaProduk').html('');

                    $('#kode').val(data.kode);
                    $('#namaproduk').val(data.namaproduk);
                    $('#hargabeli').val(data.hargabeli);
                    $('#hargajual').val(data.hargajual);
                    $('#margin').val(data.margin);
                    $('#namasatuan').val(data.namasatuan);
                    $('#idsatuan').val(data.idsatuan);
                    $('#qtysatuan').val(data.jmleceran);

                    $('#jml').focus();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
    $('#modalcariproduk').modal('hide');
}
</script>