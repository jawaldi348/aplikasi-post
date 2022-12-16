<?php
$uri = $this->uri->segment(2);
if ($this->session->userdata('idgrup') == '1') :
?>
<ul class="navigation-menu">
    <li class="has-submenu <?php if ($uri == 'home') echo 'active'; ?>">
        <a href="<?= site_url('admin/home/index') ?>"><i class="fa fa-fw fa-tachometer-alt"></i> Dashboard</a>
    </li>
    <li class="has-submenu">
        <a href="#"><i class="fa fa-file"></i> Master</a>
        <ul class="submenu">
            <li>
                <a href="<?= site_url('admin/produk/home') ?>"><i class="fa fa-fw fa-tasks"></i> Produk</a>
            </li>
            <li>
                <a href="<?= site_url('admin/satuan/index') ?>"><i class="fa fa-fw fa-tasks"></i> Satuan</a>
            </li>
            <li>
                <a href="<?= site_url('admin/kategori/index') ?>"><i class="fa fa-fw fa-tasks"></i> Kategori</a>
            </li>
            <li>
                <a href="<?= site_url('admin/pemasok/index') ?>">
                    <i class="fa fa-fw fa-truck-moving"></i> Supplier
                </a>
            </li>
            <li>
                <a href="<?= site_url('admin/member/index') ?>">
                    <i class="fa fa-fw fa-users"></i> Member
                </a>
            </li>
            <li>
                <a href="<?= site_url('biaya/index') ?>">
                    <i class="fa fa-money-bill"></i> Biaya
                </a>
            </li>
            <li>
                <a href="<?= site_url('aset-tetap/index') ?>">
                    <i class="fa fa-home"></i> Aset Tetap
                </a>
            </li>
            <li>
                <a href="<?= site_url('saldo/index') ?>">
                    <i class="fa fa-money-bill"></i> Saldo Pulsa
                </a>
            </li>
        </ul>
    </li>

    <li class="has-submenu">
        <a href="#"><i class="mdi mdi-gauge"></i>Transaksi</a>
        <ul class="submenu">
            <li>
                <a href="<?= site_url('beli/index') ?>">
                    <i class="fa fa-truck"></i> Pembelian
                </a>
            </li>
            <li>
                <a href="<?= site_url('admin/penjualan/index') ?>">
                    <i class="fa fa-cash-register"></i> Penjualan
                </a>
            </li>

            <li>
                <a href="<?= site_url('pemakaian/index') ?>">
                    <i class="fa fa-tasks"></i> Pemakaian Barang
                </a>
            </li>

            <li>
                <a href="<?= site_url('admin/pengambilandiskon/input') ?>">
                    <i class="fa fa-tasks"></i> Pengambilan Diskon Member
                </a>
            </li>

        </ul>
    </li>


    <li class="has-submenu <?php
                                if ($uri == 'kaskecil') echo 'active';
                                ?>">
        <a href="#"><i class="fa fa-cogs"></i> Setting </a>
        <ul class="submenu">
            <li>
                <a href="<?= site_url('admin/toko/index') ?>"><i class="fa fa-store-alt"></i> Nama Toko</a>
            </li>
            <li>
                <a href="<?= site_url('neraca/input-awal') ?>"><i class="fa fa-newspaper"></i> Input Awal Neraca</a>
            </li>
            <li>
                <a href="<?= site_url('setting-diskon-member/index') ?>"><i class="fa fa-tasks"></i> Diskon Member</a>
            </li>
            <li>
                <a href="<?= site_url('pengaturan/index') ?>"><i class="fa fa-tasks"></i> Pengaturan Lainnya</a>
            </li>
            <!-- <li> -->
            <!-- <a href="<?//= site_url('admin/kaskecil/index') ?>"><i class="fa fa-comments-dollar"></i> Kas Kecil</a> -->
            <!-- </li> -->
        </ul>
    </li>

    <li class="has-submenu">
        <a href="#"><i class="fa fa-file-archive"></i> Report</a>
        <ul class="submenu">
            <li class="has-submenu">
                <a href="#">Neraca</a>
                <ul class="submenu">
                    <li><a href="<?= site_url('neraca/cek') ?>">Cek Neraca</a></li>
                    <li><a href="<?= site_url('laporan/neraca-labarugi') ?>">Laporan Neraca Laba/Rugi</a></li>

                </ul>
            </li>
            <li class="has-submenu">
                <a href="#">Pembelian</a>
                <ul class="submenu">
                    <li>
                        <a href="<?= site_url('laporan/transaksi-pembelian') ?>">Laporan Pembelian</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/hutang') ?>">Laporan Hutang</a>
                    </li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#">Penjualan</a>
                <ul class="submenu">
                    <li>
                        <a href="<?= site_url('laporan/grafik-penjualan') ?>">
                            <i class="fa fa-chart-bar"></i> Grafik Penjualan
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/penjualan-kasir') ?>">Laporan Penjualan</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/laba-rugi') ?>">laporan Laba Rugi</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/sisa-pembulatan') ?>">Laporan Sisa Pembulatan</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/piutang') ?>">Laporan Piutang</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="<?= site_url('laporan/arus-kas') ?>">Laporan Arus Kas</a>
            </li>

            <li class="has-submenu">
                <a href="#">Produk</a>
                <ul class="submenu">
                    <li>
                        <a href="<?= site_url('laporan/persediaan-produk') ?>">Laporan Persediaan Produk</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/perjalanan-stok-produk') ?>">Laporan Stok</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/produk-kadaluarsa') ?>">Laporan Produk Kadaluarsa</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/koreksi-stok') ?>">Laporan Koreksi Stok</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/stok-opname') ?>">Laporan Stok Opname</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/produk-laku') ?>">Grafik Produk Yang Laku</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="<?= site_url('laporan/tabungan-diskon-member') ?>">Laporan Diskon Member</a>
            </li>
            <li>
                <a href="<?= site_url('laporan/pemakaian-barang') ?>">Laporan Pemakaian Barang</a>
            </li>
            <!-- <li>
            <a href="<?//php echo site_url('laporan/ksf') ?>">Laporan KSF</a>
        </li> -->


        </ul>
    </li>

    <li>
        <a href="<?= site_url('admin/manuser') ?>"><i class="fa fa-cogs"></i> Manajemen User</a>
    </li>
    <li>
        <a href="<?= site_url('utility/index') ?>"><i class="fa fa-fw fa-cogs"></i> Utility</a>
    </li>
