<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Unit Kerja</h3>
        <div class="card-tools">
            <ul class="nav nav-fill">
                <li class="nav-item">
                    <button
                        class="btn btn-success btn-sm rounded-pill px-3"
                        id="btnTambah"
                        data-url="<?= base_url('unit-kerja/modal-create') ?>"
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
            id="table-unit-kerja"
            data-url="<?= base_url('unit-kerja/datatables') ?>"
            data-method="post">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Aksi</th>
                    <th>Kode Unit</th>
                    <th>Nama Unit</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/unit-kerja/index.js?v=' . time()) ?>"></script>
<?= $this->endSection() ?>