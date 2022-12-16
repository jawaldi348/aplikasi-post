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
                    <table class="table table-sm table-striped" id="dataproduk" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barcode</th>
                                <th>Nama Produk</th>
                                <th>Harga Modal (Rp)</th>
                                <th>Harga Jual (Rp)</th>
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
                                <td><?= number_format($rr['harga_beli_eceran'], 2, ",", "."); ?></td>
                                <td><?= number_format($rr['harga_jual_eceran'], 2, ",", "."); ?></td>
                                <td><?= number_format($rr['stok_tersedia'], 2); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info btnpilih"
                                        onclick="pilih('<?= $rr['kodebarcode'] ?>','<?= $rr['namaproduk'] ?>')">
                                        Pilih
                                    </button>
                                </td>
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
function pilih(kode, nama) {
    $('#kode').val(kode);
    $('#namaproduk').val(nama);
    $('#modaldatacariproduk').on('hidden.bs.modal', function(e) {
        let kode = $('#kode').val();
        let namaproduk = $('#namaproduk').val();

        if (kode.length == 0) {
            alert('kosong');
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('koreksistok/ambilproduk') ?>",
                data: {
                    kode: kode,
                    namaproduk: namaproduk,
                    koreksino: $('#koreksino').val(),
                    tgl: $('#tgl').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        $.toast({
                            heading: 'Error',
                            text: `${response.error}`,
                            icon: 'error',
                            loader: true,
                            loaderBg: '#9EC600',
                            position: 'top-center'
                        });
                    }

                    if (response.banyakdata) {
                        $('.viewmodal').html(response.banyakdata).show();
                        $('#modaldatacariproduk').modal('show');
                    }

                    if (response.sukses) {
                        let data = response.sukses;
                        $('#namaproduk').val(data.namaproduk);
                        $('#kode').val(data.kode);
                        $('#satuan').val(data.namasatuan);
                        $('#stoklalu').val(data.stoktersedia);
                        $('#hargabeli').val(data.hargabeli);
                        $('#hargajual').val(data.hargajual);

                        $('#stoksekarang').focus();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" +
                        thrownError);
                }
            });
        }
    });
    $('#modaldatacariproduk').modal('hide');
}
$(document).ready(function() {

});
</script>