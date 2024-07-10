<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Fakultas</h3>
        <div class="card-tools">
            <ul class="nav nav-fill">
                <li class="nav-item">
                    <button
                        class="btn btn-success btn-sm rounded-pill px-3"
                        id="btnTambahFakultas"
                        data-url="<?= base_url('fakultas/modal-create') ?>"
                        data-method="post"
                        data-btn="<i class='fas fa-plus mr-1'></i>Tambah Data"
                        onclick="return viewModal('btnTambahFakultas', true)">
                        <i class="fas fa-plus mr-1"></i>Tambah Data
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <table
            class="table table-bordered table-hover table-sm w-100"
            id="table-fakultas"
            data-url="<?= base_url('fakultas/datatables') ?>"
            data-method="post">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Aksi</th>
                    <th>Kode Fakultas</th>
                    <th>Nama Fakultas</th>
                    <!-- <th>Program Studi</th> -->
                    <!-- <th>Status</th> -->
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Program Studi</h3>
        <div class="card-tools">
            <ul class="nav nav-fill">
                <li class="nav-item">
                    <button
                        class="btn btn-success btn-sm rounded-pill px-3"
                        id="btnTambahProdi"
                        data-url="<?= base_url('program-studi/modal-create') ?>"
                        data-method="post"
                        data-btn="<i class='fas fa-plus mr-1'></i>Tambah Data"
                        onclick="return viewModal('btnTambahProdi', true)">
                        <i class="fas fa-plus mr-1"></i>Tambah Data
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <table
            class="table table-bordered table-hover table-sm w-100"
            id="table-program-studi"
            data-url="<?= base_url('program-studi/datatables') ?>"
            data-method="post">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Aksi</th>
                    <th>Fakultas</th>
                    <th>Kode Program Studi</th>
                    <th>Nama Program Studi</th>
                    <!-- <th>Program Studi</th> -->
                    <!-- <th>Status</th> -->
                </tr>
            </thead>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/fakultas/index.js?v='.time()) ?>"></script>
<script src="<?= base_url('assets/js/program-studi/index.js?v='.time()) ?>"></script>
<?= $this->endSection() ?>