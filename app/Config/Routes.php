<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/file', 'File::file_shared');

// CSRF
$routes->post('/reload-csrf', 'CSRFControl::reload');

// User Mahasiswa
$routes->get('/mahasiswa', 'UserMahasiswa::index');
$routes->get('/mahasiswa/create', 'UserMahasiswa::create');
$routes->get('/mahasiswa/(:segment)/e', 'UserMahasiswa::edit/$1');
// User Mahasiswa -> AJAX
$routes->post('/mahasiswa/datatables', 'UserMahasiswa::datatables');
$routes->post('/mahasiswa/modal-create', 'UserMahasiswa::modalCreate');
$routes->post('/mahasiswa/save', 'UserMahasiswa::save');
$routes->post('/mahasiswa/modal-edit/(:segment)', 'UserMahasiswa::modalEdit/$1');
$routes->post('/mahasiswa/update/(:segment)', 'UserMahasiswa::update/$1');
$routes->delete('/mahasiswa/delete/(:segment)', 'UserMahasiswa::delete/$1');

// User Dosen
$routes->get('/dosen', 'UserDosen::index');
$routes->get('/dosen/create', 'UserDosen::create');
// User Dosen -> AJAX
$routes->post('/dosen/datatables', 'UserDosen::datatables');
$routes->post('/dosen/save', 'UserDosen::save');
$routes->post('/dosen/modal-edit/(:segment)', 'UserDosen::modalEdit/$1');
$routes->post('/dosen/update/(:segment)', 'UserDosen::update/$1');
$routes->delete('/dosen/delete/(:segment)', 'UserDosen::delete/$1');

// User Pegawai
$routes->get('/pegawai', 'UserPegawai::index');
$routes->get('/pegawai/create', 'UserPegawai::create');
// User Pegawai -> AJAX
$routes->post('/pegawai/datatables', 'UserPegawai::datatables');
$routes->post('/pegawai/save', 'UserPegawai::save');
$routes->post('/pegawai/modal-edit/(:segment)', 'UserPegawai::modalEdit/$1');
$routes->post('/pegawai/update/(:segment)', 'UserPegawai::update/$1');
$routes->delete('/pegawai/delete/(:segment)', 'UserPegawai::delete/$1');

// Periode Akademik
$routes->get('/periode-akademik', 'PeriodeAkademik::index');
// Periode Akademik
$routes->post('/periode-akademik/datatables', 'PeriodeAkademik::datatables');
$routes->post('/periode-akademik/modal-create', 'PeriodeAkademik::modalCreate');
$routes->post('/periode-akademik/save', 'PeriodeAkademik::save');
$routes->post('/periode-akademik/(:segment)/modal-edit', 'PeriodeAkademik::modalEdit/$1');
$routes->post('/periode-akademik/(:segment)/update', 'PeriodeAkademik::update/$1');
$routes->post('/periode-akademik/(:segment)/change-status', 'PeriodeAkademik::changeStatus/$1');
$routes->delete('/periode-akademik/(:segment)/delete', 'PeriodeAkademik::delete/$1');

// Fakultas
$routes->get('/fakultas', 'Fakultas::index');
// Fakultas
$routes->post('/fakultas/datatables', 'Fakultas::datatables');
$routes->post('/fakultas/modal-create', 'Fakultas::modalCreate');
$routes->post('/fakultas/save', 'Fakultas::save');
$routes->post('/fakultas/(:segment)/modal-edit', 'Fakultas::modalEdit/$1');
$routes->post('/fakultas/(:segment)/update', 'Fakultas::update/$1');
$routes->delete('/fakultas/(:segment)/delete', 'Fakultas::delete/$1');

// Program Studi
$routes->post('/program-studi/datatables', 'ProgramStudi::datatables');
$routes->post('/program-studi/modal-create', 'ProgramStudi::modalCreate');
$routes->post('/program-studi/save', 'ProgramStudi::save');
$routes->post('/program-studi/(:segment)/modal-edit', 'ProgramStudi::modalEdit/$1');
$routes->post('/program-studi/(:segment)/update', 'ProgramStudi::update/$1');
$routes->delete('/program-studi/(:segment)/delete', 'ProgramStudi::delete/$1');

