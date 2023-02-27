<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['login'] = 'auth/login';
$route['login/validasi-user'] = 'auth/login/validasi_user';
$route['logout'] = 'auth/logout';

$route['satuan'] = 'produk/satuan';
$route['satuan/data'] = 'produk/satuan/data';
$route['satuan/create'] = 'produk/satuan/create';
$route['satuan/store'] = 'produk/satuan/store';
$route['satuan/show'] = 'produk/satuan/show';
$route['satuan/update'] = 'produk/satuan/update';
$route['satuan/destroy'] = 'produk/satuan/destroy';

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

$route['utility/(:any)'] = 'admin/utility/$1';
$route['utility/(:any)/(:any)'] = 'admin/utility/$1/$2';
