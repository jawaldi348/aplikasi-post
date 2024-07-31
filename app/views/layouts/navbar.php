<?php $uri = $this->uri->segment(1);
$session = $this->session->userdata('userData');
$group = $session['idgroup']; ?>
<div class="navbar-custom">
    <div class="container-fluid">
        <div id="navigation">
            <?php if ($group == '1') : ?>
                <ul class="navigation-menu">
                    <li class="has-submenu <?= $uri == null || $uri == 'welcome' ? 'active' : null ?>">
                        <a href="<?= site_url() ?>"><i class="fa fa-fw fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="has-submenu <?= in_array($uri, ['produk', 'satuan', 'kategori', 'pemasok', 'member']) ? 'active' : '' ?>">
                        <a href="#"><i class="fa fa-file"></i> Master</a>
                        <ul class="submenu">
                            <li>
                                <a href="<?= site_url('produk/home') ?>"><i class="fa fa-fw fa-tasks"></i> Produk</a>
                            </li>
                            <li class="<?= $uri == 'satuan' ? ' active' : null ?>">
                                <a href="<?= site_url('satuan') ?>"><i class="fa fa-fw fa-tasks"></i> Satuan</a>
                            </li>
                            <li class="<?= $uri == 'kategori' ? ' active' : null ?>">
                                <a href="<?= site_url('kategori') ?>"><i class="fa fa-fw fa-tasks"></i> Kategori</a>
                            </li>
                            <li class="<?= $uri == 'pemasok' ? ' active' : null ?>">
                                <a href="<?= site_url('pemasok') ?>"><i class="fa fa-fw fa-truck-moving"></i> Pemasok</a>
                            </li>
                            <li class="<?= $uri == 'member' ? ' active' : null ?>">
                                <a href="<?= site_url('member') ?>"><i class="fa fa-fw fa-users"></i> Member</a>
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
                            <!-- <li><a href="site_url('laporan/ksf')">Laporan KSF</a></li> -->
                        </ul>
                    </li>
                    <li class="has-submenu <?= in_array($uri, ['users', 'group-user']) ? 'active' : '' ?>">
                        <a href="#"><i class="fa fa-cogs"></i> Manajemen User</a>
                        <ul class="submenu">
                            <li class="<?= $uri == 'users' ? ' active' : null ?>">
                                <a href="<?= site_url('users') ?>"><i class="fa fa-fw fa-users"></i> User</a>
                            </li>
                            <li class="<?= $uri == 'group-user' ? ' active' : null ?>">
                                <a href="<?= site_url('group-user') ?>"><i class="fa fa-fw fa-object-group"></i> Grup User</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu <?= in_array($uri, ['profil-bisnis', 'utility']) ? 'active' : '' ?>">
                        <a href="#"><i class="fa fa-cogs"></i> Pengaturan</a>
                        <ul class="submenu">
                            <li class="<?= $uri == 'profil-bisnis' ? ' active' : null ?>">
                                <a href="<?= site_url('profil-bisnis') ?>"><i class="fa fa-store-alt"></i> Toko Anda</a>
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
                            <!-- <a href="site_url('admin/kaskecil/index')"><i class="fa fa-comments-dollar"></i> Kas Kecil</a> -->
                            <!-- </li> -->
                            <li class="dropdown-divider"></li>
                            <li class="<?= $uri == 'utility' ? ' active' : null ?>">
                                <a href="<?= site_url('utility') ?>"><i class="fa fa-fw fa-cogs"></i> Backup DB</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php elseif ($group == '2') : ?>
                <ul class="navigation-menu">
                    <li class="has-submenu <?php if ($uri == 'home') echo 'active'; ?>">
                        <a href="<?= site_url('k/home/index') ?>"><i class="fa fa-fw fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <!-- <li class="has-submenu">
                        <a href="#"><i class="fa fa-tasks"></i> Pembelian</a>
                        <ul class="submenu">
                            <li>
                                <a href="site_url('beli/input')"><i class="fa fa-tasks"></i> Input Pembelian</a>
                            </li>
                            <li>
                                <a href="site_url('beli/return-input')"><i class="fa fa-tasks"></i> Return Pembelian</a>
                            </li>
                            <li>
                                <a href="site_url('beli/data')"><i class="fa fa-tasks"></i> Pembayaran Hutang</a>
                            </li>
                        </ul>
                    </li> -->
                    <li>
                        <a href="<?= site_url('beli/index') ?>">
                            <i class="fa fa-truck"></i> Pembelian
                        </a>
                    </li>
                </ul>
            <?php elseif ($group == '4') : ?>
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
        </div>
    </div>
</div>