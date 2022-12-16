<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-body">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <td style="width: 20%;">Periode</td>
                        <td style="width: 1%;">:</td>
                        <td colspan="2"><?= $periode; ?></td>
                        <td style="text-align: right;">
                            <?= form_open('laporan/cetak-penjualan-kasir', ['target' => '_blank']) ?>
                            <input type="hidden" name="awal" id="awal" value="<?= $tglawal; ?>">
                            <input type="hidden" name="akhir" id="akhir" value="<?= $tglakhir; ?>">
                            <input type="hidden" name="kasir" id="kasir" value="<?= $kasir; ?>">
                            <button type="submit" class="btn btn-success btn-sm btncetak">
                                <i class="fa fa-print"></i> Cetak
                            </button>
                            <?= form_close(); ?>
                        </td>
                    </tr>
                </thead>
            </table>

            <table class="table table-sm table-bordered">
                <thead>
                    <tr style="background-color: #c9ccd1;">
                        <th>No</th>
                        <th>Total Kotor(Rp)</th>
                        <th>Diskon(Rp)</th>
                        <th>Total Bersih (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $nomor = 0;
                    if ($penjualan->num_rows() > 0) {
                        foreach ($penjualan->result_array() as $jual) :
                            $nomor++;

                    ?>
                    <tr>
                        <td><?= $nomor; ?></td>
                        <td style="text-align: right;"><?= number_format($jual['totalkotor'], 0, ",", "."); ?></td>
                        <td style="text-align: right;"><?= number_format($jual['diskon'], 0, ",", "."); ?></td>
                        <td style="text-align: right;"><?= number_format($jual['totalbersih'], 0, ",", "."); ?></td>
                    </tr>
                    <?php
                        endforeach;
                    }
                    ?>
                </tbody>
            </table>

            <table class="table table-sm table-bordered">
                <thead>
                    <tr style="background-color: #c9ccd1;">
                        <th>No</th>
                        <th>Kode Barcode</th>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        <th>Harga (Rp)</th>
                        <th>Diskon (Rp)</th>
                        <th>Sub.Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 0;
                    if ($detailpenjualan->num_rows() > 0) {
                        foreach ($detailpenjualan->result_array() as $detail) :
                            $no++;

                    ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $detail['detjualkodebarcode']; ?></td>
                        <td><?= $detail['namaproduk']; ?></td>
                        <td><?= $detail['detjualjml']; ?></td>
                        <td style="text-align: right;"><?= number_format($detail['detjualharga'], 0, ",", "."); ?></td>
                        <td style="text-align: right;"><?= number_format($detail['diskon'], 0, ",", "."); ?></td>
                        <td style="text-align: right;"><?= number_format($detail['detjualsubtotal'], 0, ",", "."); ?>
                        </td>
                    </tr>
                    <?php
                        endforeach;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>