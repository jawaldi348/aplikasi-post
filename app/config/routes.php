<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['login'] = 'auth/login';
$route['login/validasi-user'] = 'auth/login/validasi_user';
$route['logout'] = 'auth/logout';

/**
 * Modul User
 */
$route['users'] = 'users/user';
$route['users/data'] = 'users/user/data';
$route['users/create'] = 'users/user/create';
$route['users/store'] = 'users/user/store';
$route['users/edit'] = 'users/user/edit';
$route['users/update'] = 'users/user/update';
$route['users/destroy'] = 'users/user/destroy';

/**
 * Group User
 */
$route['group-user'] = 'users/group';
$route['group-user/data'] = 'users/group/data';
$route['group-user/create'] = 'users/group/create';
$route['group-user/store'] = 'users/group/store';
$route['group-user/edit'] = 'users/group/edit';
$route['group-user/update'] = 'users/group/update';
$route['group-user/destroy'] = 'users/group/destroy';
$route['group-user/autocomplete'] = 'users/group/autocomplete';

$route['produk'] = 'produk/produk';
$route['produk/home'] = 'produk/home';
$route['produk/create'] = 'produk/produk/create';

$route['satuan'] = 'produk/satuan';
$route['satuan/data'] = 'produk/satuan/data';
$route['satuan/create'] = 'produk/satuan/create';
$route['satuan/store'] = 'produk/satuan/store';
$route['satuan/show'] = 'produk/satuan/show';
$route['satuan/update'] = 'produk/satuan/update';
$route['satuan/destroy'] = 'produk/satuan/destroy';
$route['satuan/store-quick'] = 'produk/satuan/store_quick';
$route['satuan/autocomplete'] = 'produk/satuan/autocomplete';

$route['kategori'] = 'produk/kategori';
$route['kategori/data'] = 'produk/kategori/data';
$route['kategori/create'] = 'produk/kategori/create';
$route['kategori/store'] = 'produk/kategori/store';
$route['kategori/show'] = 'produk/kategori/show';
$route['kategori/update'] = 'produk/kategori/update';
$route['kategori/destroy'] = 'produk/kategori/destroy';
$route['kategori/store-quick'] = 'produk/kategori/store_quick';
$route['kategori/autocomplete'] = 'produk/kategori/autocomplete';

$route['pemasok'] = 'kontak/pemasok';
$route['pemasok/data'] = 'kontak/pemasok/data';
$route['pemasok/create'] = 'kontak/pemasok/create';
$route['pemasok/store'] = 'kontak/pemasok/store';
$route['pemasok/show'] = 'kontak/pemasok/show';
$route['pemasok/update'] = 'kontak/pemasok/update';
$route['pemasok/destroy'] = 'kontak/pemasok/destroy';

$route['member'] = 'kontak/member';
$route['member/data'] = 'kontak/member/data';
$route['member/create'] = 'kontak/member/create';
$route['member/store'] = 'kontak/member/store';
$route['member/show'] = 'kontak/member/show';
$route['member/update'] = 'kontak/member/update';
$route['member/destroy'] = 'kontak/member/destroy';
$route['member/detail/(:any)'] = 'kontak/member/detail/$1';
$route['member/cetak-kartu/(:any)'] = 'kontak/member/cetak_kartu/$1';

$route['beli/(:any)'] = 'admin/pembelian/$1';
$route['beli/(:any)/(:any)'] = 'admin/pembelian/$1/$2';
$route['pemasok/(:any)'] = 'admin/pemasok/$1';

$route['neraca/(:any)'] = 'admin/neraca/$1';
$route['neraca/(:any)/(:any)'] = 'admin/neraca/$1/$2';

$route['biaya/(:any)'] = 'admin/biaya/$1';
$route['biaya/(:any)/(:any)'] = 'admin/biaya/$1/$2';

$route['saldo/(:any)'] = 'admin/saldo/$1';
$route['saldo/(:any)/(:any)'] = 'admin/saldo/$1/$2';

$route['pulsa/(:any)'] = 'admin/pulsa/$1';
$route['pulsa/(:any)/(:any)'] = 'admin/pulsa/$1/$2';

$route['laporan/(:any)'] = 'admin/laporan/$1';
$route['laporan/(:any)/(:any)'] = 'admin/laporan/$1/$2';
$route['laporan/(:any)/(:any)/(:any)'] = 'admin/laporan/$1/$2/$3';

$route['stokproduk/(:any)'] = 'admin/stokproduk/$1';
$route['stokproduk/(:any)/(:any)'] = 'admin/stokproduk/$1/$2';

$route['koreksistok/(:any)'] = 'admin/koreksistok/$1';
$route['koreksistok/(:any)/(:any)'] = 'admin/koreksistok/$1/$2';

$route['aset-tetap/(:any)'] = 'admin/aset_tetap/$1';
$route['aset-tetap/(:any)/(:any)'] = 'admin/aset_tetap/$1/$2';

$route['setting-diskon-member/(:any)'] = 'admin/setting_diskon_member/$1';
$route['setting-diskon-member/(:any)/(:any)'] = 'admin/setting_diskon_member/$1/$2';

$route['pengambilandiskon/(:any)'] = 'admin/pengambilandiskon/$1';
$route['pengambilandiskon/(:any)/(:any)'] = 'admin/pengambilandiskon/$1/$2';

$route['pengaturan/(:any)'] = 'admin/pengaturan/$1';
$route['pengaturan/(:any)/(:any)'] = 'admin/pengaturan/$1/$2';

$route['pemakaian/(:any)'] = 'admin/pemakaian/$1';
$route['pemakaian/(:any)/(:any)'] = 'admin/pemakaian/$1/$2';

/**
 * Modul Profil Bisnis
 */
$route['profil-bisnis'] = 'settings/bisnis';
$route['profil-bisnis/edit'] = 'settings/bisnis/edit';
$route['profil-bisnis/update'] = 'settings/bisnis/update';

/**
 * Modul Backup Database
 */
$route['utility'] = 'settings/utility';
$route['utility/backup'] = 'settings/utility/backup';
$route['utility/restore'] = 'settings/utility/restore';

/**
 * Modul Profil
 */
$route['profil'] = 'settings/profil';
$route['profil/change-image'] = 'settings/profil/change_image';
$route['profil/update-image'] = 'settings/profil/update_image';
$route['profil/change-profil'] = 'settings/profil/change_profil';
$route['profil/update-profil'] = 'settings/profil/update_profil';
$route['profil/change-password'] = 'settings/profil/change_password';
$route['profil/update-password'] = 'settings/profil/update_password';
