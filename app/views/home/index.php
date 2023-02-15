@extends(layouts/index)
@section(content)
<?php
$session = $this->session->userdata('userData');
if ($this->session->userdata('fotouser') == '' || $this->session->userdata('fotouser') == null || !file_exists($this->session->userdata('fotouser'))) {
    $fotouser = "./assets/images/users/avatar.png";
} else {
    $fotouser = $session['foto'];
}
?>
<div class="col-lg-12">
    <div class="card card-body">
        <p class="card-text">
        <div class="row">
            <div class="col-sm-2">
                <div class="card">
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="<?= site_url('profil/index') ?>">
                                <div class="card">
                                    <img src="<?= base_url($fotouser) ?>" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h6 class="card-title" style="border-bottom: 2px solid blue;">
                                            <?= $session['nama'] ?></h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-sm-10">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card border-light mb-1 animated zoomInUp">
                            <div class="card-header bg-info text-white">Transaksi Semua Penjualan</div>
                            <div class="card-body">
                                <?php
                                $penjualan_hariini = $this->db->query("SELECT IFNULL(SUM(jualtotalbersih),0) AS totalhariini FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') = CURRENT_DATE()")->row_array();

                                $penjualan_bulanini = $this->db->query("SELECT IFNULL(SUM(jualtotalbersih),0) AS totalhariini FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m')")->row_array();
                                ?>
                                <div class=" row">
                                    <div class="col-lg-8">
                                        <span>Total Hari ini :
                                            <strong><?= "Rp. " . number_format($penjualan_hariini['totalhariini'], 0, ",", "."); ?></strong></span>
                                        <br>
                                        <span>Total Bulan ini :
                                            <strong><?= "Rp. " . number_format($penjualan_bulanini['totalhariini'], 0, ",", "."); ?></strong></span>

                                    </div>
                                    <div class="col-lg-4">
                                        <i class="fa fa-cash-register" style="color: #0a9dab; font-size:72px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="card border-light mb-1 animated flipInY">
                            <div class="card-header" style="background-color: #aff76a; font-weight: bold; color:#000;">
                                Daftar Piutang</div>
                            <a href="<?= site_url('admin/penjualan/all-data-piutang') ?>">
                                <div class="card-body tomboldaftarpiutang" style="cursor: pointer;">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <?php
                                            $queryx = $this->db->get_where('penjualan', ['jualstatusbayar' => 'K'])->result();
                                            echo "<span class=\"text-muted\">Jumlah Data : <strong>" . count($queryx) . "</strong></span>";

                                            $queryxx = $this->db->get_where('penjualan', ['jualstatusbayar' => 'K', 'jualstatuslunas' => 0])->result();
                                            ?>
                                            <?= "<span class=\"text-muted\">Ada <strong>" . count($queryxx) . "</strong> Faktur yang belum lunas</span>"; ?>
                                        </div>
                                        <div class="col-lg-4">
                                            <i class="fa fa-file-invoice" style="color: #a89805; font-size:72px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
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
                                            Faktur Jatuh Tempo 3 Hari Terakhir : <a href="<?= site_url('beli/faktur-jatuh-tempo') ?>"><?= $totalhutang_jatuhtempo; ?></a>
                                        </span>
                                    </div>
                                    <div class="col-lg-4">
                                        <i class="fa fa-tasks" style="color: #8c0f06; font-size:72px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card border-light mb-1 animated zoomInUp">
                    <div class="card-header" style="background-color: #005bed; color:white; font-weight: bold;">
                        Grafik Penjualan Per-Bulan
                    </div>
                    <div class="card-body">
                        <p>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Input Bulan</label>
                            <div class="col-sm-6">
                                <input type="month" class="form-control-sm form-control" name="bulan" id="bulan" value="<?= date('Y-m'); ?>">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-sm btn-success btntampilgrafikpenjualan">
                                    Tampilkan
                                </button>
                            </div>
                        </div>
                        </p>
                        <div class="viewtampilgrafik"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card border-light mb-1 animated zoomInUp">
                    <div class="card-header" style="background-color: #128c33; color:white; font-weight: bold;">
                        10 Member Yang Sering Belanja Pada Tahun ini
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="tahunbelanjamember" value="<?= date('Y'); ?>">
                        <div class="viewgrafikbelanjamember" style="display: none;"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card border-light mb-1 animated zoomInUp">
                    <div class="card-header" style="background-color: #e0a607; color:white; font-weight: bold;">
                        10 Produk Yang Paling Laku
                    </div>
                    <div class="card-body">
                        <p>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Input Bulan</label>
                            <div class="col-sm-6">
                                <input type="month" class="form-control-sm form-control" id="bulanproduklaku" value="<?= date('Y-m'); ?>">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-sm btn-success btntampilgrafikproduklaku">
                                    Tampilkan
                                </button>
                            </div>
                        </div>
                        </p>
                        <div class="viewgrafikproduklaku" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
        </p>
    </div>
</div>
<script>
    function tampilgrafikbelanjamember() {
        $.ajax({
            type: "post",
            url: "<?= site_url('laporan/grafik_penjualanmember') ?>",
            data: {
                tahun: $('#tahunbelanjamember').val()
            },
            dataType: "json",
            beforeSend: function() {
                $('.viewgrafikbelanjamember').html('<i class="fa fa-spin fa-spinner"></i> Tunggu').fadeIn();
            },
            success: function(response) {
                if (response.data) {
                    $('.viewgrafikbelanjamember').html(response.data).show();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    }

    function tampilgrafikpenjualan() {
        $.ajax({
            type: "post",
            url: "<?= site_url('laporan/tampil-grafik-penjualan-perbulan') ?>",
            data: {
                bulan: $('#bulan').val()
            },
            dataType: "json",
            beforeSend: function() {
                $('.viewtampilgrafik').html('<i class="fa fa-spin fa-spinner"></i> Tunggu').fadeIn();
            },
            success: function(response) {
                if (response.data) {
                    $('.viewtampilgrafik').html(response.data).show();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    }

    function tampilgrafikproduklaku() {
        $.ajax({
            url: "<?= site_url('laporan/grafikproduklaku') ?>",
            dataType: "json",
            type: 'post',
            data: {
                bulan: $('#bulanproduklaku').val()
            },
            beforeSend: function() {
                $('.viewgrafikproduklaku').html(
                    '<i class="fa fa-spin fa-spinner"></i> Tunggu Grafik sedang ditampilkan').fadeIn();
            },
            success: function(response) {
                if (response.data) {
                    $('.viewgrafikproduklaku').html(response.data).show();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    }
    $(document).ready(function() {
        tampilgrafikpenjualan();
        tampilgrafikproduklaku();
        tampilgrafikbelanjamember();
        $('.btntampilgrafikpenjualan').click(function(e) {
            e.preventDefault();
            tampilgrafikpenjualan();
        });
        $('.btntampilgrafikproduklaku').click(function(e) {
            e.preventDefault();
            tampilgrafikproduklaku();
        });
    });
</script>
@endsection