// Mata Kuliah
$routes->get('/mata-kuliah', 'MataKuliah::index');
// Mata Kuliah
$routes->post('/mata-kuliah/datatables', 'MataKuliah::datatables');
$routes->post('/mata-kuliah/modal-create', 'MataKuliah::modalCreate');
$routes->post('/mata-kuliah/save', 'MataKuliah::save');
$routes->post('/mata-kuliah/(:segment)/modal-edit', 'MataKuliah::modalEdit/$1');
$routes->post('/mata-kuliah/(:segment)/update', 'MataKuliah::update/$1');
$routes->delete('/mata-kuliah/(:segment)/delete', 'MataKuliah::delete/$1');

// Ruang Kelas
$routes->get('/ruang-kelas', 'RuangKelas::index');
// Ruang Kelas
$routes->post('/ruang-kelas/datatables', 'RuangKelas::datatables');
$routes->post('/ruang-kelas/modal-create', 'RuangKelas::modalCreate');
$routes->post('/ruang-kelas/save', 'RuangKelas::save');
$routes->post('/ruang-kelas/(:segment)/modal-edit', 'RuangKelas::modalEdit/$1');
$routes->post('/ruang-kelas/(:segment)/update', 'RuangKelas::update/$1');
$routes->delete('/ruang-kelas/(:segment)/delete', 'RuangKelas::delete/$1');


// Set Nilai
$routes->get('/set-nilai', 'SetNilai::index');
// Set Nilai
$routes->post('/set-nilai/datatables', 'SetNilai::datatables');
$routes->post('/set-nilai/modal-create', 'SetNilai::modalCreate');
$routes->post('/set-nilai/save', 'SetNilai::save');
$routes->post('/set-nilai/(:segment)/modal-edit', 'SetNilai::modalEdit/$1');
$routes->post('/set-nilai/(:segment)/update', 'SetNilai::update/$1');
$routes->delete('/set-nilai/(:segment)/delete', 'SetNilai::delete/$1');

// Unit Kerja
$routes->get('/unit-kerja', 'UnitKerja::index');
// Unit Kerja
$routes->post('/unit-kerja/datatables', 'UnitKerja::datatables');
$routes->post('/unit-kerja/modal-create', 'UnitKerja::modalCreate');
$routes->post('/unit-kerja/save', 'UnitKerja::save');
$routes->post('/unit-kerja/(:segment)/modal-edit', 'UnitKerja::modalEdit/$1');
$routes->post('/unit-kerja/(:segment)/update', 'UnitKerja::update/$1');
$routes->delete('/unit-kerja/(:segment)/delete', 'UnitKerja::delete/$1');

// Jabatan
$routes->get('/jabatan', 'Jabatan::index');
// Jabatan
$routes->post('/jabatan/datatables', 'Jabatan::datatables');
$routes->post('/jabatan/modal-create', 'Jabatan::modalCreate');
$routes->post('/jabatan/save', 'Jabatan::save');
$routes->post('/jabatan/(:segment)/modal-edit', 'Jabatan::modalEdit/$1');
$routes->post('/jabatan/(:segment)/update', 'Jabatan::update/$1');
$routes->delete('/jabatan/(:segment)/delete', 'Jabatan::delete/$1');


// Terjemahan
$routes->get('/terjemahan', 'Terjemahan::index');
// Terjemahan
$routes->post('/terjemahan/datatables', 'Terjemahan::datatables');
$routes->post('/terjemahan/modal-create', 'Terjemahan::modalCreate');

// Auth Slider
$routes->get('/auth-slider', 'ConfigAuthSlider::index');
// Auth Slider
$routes->post('/auth-slider/datatables', 'ConfigAuthSlider::datatables');
$routes->post('/auth-slider/modal-create', 'ConfigAuthSlider::modalCreate');
$routes->post('/auth-slider/save', 'ConfigAuthSlider::save');
$routes->post('/auth-slider/(:segment)/modal-edit', 'ConfigAuthSlider::modalEdit/$1');
$routes->post('/auth-slider/(:segment)/update', 'ConfigAuthSlider::update/$1');
$routes->post('/auth-slider/(:segment)/change-status', 'ConfigAuthSlider::changeStatus/$1');

// Permission
$routes->get('/permission', 'AuthPermission::index');
// Permission -> ajax
$routes->post('/permission/datatables', 'AuthPermission::datatables');
$routes->post('/permission/modal-create', 'AuthPermission::modalCreate');
$routes->post('/permission/save', 'AuthPermission::save');
$routes->post('/permission/(:segment)/modal-edit', 'AuthPermission::modalEdit/$1');
$routes->post('/permission/(:segment)/update', 'AuthPermission::update/$1');
