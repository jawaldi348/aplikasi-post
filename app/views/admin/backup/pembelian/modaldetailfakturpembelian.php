<div class="modal fade bd-example-modal-lg" id="modaldetailfaktur" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Detail Faktur Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped">
                    <tr>
                        <td style="width: 30%;">No.Faktur</td>
                        <td style="width: 1%;">:</td>
                        <td><?= $nofaktur; ?></td>
                    </tr>
                    <tr>
                        <td>Tgl.Pembelian</td>
                        <td>:</td>
                        <td><?= $tglbeli; ?></td>
                    </tr>
                    <tr>
                        <td>Pemasok</td>
                        <td>:</td>
                        <td><?= $pemasok; ?></td>
                    </tr>
                    <tr>
                        <td>Jenis Pembayaran</td>
                        <td>:</td>
                        <td><?= $jenisbayar; ?></td>
                    </tr>
                    <tr>
                        <td>Tgl.Jatuh Tempo</td>
                        <td>:</td>
                        <td><?= $tgljatuhtempo; ?></td>
                    </tr>
                    <tr>
                        <td>Total Kotor (Rp)</td>
                        <td>:</td>
                        <td style="text-align: right;"><?= $totalkotor; ?></td>
                    </tr>
                    <tr>
                        <td>Pph (%)</td>
                        <td>:</td>
                        <td style="text-align: right;"><?= $pph; ?></td>
                    </tr>
                    <tr>
                        <td>Diskon(%)</td>
                        <td>:</td>
                        <td style="text-align: right;"><?= $diskonpersen; ?></td>
                    </tr>
                    <tr>
                        <td>Diskon(Rp)</td>
                        <td>:</td>
                        <td style="text-align: right;"><?= $diskonuang; ?></td>
                    </tr>
                    <tr>
                        <td>Total Bersih(Rp)</td>
                        <td>:</td>
                        <td style="text-align: right;"><?= $totalbersih; ?></td>
                    </tr>
                    <?php
                    if ($jenispembayaran == 'K') :
                    ?>

                    <tr>
                        <td>Status Pembayaran</td>
                        <td>:</td>
                        <td>
                            <?php if ($statusbayar == '1') : ?>
                            <?php if ($totalbersihx == $jmlpembayarankredit) {
                                        $statuslunas = '<span class="badge badge-success">Lunas</span>';
                                    } else {
                                        $statuslunas = '<span class="badge badge-warning">Belum Lunas</span>';
                                    } ?>
                            <span class="badge badge-success">Sudah Bayar</span>&nbsp;<?= $statuslunas; ?>
                            <?php else : ?>
                            <span class="badge badge-danger">Belum di Bayarkan</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Tgl.Pembayaran</td>
                        <td>:</td>
                        <td><?= date('d-m-Y', strtotime($tglpembayarankredit)); ?></td>
                    </tr>
                    <tr>
                        <td>Jumlah Uang Yang telah diBayar</td>
                        <td>:</td>
                        <td><?= number_format($jmlpembayarankredit, 2, ".", ","); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>