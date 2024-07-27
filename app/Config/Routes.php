<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/file', 'File::file_shared');

// CSRF
$routes->post('/reload-csrf', 'CSRFControl::reload');

// Pengajuan KAS
$routes->get('/pengajuan-kas', 'PengajuanKas::index', ['filter' => 'auth']);
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