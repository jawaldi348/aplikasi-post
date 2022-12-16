<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated zoomInUp">
                        <div class="card-header bg-info text-white">Input Barang Masuk</div>
                        <div class="card-body tombolbarangmasuk" style="cursor: pointer;">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h3 class="card-subtitle mb-2 text-muted">Input Data</h3>
                                </div>
                                <div class="col-lg-4">
                                    <i class="fa fa-sort-amount-down" style="color: #0a9dab; font-size:72px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated zoomInUp">
                        <div class="card-header" style="background-color: #027036; color:white; font-weight: bold;">Data
                            Transaksi Pembelian</div>
                        <div class="card-body tomboldata" style="cursor: pointer;">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h3 class="card-subtitle mb-2 text-muted">Total Data
                                        (<strong><?= $totaltransaksipembelian; ?></strong>)</h3>
                                </div>
                                <div class="col-lg-4">
                                    <i class="fa fa-tasks" style="color: #027036; font-size:72px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $totaldatahutang = $datahutang->num_rows();

                $totalbersih = 0;

                foreach ($datahutang->result_array() as $row) :
                    $totalbersih = $totalbersih + $row['totalbersih'];
                endforeach;

                $totalhutang_jatuhtempo = $datahutang_jatuhtempo->num_rows();
                ?>
                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated zoomInUp">
                        <div class="card-header" style="background-color: #8c0f06; color:white; font-weight: bold;">
                            Total Faktur Hutang (<?= "Rp. " . number_format($totalbersih, 2, ".", ","); ?>)</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <span class="card-subtitle mb-2 text-muted">
                                        Faktur Jatuh Tempo 3 Hari Terakhir : <a
                                            href="<?= site_url('beli/faktur-jatuh-tempo') ?>"><?= $totalhutang_jatuhtempo; ?></a>
                                    </span>
                                    <br>
                                    <a href="<?= site_url('beli/daftar-hutang') ?>">
                                        Daftar Hutang
                                    </a>
                                </div>
                                <div class="col-lg-4">
                                    <i class="fa fa-tasks" style="color: #8c0f06; font-size:72px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">


                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated zoomInUp">
                        <div class="card-header" style="background-color: #944a04; color:white; font-weight: bold;">
                            Return Pembelian
                        </div>
                        <div class="card-body tombolreturn" style="cursor: pointer;">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h4 class="card-subtitle mb-2 text-muted">
                                        Return
                                    </h4>
                                </div>
                                <div class="col-lg-4">
                                    <i class="fa fa-exchange-alt" style="color: #944a04; font-size:72px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(e) {
    $('.tombolbarangmasuk').click(function(e) {
        window.location.href = ("<?= site_url('beli/input') ?>");
    });
    $('.tomboldata').click(function(e) {
        window.location.href = ("<?= site_url('beli/data') ?>");
    });
    $('.tombolreturn').click(function(e) {
        window.location.href = ("<?= site_url('beli/return-input') ?>");
    });
});
</script>