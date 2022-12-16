<div class="col-lg-12">
    <div class="card border-light mb-1">
        <div class="card-header"></div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated flipInY">
                        <div class="card-header bg-info text-white">Input Pembelian</div>
                        <div class="card-body tombolinputpembelian" style="cursor: pointer;">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h3 class="card-subtitle mb-2 text-muted">Input Faktur</h3>
                                </div>
                                <div class="col-lg-4">
                                    <i class="fa fa-dolly-flatbed" style="color: #0a9dab; font-size:72px;"></i>
                                </div>
                            </div>
                        </div>
                        </>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated zoomInUp">
                        <div class="card-header bg-primary text-white">Data Transaksi Pembelian</div>
                        <div class="card-body tomboldata" style="cursor:pointer;">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h3 class="card-subtitle mb-2 text-muted">Total Data :</h3>
                                    <?php
                                    $query = $this->db->get('pembelian')->result();
                                    echo '<h3>' . number_format(count($query), 0) . '</h3>';
                                    ?>
                                </div>
                                <div class="col-lg-4">
                                    <i class="fa fa-list-alt" style="color: #091980; font-size:72px;"></i>
                                </div>
                            </div>
                        </div>
                        </>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card border-light mb-1 animated fadeInRight">
                        <div class="card-header bg-warning text-white">Pembayaran Hutang</div>
                        <div class="card-body tombolpembyaranhutang" style="cursor: pointer;">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h3 class="card-subtitle mb-2 text-muted">Proses Hutang Pembelian</h3>
                                </div>
                                <div class="col-lg-4">
                                    <i class="fa fa-money-check" style="color: #a89805; font-size:72px;"></i>
                                </div>
                            </div>
                        </div>
                        </>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.tombolinputpembelian').click(function(e) {
        e.preventDefault();
        window.location.href = ("<?= site_url('admin/pembelian/index') ?>");
    });
    $('.tombolpembyaranhutang').click(function(e) {
        e.preventDefault();
        window.location.href = ("<?= site_url('admin/hutang/data') ?>");
    });
    $('.tomboldata').click(function(e) {
        e.preventDefault();
        window.location.href = ("<?= site_url('admin/pembelian/data') ?>");
    });
});
</script>