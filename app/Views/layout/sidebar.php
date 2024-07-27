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
            class="brand-image shadow-none" >
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
                <li class="nav-item">
                    <a
                        href="<?= base_url('taskboard') ?>"
                        class="nav-link  <?= $uri_1 == 'taskboard' ? 'active' : '' ?>">
                        <i class="fas fa-tasks nav-icon"></i>
                        <p>
                            Taskboard
                        </p>
                    </a>
                </li>
                <li class="nav-header">KAS</li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('pengajuan-kas') ?>"
                        class="nav-link  <?= $uri_1 == 'pengajuan-kas' ? 'active' : '' ?>">
                        <i class="fas fa-mail-bulk nav-icon"></i>
                        <p>
                            Pengajuan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('laporan') ?>"
                        class="nav-link  <?= $uri_1 == 'laporan' ? 'active' : '' ?>">
                        <i class="fas fa-chart-line nav-icon"></i>
                        <p>
                            Laporan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="<?= base_url('approver-setting') ?>"
                        class="nav-link  <?= $uri_1 == 'approver-setting' ? 'active' : '' ?>">
                        <i class="fas fa-user-ninja nav-icon"></i>
                        <p>
                            Approver Setting
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>