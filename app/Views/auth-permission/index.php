<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Permission</h3>
        <div class="card-tools">
            <ul class="nav nav-fill">
                <li class="nav-item">
                    <button
                        class="btn btn-success btn-sm rounded-pill px-3"
                        id="btnTambah"
                        data-url="<?= base_url('permission/modal-create') ?>"
                        data-method="post"
                        data-btn="<i class='fas fa-plus mr-1'></i>Tambah Data"
                        onclick="return viewModal('btnTambah', true)">
                        <i class="fas fa-plus mr-1"></i>Tambah Data
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <table
            class="table table-bordered table-hover table-sm w-100"
            id="table-permission"
            data-url="<?= base_url('permission/datatables') ?>"
            data-method="post">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Aksi</th>
                    <th>Aplikasi</th>
                    <th>Permission</th>
                    <th>Label</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/permission/index.js?v=' . time()) ?>"></script>
<?= $this->endSection() ?>