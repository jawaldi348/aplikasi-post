<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-linkedin m-b-10 m-l-10 waves-effect waves-light btn-sm"
                onclick="window.location=('./index')">
                <i class="fa fa-backward"></i> Kembali
            </button>
            <button type="button"
                class="btn btn-instagram m-b-10 m-l-10 waves-effect waves-light btn-sm tambahhargaproduk">
                <i class="fa fa-plus"></i> Tambah Harga Produk
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <table class="table table-sm table-striped" style="font-size: 10pt;">

                        <tr>
                            <td style="width: 25%;">Kode Produk</td>
                            <td style="width: 1%;">:</td>
                            <td><?= $kode ?>
                                <input type="hidden" name="kode" id="kode" value="<?= $kode; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 205;">Produk</td>
                            <td style="width: 1%;">:</td>
                            <td><?= $nama ?></td>
                        </tr>
                        <tr>
                            <td style="width: 205;">Satuan</td>
                            <td style="width: 1%;">:</td>
                            <td><?= $satuan ?></td>
                        </tr>
                        <tr>
                            <td style="width: 205;">Harga Beli (Rp)</td>
                            <td style="width: 1%;">:</td>
                            <td><?= $hargabeli ?></td>
                        </tr>
                        <tr>
                            <td style="width: 205;">Harga Jual (Rp)</td>
                            <td style="width: 1%;">:</td>
                            <td><?= $hargajual ?></td>
                        </tr>
                        <tr>
                            <td style="width: 205;">Qty Default</td>
                            <td style="width: 1%;">:</td>
                            <td><?= $jml ?></td>
                        </tr>
                        <tr>
                            <td style="width: 205;">Kategori</td>
                            <td style="width: 1%;">:</td>
                            <td><?= $kategori ?></td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-8 vtampilhargaproduk">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script src="<?= base_url('assets/novinaldi/produk/hargaproduk.js'); ?>"></script>