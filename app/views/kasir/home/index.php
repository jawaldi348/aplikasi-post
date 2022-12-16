<?php
if ($this->session->userdata('fotouser') == '' || $this->session->userdata('fotouser') == null || !file_exists($this->session->userdata('fotouser'))) {
    $fotouser = "./assets/images/users/avatar.png";
} else {
    $fotouser = $this->session->userdata('fotouser');
}
?>
<div class="col-lg-12">
    <div class="card m-b-30 card-body">
        <p class="card-text">
        <div class="row">
            <div class="col-sm-2">
                <div class="card">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <img src="<?= base_url($fotouser) ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h6 class="card-title" style="border-bottom: 2px solid blue;">
                                        <?= $this->session->userdata('namalengkapuser'); ?></h6>
                                    <p class="card-text">Level : <?= $this->session->userdata('namagrup'); ?></p>
                                    <a href="#" class="btn btn-primary btn-sm"
                                        onclick="window.location='<?= site_url('profil/index') ?>'">Update Profil ?</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-10">
                <div class="row">
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
                        <div class="card border-light mb-1 animated zoomInUp">
                            <div class="card-header text-white" style="background-color: #084f1b;">Kasir</div>
                            <a href="<?= site_url('kasir/input') ?>">
                                <div class="card-body tombolMesinKasir">
                                    <div class=" row">
                                        <div class="col-lg-8">
                                            <h3 class="card-subtitle mb-2 text-muted">Mesin Kasir</h3>
                                        </div>
                                        <div class="col-lg-4">
                                            <i class="fa fa-cash-register" style="color: #084f1b; font-size:72px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card border-light mb-1 animated flipInY">
                            <div class="card-header text-white" style="background-color: #63045b;">Transaksi Di Tahan
                            </div>
                            <a href="<?= site_url('admin/penjualan/transaksiditahan') ?>">
                                <div class="card-body tombolTransaksiditahan">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <h3 class="card-subtitle mb-2 text-muted">Total Data :</h3>
                                            <?php
                                            $queryx = $this->db->get_where('penjualan', ['jualstatusbayar' => 'H', 'jualuserinput' => $this->session->userdata('username')])->result();
                                            echo '<h3>' . number_format(count($queryx), 0) . '</h3>';
                                            ?>
                                        </div>
                                        <div class="col-lg-4">
                                            <i class="fa fa-hand-holding-usd"
                                                style="color: #63045b; font-size:72px;"></i>
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
</div>