<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-warning"
                onclick="window.location='<?= site_url('admin/produk/home') ?>'">
                &laquo; Kembali
            </button>
        </div>
        <div class="card-body">
            <div class="col-lg-12">
                <?= form_open('admin/stokproduk/index'); ?>
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Cari</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm" name="cari" id="cari"
                            placeholder="Cari berdasarkan kode / nama produk" autofocus="autofocus"
                            value="<?= $this->session->userdata('cariproduk'); ?>">
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-sm btn-success" name="btncari">
                            Cari
                        </button>
                        <button type="button" class="btn btn-sm btn-info"
                            onclick="window.location='<?= site_url('admin/stokproduk/resetpencarian') ?>'">
                            Reset Pencarian
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered" id="dataproduk" width="100%">
                        <thead>
                            <tr style="font-weight: b;">
                                <th>No</th>
                                <th>Kode</th>
                                <th>Produk</th>
                                <th>Tgl.Kadaluarsa</th>
                                <th>Satuan</th>
                                <th>Harga Beli (Rp)</th>
                                <th>Harga Jual (Rp)</th>
                                <th>Stok Tersedia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nomor = 0 + $this->uri->segment('4');
                            foreach ($tampildata->result_array() as $r) :
                                $nomor++;
                                $kodebarcode = $r['kodebarcode'];
                                $namaproduk = $r['namaproduk'];

                                $query_cekkadaluarsa = $this->db->query("SELECT produk_tglkadaluarsa.*,namaproduk,TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) AS selisih_bulan FROM produk_tglkadaluarsa JOIN produk ON produk.`kodebarcode`=produk_tglkadaluarsa.`kodebarcode` WHERE produk_tglkadaluarsa.`kodebarcode` = '$kodebarcode' ORDER BY tglkadaluarsa ASC LIMIT 1");

                                if ($query_cekkadaluarsa->num_rows() > 0) {
                                    $row_kadaluarsa = $query_cekkadaluarsa->row_array();
                                    $selisih_bulan = $row_kadaluarsa['selisih_bulan'];
                                    if ($selisih_bulan <= 6 && $selisih_bulan > 3) {
                                        $warna = "style='background-color:green;color:white;'";
                                    }
                                    if ($selisih_bulan <= 3 && $selisih_bulan > 0) {
                                        $warna = "style='background-color:yellow;'";
                                    }
                                    if ($selisih_bulan <= 0) {
                                        $warna = "style='background-color:red; color:white;'";
                                    }
                                    $tglkadaluarsa = date('d-m-Y', strtotime($row_kadaluarsa['tglkadaluarsa']));
                                } else {
                                    $warna = '';
                                    $tglkadaluarsa = '-';
                                }

                            ?>
                            <tr <?= $warna; ?>>
                                <td><?= $nomor; ?></td>
                                <td><?= "<a href=\"#\" title=\"$r[kodebarcode]\" onclick=\"showDetail('" . sha1($r['kodebarcode']) . "')\">" . $r['kodebarcode'] . "</a>"; ?>
                                </td>
                                <td><?= $r['namaproduk']; ?></td>
                                <td><?= $tglkadaluarsa; ?></td>
                                <td><?= $r['satnama']; ?></td>
                                <td><?= number_format($r['hargabeli'], 2, ",", "."); ?></td>
                                <td><?= number_format($r['hargajual'], 2, ",", "."); ?></td>
                                <td><?= number_format($r['stok'], 0, ",", "."); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p>
                    <?= $this->pagination->create_links(); ?>
                </p>
            </div>
        </div>
    </div>
</div>
<script>
function showDetail(kode) {
    window.location.href = ("<?= site_url('stokproduk/detailproduk/') ?>" + kode);
}

$(document).ready(function() {
    // tampildata();
});
</script>