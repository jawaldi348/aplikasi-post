<div class="col-lg-12">
    <div class="card border-light mb-1">
        <div class="card-header"></div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated zoomInUp">
                        <div class="card-header bg-info text-white">Kasir</div>
                        <a href="<?= site_url('kasir/input') ?>">
                            <div class="card-body tombolMesinKasir">
                                <div class=" row">
                                    <div class="col-lg-8">
                                        <h3 class="card-subtitle mb-2 text-muted">Mesin Kasir</h3>
                                    </div>
                                    <div class="col-lg-4">
                                        <i class="fa fa-cash-register" style="color: #0a9dab; font-size:72px;"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated flipInY">
                        <div class="card-header bg-primary text-white">Data Transaksi Penjualan</div>
                        <a href="<?= site_url('admin/penjualan/all-data') ?>">
                            <div class="card-body tombolDataTransaksi" style="cursor:pointer;">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <table class="table table-sm table-striped">
                                            <tr>
                                                <td>Total Hari ini :
                                                    <strong><?= number_format($totalhariini, 0, ",", "."); ?></strong>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Total Bulan ini :
                                                    <strong><?= number_format($totalbulanini, 0, ",", "."); ?></strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Tahun ini :
                                                    <strong><?= number_format($totaltahunini, 0, ",", "."); ?></strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-lg-4">
                                        <i class="fa fa-list-alt" style="color: #091980; font-size:72px;"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated flipInY">
                        <div class="card-header bg-warning text-white">Transaksi Di Tahan</div>
                        <a href="<?= site_url('admin/penjualan/transaksiditahan') ?>">
                            <div class="card-body tombolTransaksiditahan">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h3 class="card-subtitle mb-2 text-muted">Total Data :</h3>
                                        <?php
                                        $queryx = $this->db->get_where('penjualan', ['jualstatusbayar' => 'H'])->result();
                                        echo '<h3>' . number_format(count($queryx), 0) . '</h3>';
                                        ?>
                                    </div>
                                    <div class="col-lg-4">
                                        <i class="fa fa-hand-holding-usd" style="color: #a89805; font-size:72px;"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated flipInY">
                        <div class="card-header" style="background-color: #aff76a; font-weight: bold; color:#000;">
                            Daftar Piutang</div>
                        <a href="<?= site_url('admin/penjualan/all-data-piutang') ?>">
                            <div class="card-body tomboldaftarpiutang" style="cursor: pointer;">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <ul>
                                            <?php
                                            $queryx = $this->db->get_where('penjualan', ['jualstatusbayar' => 'K', 'jualstatuslunas' => 0])->result();
                                            echo "<li><span class=\"text-muted\">Jumlah Data : <strong>" . count($queryx) . "</strong></span></li>";

                                            $queryxx = $this->db->get_where('penjualan', ['jualstatusbayar' => 'K', 'jualstatuslunas' => 0])->result();
                                            ?>
                                            <?= "<li><span class=\"text-muted\">Ada <strong>" . count($queryxx) . "</strong> Faktur yang belum lunas</span></li>"; ?>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                        <i class="fa fa-file-invoice" style="color: #a89805; font-size:72px;"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated flipInY">
                        <div class="card-header" style="background-color: #d4067b; font-weight: bold; color:#fff;">
                            Pembayaran Piutang Pelanggan (<strong>Multiple Payment</strong>)</div>
                        <a href="<?= site_url('admin/penjualan/daftar-piutang-pelanggan') ?>">
                            <div class="card-body tomboldaftarpiutang" style="cursor: pointer;">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <ul>
                                            <?php
                                            $queryx = $this->db->get_where('penjualan', ['jualstatusbayar' => 'K', 'jualstatuslunas' => 0])->result();
                                            echo "<li><span class=\"text-muted\">Jumlah Data : <strong>" . count($queryx) . "</strong></span></li>";

                                            $queryxx = $this->db->get_where('penjualan', ['jualstatusbayar' => 'K', 'jualstatuslunas' => 0])->result();
                                            ?>
                                            <?= "<li><span class=\"text-muted\">Ada <strong>" . count($queryxx) . "</strong> Faktur yang belum lunas</span></li>"; ?>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                        <i class="fa fa-file-invoice" style="color: #a89805; font-size:72px;"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated slideInRight">
                        <div class="card-header" style="background-color: #660554; font-weight: bold; color:#fff;">
                            Return Penjualan</div>
                        <a href="<?= site_url('admin/penjualan/return-input') ?>">
                            <div class="card-body tombolreturnpenjualan">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h5 class="card-subtitle mb-2 text-muted">Return Produk<h3>
                                    </div>
                                    <div class="col-lg-4">
                                        <i class="fa fa-exchange-alt" style="color: #660554; font-size:72px;"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>