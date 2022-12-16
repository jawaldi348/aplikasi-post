<div class="modal fade bd-example-modal-lg" id="modalshowdetail" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Detail Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped">
                    <tr>
                        <td style="width: 30%;">Kode Barcode</td>
                        <td style="width: 1%;">:</td>
                        <td><?= $kode; ?></td>
                    </tr>
                    <tr>
                        <td>Nama Produk</td>
                        <td>:</td>
                        <td><?= $namaproduk; ?></td>
                    </tr>
                    <tr>
                        <td>Satuan</td>
                        <td>:</td>
                        <td><?= $satuan; ?></td>
                    </tr>
                    <tr>
                        <td>Stok Tersedia</td>
                        <td>:</td>
                        <td><?= $stok; ?></td>
                    </tr>
                    <tr>
                        <td>Harga Beli (Rp)</td>
                        <td>:</td>
                        <td><?= $hargabeli; ?></td>
                    </tr>
                    <tr>
                        <td>Harga Jual (Rp)</td>
                        <td>:</td>
                        <td><?= $hargajual; ?></td>
                    </tr>
                    <tr>
                        <td>Margin</td>
                        <td>:</td>
                        <td><?= $margin; ?></td>
                    </tr>
                    <tr>
                        <td>Qty Default</td>
                        <td>:</td>
                        <td><?= $jml . "&nbsp;" . $satuan; ?></td>
                    </tr>
                    <tr>
                        <td>Kategori Produk</td>
                        </td>
                        <td>:</td>
                        <td><?= $kategori; ?></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>