</ul>

<?php elseif ($this->session->userdata('idgrup') == '2') : ?>
<ul class="navigation-menu">
    <li class="has-submenu <?php if ($uri == 'home') echo 'active'; ?>">
        <a href="<?= site_url('k/home/index') ?>"><i class="fa fa-fw fa-tachometer-alt"></i> Dashboard</a>
    </li>
    <!-- <li class="has-submenu">
    <a href="#"><i class="fa fa-tasks"></i> Pembelian</a>
    <ul class="submenu">
        <li>
            <a href="<?//= site_url('beli/input') ?>"><i class="fa fa-tasks"></i> Input Pembelian</a>
        </li>
        <li>
            <a href="<?//= site_url('beli/return-input') ?>"><i class="fa fa-tasks"></i> Return Pembelian</a>
        </li>
        <li>
            <a href="<?//= site_url('beli/data') ?>"><i class="fa fa-tasks"></i> Pembayaran Hutang</a>
        </li>
    </ul>
</li> -->
    <li>
        <a href="<?= site_url('beli/index') ?>">
            <i class="fa fa-truck"></i> Pembelian
        </a>
    </li>
</ul>
<?php elseif ($this->session->userdata('idgrup') == '4') : ?>
<ul class="navigation-menu">
    <li class="has-submenu<?php if ($uri == 'home') echo 'active'; ?>">
        <a href="<?= site_url('p/home/index') ?>"><i class="fa fa-fw fa-tachometer-alt"></i> Dashboard</a>
    </li>
    <li class="has-submenu">
        <a href="#"><i class="fa fa-tasks"></i> Laporan</a>
        <ul class="submenu">
            <li class="has-submenu">
                <a href="#">Neraca</a>
                <ul class="submenu">
                    <li><a href="<?= site_url('neraca/cek') ?>">Cek Neraca</a></li>
                    <li><a href="<?= site_url('laporan/neraca-labarugi') ?>">Laporan Neraca Laba/Rugi</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#">Pembelian</a>
                <ul class="submenu">
                    <li>
                        <a href="<?= site_url('laporan/transaksi-pembelian') ?>">Laporan Pembelian</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/hutang') ?>">Laporan Hutang</a>
                    </li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#">Penjualan</a>
                <ul class="submenu">
                    <li>
                        <a href="<?= site_url('laporan/grafik-penjualan') ?>">
                            <i class="fa fa-chart-bar"></i> Grafik Penjualan
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/penjualan-kasir') ?>">Laporan Penjualan</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/laba-rugi') ?>">laporan Laba Rugi</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/sisa-pembulatan') ?>">Laporan Sisa Pembulatan</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/piutang') ?>">Laporan Piutang</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="<?= site_url('laporan/arus-kas') ?>">Laporan Arus Kas</a>
            </li>
            <li class="has-submenu">
                <a href="#">Produk</a>
                <ul class="submenu">
                    <li>
                        <a href="<?= site_url('laporan/persediaan-produk') ?>">Laporan Persediaan Produk</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/perjalanan-stok-produk') ?>">Laporan Stok</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/produk-kadaluarsa') ?>">Laporan Produk Kadaluarsa</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/koreksi-stok') ?>">Laporan Koreksi Stok</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/stok-opname') ?>">Laporan Stok Opname</a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan/produk-laku') ?>">Grafik Produk Yang Laku</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="<?= site_url('laporan/tabungan-diskon-member') ?>">Laporan Diskon Member</a>
            </li>
        </ul>
    </li>
</ul>
<?php endif; ?>