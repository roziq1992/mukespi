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
$route['portal'] = 'portal/index';

// Profil pengguna (nama, avatar, password)
$route['profile'] = 'profile/index';

// Manajemen User & Role Menu Akses (admin)
$route['users'] = 'users/index';
$route['users/create'] = 'users/create';
$route['users/store'] = 'users/create_action';
$route['users/edit/(:num)'] = 'users/update/$1';
$route['users/update'] = 'users/update_action';
$route['users/toggle_active/(:num)'] = 'users/toggle_active/$1';
$route['users/delete/(:num)'] = 'users/delete/$1';

$route['menu'] = 'menu/index';
$route['menu/create'] = 'menu/create';
$route['menu/store'] = 'menu/store';
$route['menu/edit/(:num)'] = 'menu/edit/$1';
$route['menu/update'] = 'menu/update';
$route['menu/delete/(:num)'] = 'menu/delete/$1';
$route['menu/roles'] = 'menu/roles';
$route['menu/roles/create'] = 'menu/role_create';
$route['menu/roles/store'] = 'menu/role_store';
$route['menu/roles/edit/(:num)'] = 'menu/role_edit/$1';
$route['menu/roles/update'] = 'menu/role_update';
$route['menu/roles/delete/(:num)'] = 'menu/role_delete/$1';
$route['menu/role_access/(:num)'] = 'menu/role_access/$1';
$route['menu/role_access'] = 'menu/role_access/1';
$route['menu/role_access/save'] = 'menu/role_access_save';

// Manajemen Data Pegawai (mutasi, riwayat, nonaktif)
$route['pegawai'] = 'pegawai/index';
$route['pegawai/create'] = 'pegawai/create';
$route['pegawai/store'] = 'pegawai/create_action';
$route['pegawai/edit/(:num)'] = 'pegawai/update/$1';
$route['pegawai/update'] = 'pegawai/update_action';
$route['pegawai/detail/(:num)'] = 'pegawai/detail/$1';
$route['pegawai/mutasi'] = 'pegawai/mutasi_action';
$route['pegawai/nonaktif'] = 'pegawai/nonaktif_action';
$route['pegawai/aktifkan'] = 'pegawai/aktifkan_action';
$route['pegawai/export_excel'] = 'pegawai/export_excel';
$route['pegawai/template_excel'] = 'pegawai/template_excel';
$route['pegawai/import_excel'] = 'pegawai/import_excel';

$route['master_ep'] = 'master_ep/index';
$route['master_ep/pokja'] = 'master_ep/pokja';
$route['master_ep/pokja_form'] = 'master_ep/pokja_form';
$route['master_ep/pokja_form/(:num)'] = 'master_ep/pokja_form/$1';
$route['master_ep/pokja_delete/(:num)'] = 'master_ep/pokja_delete/$1';
$route['master_ep/pokja_toggle/(:num)/(:any)'] = 'master_ep/pokja_toggle/$1/$2';
$route['master_ep/pokja_standar'] = 'master_ep/pokja_standar';
$route['master_ep/pokja_standar/(:num)'] = 'master_ep/pokja_standar/$1';
$route['master_ep/rapikan_urutan'] = 'master_ep/rapikan_urutan';
$route['master_ep/rapikan_urutan/(:num)'] = 'master_ep/rapikan_urutan/$1';
$route['master_ep/standar'] = 'master_ep/standar';
$route['master_ep/standar_form'] = 'master_ep/standar_form';
$route['master_ep/standar_form/(:num)'] = 'master_ep/standar_form/$1';
$route['master_ep/standar_delete/(:num)'] = 'master_ep/standar_delete/$1';
$route['master_ep/standar_toggle/(:num)/(:any)'] = 'master_ep/standar_toggle/$1/$2';
$route['master_ep/elemen'] = 'master_ep/elemen';
$route['master_ep/elemen_form'] = 'master_ep/elemen_form';
$route['master_ep/elemen_form/(:num)'] = 'master_ep/elemen_form/$1';
$route['master_ep/elemen_delete/(:num)'] = 'master_ep/elemen_delete/$1';
$route['master_ep/elemen_toggle/(:num)/(:any)'] = 'master_ep/elemen_toggle/$1/$2';

// QR Code data pegawai. t/ = publik (hasil scan tanpa login, memakai token,
// bukan NIK). cetak/ & token_baru/ memakai NIK karena hanya untuk yang login.
$route['pegawai_qr'] = 'pegawai_qr/index';
$route['pegawai_qr/cetak_semua'] = 'pegawai_qr/cetak_semua';
$route['pegawai_qr/t/(:alphanum)'] = 'pegawai_qr/t/$1';
$route['pegawai_qr/cetak/(:num)'] = 'pegawai_qr/cetak/$1';
$route['pegawai_qr/token_baru/(:num)'] = 'pegawai_qr/token_baru/$1';

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
$route['data_inventaris/get_maintenance_tracking/(:any)'] = 'data_inventaris/get_maintenance_tracking/$1';
$route['data_inventaris/maintenance_history_form/(:any)'] = 'data_inventaris/maintenance_history_form/$1';
$route['data_inventaris/maintenance_history_form'] = 'data_inventaris/maintenance_history_form';
$route['data_inventaris/sparepart_delete/(:any)'] = 'data_inventaris/sparepart_delete/$1';

// Survei Kepuasan Pasien — formulir publik (tanpa login)
$route['survei'] = 'survei/index';
$route['survei/kirim'] = 'survei/kirim';
$route['survei/terima'] = 'survei/terima';

// Survei Kepuasan Pasien — monitoring internal (butuh login)
$route['survei_admin'] = 'survei_admin/index';
$route['survei_admin/data'] = 'survei_admin/data';
$route['survei_admin/detail/(:num)'] = 'survei_admin/detail/$1';
$route['survei_admin/detail_responden/(:any)'] = 'survei_admin/detail_responden/$1';
$route['survei_admin/tindak_lanjut'] = 'survei_admin/tindak_lanjut';
$route['survei_admin/hapus/(:num)'] = 'survei_admin/hapus/$1';
$route['survei_admin/ekspor'] = 'survei_admin/ekspor';
$route['survei_admin/aspek'] = 'survei_admin/aspek';
$route['survei_admin/aspek/simpan'] = 'survei_admin/aspek_simpan';
$route['survei_admin/aspek/hapus/(:num)'] = 'survei_admin/aspek_hapus/$1';
$route['survei_admin/aspek/toggle/(:num)'] = 'survei_admin/aspek_toggle/$1';
