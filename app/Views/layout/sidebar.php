<?php $uri = service('uri');

$uri_1 = false;
$uri_2 = false;
$uri_3 = false;
$uri_4 = false;

$uri_1 = $uri->getSegment(1);

if ($uri_1) {
    $uri_2 = $uri->getSegment(2);
}
if ($uri_2 != false) {
    $uri_3 = $uri->getSegment(3);
}

if ($uri_3 != false) {
    $uri_4 = $uri->getSegment(4);
}



?>

<aside class="main-sidebar sidebar-primary">

    <!-- Brand Logo -->

    <a href="<?= base_url() ?>" class="brand-link text-center">
        <img
            src="<?= base_url('file?file=/master/logo-santri-unu-purwokerto.png') ?>"
            alt="Logo Santri UNU Purwokerto"
            class="brand-image shadow-none">
    </a>

    <!-- Sidebar -->

    <div class="sidebar">

        <nav>

            <ul
                class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="<?= getenv('url.sso') ?>" class="nav-link">
                        <i class="fas fa-home nav-icon"></i>
                        <p>
                            Beranda
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url() ?>"
                        class="nav-link  <?= $uri_1 == null ? 'active' : '' ?>">
                        <i class="fas fa-th nav-icon"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-header">Akademik</li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('periode-akademik') ?>"
                        class="nav-link  <?= $uri_1 == 'periode-akademik' ? 'active' : '' ?>">
                        <i class="fas fa-calendar-alt nav-icon"></i>
                        <p>
                            Periode Akademik
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('fakultas') ?>"
                        class="nav-link  <?= $uri_1 == 'fakultas' ? 'active' : '' ?>">
                        <i class="fas fa-university nav-icon"></i>
                        <p>
                            Fakultas & Studi
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="<?= base_url('mata-kuliah') ?>"
                        class="nav-link  <?= $uri->getSegment(1) == 'mata-kuliah' ? 'active' : '' ?>">
                        <i class="fas fa-book-open nav-icon"></i>
                        <p>
                            Mata Kuliah
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('set-nilai') ?>"
                        class="nav-link  <?= $uri->getSegment(1) == 'set-nilai' ? 'active' : '' ?>">
                        <i class="fas fa-book nav-icon"></i>
                        <p>
                            Set Nilai
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('ruang-kelas') ?>"
                        class="nav-link  <?= $uri->getSegment(1) == 'ruang-kelas' ? 'active' : '' ?>">
                        <i class="fab fa-discourse nav-icon"></i>
                        <p>
                            Kelas
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url() ?>"
                        class="nav-link  <?= $uri_1 == 'user' ? 'active' : '' ?>">
                        <i class="fab fa-joomla nav-icon"></i>
                        <p>
                            Kelas Mata Kuliah
                        </p>
                    </a>
                </li>
                <li class="nav-header">Pengguna & Otorisasi</li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('mahasiswa') ?>"
                        class="nav-link  <?= $uri_1 == 'mahasiswa' ? 'active' : '' ?>">
                        <i class="fas fa-user-graduate nav-icon"></i>
                        <p>
                            Mahasiswa
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('dosen') ?>"
                        class="nav-link  <?= $uri_1 == 'dosen' ? 'active' : '' ?>">
                        <i class="fas fa-chalkboard-teacher nav-icon"></i>
                        <p>
                            Dosen
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('pegawai') ?>"
                        class="nav-link  <?= $uri_1 == 'pegawai' ? 'active' : '' ?>">
                        <i class="fas fa-user-tie nav-icon"></i>
                        <p>
                            Pegawai
                        </p>
                    </a>
                </li>
                <li class="nav-item <?= $uri_1 == 'permission' ? 'menu-open' : '' ?> ">
                    <a href="#" class="nav-link">
                        <i class="fas fa-low-vision nav-icon"></i>
                        <p>
                            Hak Akses
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a
                                href="<?= base_url('permission') ?>"
                                class="nav-link <?= $uri_1 == 'permission' && $uri_2 == null ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Permission</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                href="<?= base_url('permission/group') ?>"
                                class="nav-link <?= $uri_1 == 'permission' && $uri_2 == 'group' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Group</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                href="<?= base_url('permission/user-permission') ?>"
                                class="nav-link <?= $uri_1 == 'permission' && $uri_2 == 'user-permission' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User Permission</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">Kepegawaian</li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('unit-kerja') ?>"
                        class="nav-link  <?= $uri_1 == 'unit-kerja' ? 'active' : '' ?>">
                        <i class="fas fa-cube nav-icon"></i>
                        <p>
                            Unit Kerja
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('jabatan') ?>"
                        class="nav-link  <?= $uri_1 == 'jabatan' ? 'active' : '' ?>">
                        <i class="fas fa-user-tag nav-icon"></i>
                        <p>
                            Jabatan
                        </p>
                    </a>
                </li>
                <li class="nav-header">Konfigurasi Sistem</li>
                <li class="nav-item">
                    <a
                        href="<?= base_url() ?>"
                        class="nav-link  <?= $uri_1 == 'user' ? 'active' : '' ?>">
                        <i class="fas fa-money-check-alt nav-icon"></i>
                        <p>
                            Tagihan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('terjemahan') ?>" class="nav-link  <?= $uri_1 == 'terjemahan' ? 'active' : '' ?>">
                        <i class="fas fa-language nav-icon"></i>
                        <p>
                            Bahasa
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url() ?>"
                        class="nav-link  <?= $uri_1 == 'user' ? 'active' : '' ?>">
                        <i class="fab fa-medapps nav-icon"></i>
                        <p>
                            Aplikasi
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('auth-slider') ?>" class="nav-link  <?= $uri_1 == 'auth-slider' ? 'active' : '' ?>">
                        <i class="far fa-images nav-icon"></i>
                        <p>
                            Auth Slider
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>