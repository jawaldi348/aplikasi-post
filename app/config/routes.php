<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

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