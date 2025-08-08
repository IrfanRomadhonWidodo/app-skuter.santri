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
            src="/assets/files/logo-santri-unu-purwokerto.png"
            alt="Logo Santri UNU Purwokerto"
            class="brand-image shadow-none">
    </a>

    <!-- Sidebar -->

    <div class="sidebar">

        <nav>

            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= getenv('url.sso') ?>" class="nav-link">
                        <i class="fas fa-home nav-icon"></i>
                        <p>
                            Beranda
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url() ?>" class="nav-link  <?= $uri_1 == null ? 'active' : '' ?>">
                        <i class="fas fa-th nav-icon"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <!-- pemasukan (SPP) -->
                <li class="nav-header">Menu Keuangan</li>
                <li class="nav-item <?= $uri_1 == '' ? 'menu-open' : '' ?> ">
                    <a href="#" class="nav-link">
                        <i class="fas fa-dumpster nav-icon"></i>
                        <p>
                            Pemasukan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('pembayaran-spp') ?>" class="nav-link <?= $uri_1 == 'pembayaran-spp' && $uri_2 == null ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pembayaran SPP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('view-kip') ?>" class="nav-link <?= $uri_1 == 'view-kip' && $uri_2 == '' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>KIP</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Pengajuan -->
                <li class="nav-item">
                    <a href="<?= base_url('pengajuan-kas') ?>" class="nav-link  <?= $uri->getSegment(1) == 'pengajuan-kas' ? 'active' : '' ?>">
                        <i class="fas fa-book-open nav-icon"></i>
                        <p>
                            Pengajuan
                        </p>
                    </a>
                </li>

                <!-- Pengeluaran -->
                <li class="nav-item <?= $uri_1 == '' ? 'menu-open' : '' ?> ">
                    <a href="#" class="nav-link">
                        <i class="fas fa-school nav-icon"></i>
                        <p>
                            Pengeluaran
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('kas-besar') ?>" class="nav-link <?= $uri_1 == 'kas-besar' && $uri_2 == null ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kas Besar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('kas-kecil') ?>" class="nav-link <?= $uri_1 == 'kas-kecil' && $uri_2 == '' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kas Kecil</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Penggajian -->
                <li class="nav-item <?= $uri_1 == '' ? 'menu-open' : '' ?> ">
                    <a href="#" class="nav-link">
                        <i class="fas fa-server nav-icon"></i>
                        <p>
                            Penggajian
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('penggajian-dosen') ?>" class="nav-link <?= $uri_1 == 'penggajian-dosen' && $uri_2 == null ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dosen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penggajian-staff') ?>" class="nav-link <?= $uri_1 == 'penggajian-staff' && $uri_2 == '' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Staff/Karyawan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Laporan -->
                <li class="nav-item <?= $uri_1 == '' ? 'menu-open' : '' ?> ">
                    <a href="#" class="nav-link">
                        <i class="fas fa-server nav-icon"></i>
                        <p>
                            Laporan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('laporan-laba-rugi') ?>" class="nav-link <?= $uri_1 == 'laporan-laba-rugi' && $uri_2 == null ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Laporan Laba Rugi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penggajian-staff') ?>" class="nav-link <?= $uri_1 == 'penggajian-staff' && $uri_2 == '' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Laporan Neraca</p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('laporan-aktiva') ?>" class="nav-link <?= $uri_1 == 'laporan-aktiva' && $uri_2 == null ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Aktiva</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('laporan-pasiva') ?>" class="nav-link <?= $uri_1 == 'laporan-pasiva' && $uri_2 == '' ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pasiva</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- User -->
                <li class="nav-item">
                    <a href="<?= base_url('pengajuan-kas') ?>" class="nav-link  <?= $uri->getSegment(1) == 'pengajuan-kas' ? 'active' : '' ?>">
                        <i class="fas fa-book-open nav-icon"></i>
                        <p>
                            User
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('setting') ?>"
                        class="nav-link  <?= $uri_1 == 'setting' ? 'active' : '' ?>">
                        <i class="fas fa-user-ninja nav-icon"></i>
                        <p>
                            Setting
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->

</aside>