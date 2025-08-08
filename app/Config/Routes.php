<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

use CodeIgniter\Router\RouteCollection;



/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/file', 'File::file_doang');
$routes->get('/file', 'File::file_doang');


// ========================
// ROUTING PEMBAYARAN SPP
// ========================

// Halaman utama dashboard pembayaran
$routes->get('/', 'PembayaranSPP::spp');
$routes->get('pembayaranspp', 'PembayaranSPP::spp');
$routes->get('pembayaran-spp', 'PembayaranSPP::spp'); // alias opsional

// Tambah data pembayaran
$routes->post('pembayaranspp/tambah', 'PembayaranSPP::tambah');

// Lihat data berdasarkan status pembayaran
$routes->get('detail/lunas', 'PembayaranSPP::detailLunas');
$routes->get('detail/cicilan', 'PembayaranSPP::detailCicilan');
$routes->get('detail/belum', 'PembayaranSPP::detailBelum');

// Edit, Update, Hapus Data
// Gunakan (:segment) agar mendukung NIM non-numerik
$routes->get('pembayaranspp/edit/(:segment)', 'PembayaranSPP::edit/$1');
$routes->post('pembayaranspp/update/(:segment)', 'PembayaranSPP::update/$1');
$routes->get('pembayaranspp/hapus/(:segment)', 'PembayaranSPP::hapus/$1');

// Pengeluaran - Kas Besar
$routes->get('/kas-besar', 'KasBesar::kasbesar');

// Pengeluaran - Kas Kecil
$routes->get('/kas-kecil', 'KasKecil::kaskecil');

// Penggajian - Dosen
$routes->get('/penggajian-dosen', 'PenggajianDosen::gajidosen');

// Penggajian - Staff
$routes->get('/penggajian-staff', 'PenggajianStaff::gajistaff');

// Laporan - Laba Rugi
$routes->get('/laporan-laba-rugi', 'LabaRugi::labarugi');

// Laporan - Aktiva
$routes->get('/laporan-aktiva', 'LaporanAktiva::aktiva');

// Laporan - Pasiva
$routes->get('/laporan-pasiva', 'LaporanPasiva::pasiva');

// CSRF
$routes->post('/reload-csrf', 'CSRFControl::reload');

// Pengajuan KAS
$routes->get('/pengajuan-kas', 'PengajuanKas::index', ['filter' => 'auth']);
$routes->get('/pengajuan-kas/d/(:segment)', 'PengajuanKas::detail/$1', ['filter' => 'auth']);
$routes->get('/pengajuan-kas/d/(:segment)/persetujuan', 'PengajuanKas::persetujuan/$1', ['filter' => 'auth']);
$routes->get('/pengajuan-kas/(:segment)', 'PengajuanKas::draft/$1', ['filter' => 'auth']);
// Pengajuan KAS -> ajax
$routes->post('/pengajuan-kas/create-draft', 'PengajuanKas::createDraft', ['filter' => 'auth']);
$routes->post('/pengajuan-kas/datatables', 'PengajuanKas::datatables', ['filter' => 'auth']);
$routes->post('/pengajuan-kas/save', 'PengajuanKas::save', ['filter' => 'auth']);
$routes->post('/pengajuan-kas/(:segment)/modal-edit', 'PengajuanKas::modalEdit/$1', ['filter' => 'auth']);
$routes->post('/pengajuan-kas/(:segment)/update', 'PengajuanKas::update/$1', ['filter' => 'auth']);
$routes->post('/pengajuan-kas/(:segment)/change-status', 'PengajuanKas::changeStatus/$1', ['filter' => 'auth']);
$routes->post('/pengajuan-kas/(:segment)/autosave', 'PengajuanKas::autosave/$1', ['filter' => 'auth']);
$routes->post('/pengajuan-kas/(:segment)/submit', 'PengajuanKas::submit/$1', ['filter' => 'auth']);

// Pengajuan KAS -> berkas
$routes->get('/pengajuan-kas/(:segment)/get-berkas', 'PengajuanKasBerkas::getBerkas/$1', ['filter' => 'auth']);
$routes->get('/pengajuan-kas/(:segment)/download-berkas', 'PengajuanKasBerkas::downloadBerkas/$1', ['filter' => 'auth']);
$routes->post('/pengajuan-kas/(:segment)/save-berkas', 'PengajuanKasBerkas::saveBerkas/$1', ['filter' => 'auth']);
$routes->post('/pengajuan-kas/(:segment)/modal-view-berkas', 'PengajuanKasBerkas::modalViewBerkas/$1', ['filter' => 'auth']);
$routes->delete('/pengajuan-kas/(:segment)/delete-berkas', 'PengajuanKasBerkas::deleteBerkas/$1', ['filter' => 'auth']);

// Setting
$routes->get('/setting', 'Setting::index');

// Setting -> Approver Pengajuan
$routes->get('/setting/approver-pengajuan/modal-create', 'SettingApproverPengajuan::modalCreate', ['filter' => 'auth']);
$routes->post('/setting/approver-pengajuan/datatables', 'SettingApproverPengajuan::datatables', ['filter' => 'auth']);
$routes->post('/setting/approver-pengajuan/save', 'SettingApproverPengajuan::save', ['filter' => 'auth']);
$routes->get('/setting/approver-pengajuan/(:segment)/modal-edit', 'SettingApproverPengajuan::modalEdit/$1', ['filter' => 'auth']);
$routes->post('/setting/approver-pengajuan/(:segment)/update', 'SettingApproverPengajuan::update/$1', ['filter' => 'auth']);
$routes->delete('/setting/approver-pengajuan/(:segment)/delete', 'SettingApproverPengajuan::delete/$1', ['filter' => 'auth']);

// Setting -> Approver Pencairan
$routes->get('/setting/approver-pencairan/modal-create', 'SettingApproverPencairan::modalCreate', ['filter' => 'auth']);
$routes->post('/setting/approver-pencairan/datatables', 'SettingApproverPencairan::datatables', ['filter' => 'auth']);
$routes->post('/setting/approver-pencairan/save', 'SettingApproverPencairan::save', ['filter' => 'auth']);
$routes->get('/setting/approver-pencairan/(:segment)/modal-edit', 'SettingApproverPencairan::modalEdit/$1', ['filter' => 'auth']);
$routes->post('/setting/approver-pencairan/(:segment)/update', 'SettingApproverPencairan::update/$1', ['filter' => 'auth']);
$routes->delete('/setting/approver-pencairan/(:segment)/delete', 'SettingApproverPencairan::delete/$1', ['filter' => 'auth']);

$routes->get('/move-group/(:segment)', 'Auth::moveGroup/$1', ['filter' => 'auth']);


if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
