<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-name" id="csrf-name" content="<?= csrf_token() ?>">
        <meta name="csrf-value" id="csrf-value" content="<?= csrf_hash() ?>">
        <title></title>
        <link rel="icon" href="">
        <!-- Google Font: Source Sans Pro -->
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('assets/vendor/adminlte/css/adminlte.min.css') ?>">
        <!-- Custom style -->
        <link rel="stylesheet" href="<?= base_url('assets/css/style.css?v='.time()) ?>">
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link
            href="<?= base_url('assets/vendor/DataTables/datatables.min.css') ?>"
            rel="stylesheet"/>
        <!-- jQuery -->
        <script src="/assets/js/jQuery.js"></script>
        <link
            href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
            rel="stylesheet"/>
        <script
            src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <?= $this->renderSection('css') ?>

    </head>
    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
        <div class="wrapper">
            <!-- Main Sidebar Container -->
            <?= $this->include('layout/sidebar') ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper border-0">
                <nav class="navbar navbar-expand navbar-dark shadow-sm">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a data-widget="pushmenu" href="#" role="button">
                                <i class="fas fa-align-left ml-3"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item navbar-notifikasi mr-3">
                            <a class="nav-link position-relative" href="<?= base_url('notifikasi') ?>">
                                <i class="fas fa-bell" style="font-size: 30px"></i>
                                <i class="fas fa-circle" style="color: #FC6C6C;"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <div class="row">
                                <div class="col text-right">
                                    <a
                                        href="<?= base_url('profil') ?>"
                                        class="font-weight-bolder text-nowrap profil-name"><?= (session()->usr_nama) ?? 'Nama' ?></a><br>
                                    <a href="<?= base_url('logout') ?>">Logout</a>
                                </div>
                                <div class="col">
                                    <a class="navbar-profile" role="button">
                                        <img src="/assets/img/images.png" class="rounded-circle" alt="">
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- Content Header (Page header) -->
                <div class="content-header border-0">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0"><?= $page ?></h1>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6">
                                <?= $breadcrumbs ?>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <?= $this->renderSection('content') ?>
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
           <div class="viewmodal"></div>
            <!-- Main Footer -->
            <footer class="main-footer border-0">
                <strong>Copyright &copy;
                    <?= date('Y') ?>
                </strong>
                All rights reserved.
                <div class="float-right d-none d-sm-inline-block">
                    <b>Version</b>
                    3.2.0
                </div>
            </footer>
        </div>
        <div id="base_url" data-base-url="<?= base_url() ?>"></div>
        <!-- ./wrapper -->
        <!-- REQUIRED SCRIPTS -->
        <script src="<?= base_url('assets/js/script.js?v='.time()) ?>"></script>
        <!-- Bootstrap -->
        <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
        <!-- AdminLTE App -->
        <script src="<?= base_url('assets/vendor/adminlte/js/adminlte.js') ?>"></script>
        <script src="<?= base_url() ?>/assets/vendor/DataTables/datatables.min.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

        <?= $this->renderSection('js') ?>
        <script>
            //   $('[data-toggle="tooltip"]').tooltip()
            $('#btn-logout').on('click', function () {
                Swal
                    .fire({
                        text: "Apakah anda yakin akan keluar ?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#001F3F',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Batal',
                        confirmButtonText: 'Ya, Keluar'
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            let btn = $(this);
                            $.ajax({
                                url: btn.data('url'),
                                type: btn.data('method'),
                                beforeSend: function () {
                                    Swal.fire({
                                        title: 'Memproses...',
                                        html: 'Harap Tunggu...',
                                        allowEscapeKey: false,
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading()
                                        }
                                    });
                                },
                                success: function (result) {
                                    // console.log(result);
                                    resp = JSON.parse(result);
                                    if (resp['success']) {
                                        Swal
                                            .fire(
                                                {icon: 'success', title: 'Berhasil', text: resp['success']['msg'], timer: 1800, showConfirmButton: false}
                                            )
                                            .then((result) => {
                                                /* Read more about isConfirmed, isDenied below */
                                                if (result.isConfirmed) {
                                                    window.location.href = resp['success']['redirect'];
                                                } else {
                                                    window.location.href = resp['success']['redirect'];
                                                }
                                            });
                                    }
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                                }
                            });
                        }
                    });
            })
        </script>
    </body>
</html>