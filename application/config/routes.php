<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
$route['default_controller'] = 'auth';
$route['404_override'] = 'auth/blocked';
$route['translate_uri_dashes'] = FALSE;

// custom routes
$route['login'] = 'auth';
$route['register'] = 'auth/register';
$route['logout'] = 'auth/logout';

$route['operan'] = 'operan/index';
$route['operan/create'] = 'operan/create';
$route['operan/store'] = 'operan/create_action';
$route['operan/edit/(:num)'] = 'operan/update/$1';
$route['operan/delete/(:num)'] = 'operan/delete/$1';
$route['operan/detail/(:num)'] = 'operan/read/$1';
$route['operan/dashboard'] = 'operan/dashboard';

// Manajemen Surat Internal & Eksternal
$route['surat'] = 'surat/index';
$route['surat/create'] = 'surat/create';
$route['surat/store'] = 'surat/store';
$route['surat/detail/(:num)'] = 'surat/detail/$1';
$route['surat/download/(:num)/(:any)'] = 'surat/download/$1/$2';
$route['surat/lampiran/(:num)'] = 'surat/download_lampiran/$1';
$route['surat/hapus/(:num)'] = 'surat/hapus/$1';
$route['surat/disposisi/selesai/(:num)'] = 'surat/selesai_disposisi/$1';
$route['surat_sekretaris'] = 'surat_sekretaris/index';
$route['surat_masuk'] = 'surat_sekretaris/index';
$route['surat_sekretaris/proses/(:num)'] = 'surat_sekretaris/proses/$1';
$route['surat_sekretaris/simpan/(:num)'] = 'surat_sekretaris/simpan/$1';
$route['surat_direktur'] = 'surat_direktur/index';
$route['surat_direktur/proses/(:num)'] = 'surat_direktur/proses/$1';
$route['surat_direktur/simpan/(:num)'] = 'surat_direktur/simpan/$1';
$route['surat_direktur/selesai_disposisi/(:num)'] = 'surat_direktur/selesai_disposisi/$1';
