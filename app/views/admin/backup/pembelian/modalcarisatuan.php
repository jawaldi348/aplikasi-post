<div class="modal fade bd-example-modal-lg" id="modalcarisatuan" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Cari Data Satuan Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Satuan</th>
                            <th>Harga Beli (Rp)</th>
                            <th>Harga Jual (Rp)</th>
                            <th>Qty Default</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data->num_rows() > 0) : ?>
                        <?php $nomor = 0;
                            foreach ($data->result_array() as $d) :
                                $nomor++;
                            ?>
                        <tr>
                            <td><?= $nomor; ?></td>
                            <td><?= $d['satnama']; ?></td>
                            <td style="text-align: right;"><?= number_format($d['hargamodal'], 2, ",", "."); ?></td>
                            <td style="text-align: right;"><?= number_format($d['hargajual'], 2, ",", "."); ?></td>
                            <td style="text-align: right;"><?= number_format($d['jml_default'], 2, ",", "."); ?></td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" onclick="pilih('<?= $d['id'] ?>')">
                                    <i class="fa fa-hand-point-up"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php else : ?>
                        <tr>
                            <th colspan="6">Data tidak ditemukan</th>
                        </tr>
                        <?php endif; ?>
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
function pilih(id) {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/pembelian/tampilsatuanhargaproduk') ?>",
        data: {
            id: id
        },
        dataType: "json",
        success: function(response) {
            if (response.sukses) {
                $('#idsatuan').val(response.sukses.idsatuan);
                $('#namasatuan').val(response.sukses.namasatuan);
                $('#jmleceran').val(response.sukses.jmleceran);
                $('#hargabeli').val(response.sukses.hargabeli);
                $('#hargajual').val(response.sukses.hargajual);
                $('#idprodukharga').val(response.sukses.idprodukharga);

                $('#modalcarisatuan').modal('hide');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
</script>