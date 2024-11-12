<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Approver Pengajuan</h3>
        <div class="card-tools">
            <ul class="nav nav-fill">
                <li class="nav-item">
                    <button
                        class="btn btn-success btn-sm rounded-pill px-3"
                        id="btnTambahApproverPengajuan"
                        data-url="<?= base_url('setting/approver-pengajuan/modal-create') ?>"
                        data-method="get"
                        data-btn="<i class='fas fa-plus mr-1'></i>Tambah Data"
                        onclick="return viewModal('btnTambahApproverPengajuan', true)">
                        <i class="fas fa-plus mr-1"></i>Tambah Data
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <table
            class="table table-bordered table-hover table-sm w-100"
            id="table-approver-pengajuan"
            data-url="<?= base_url('setting/approver-pengajuan/datatables') ?>"
            data-method="post">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Aksi</th>
                    <th>Group / Otoritas</th>
                    <th>Level</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Approver Pencairan</h3>
        <div class="card-tools">
            <ul class="nav nav-fill">
                <li class="nav-item">
                    <button
                        class="btn btn-success btn-sm rounded-pill px-3"
                        id="btnTambahApproverPencairan"
                        data-url="<?= base_url('setting/approver-pencairan/modal-create') ?>"
                        data-method="get"
                        data-btn="<i class='fas fa-plus mr-1'></i>Tambah Data"
                        onclick="return viewModal('btnTambahApproverPencairan', true)">
                        <i class="fas fa-plus mr-1"></i>Tambah Data
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <table
            class="table table-bordered table-hover table-sm w-100"
            id="table-approver-pencairan"
            data-url="<?= base_url('setting/approver-pencairan/datatables') ?>"
            data-method="post">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Aksi</th>
                    <th>Group / Otoritas</th>
                    <th>Level</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/setting/approver-pengajuan.js?v=' . time()) ?>"></script>
<script src="<?= base_url('assets/js/setting/approver-pencairan.js?v=' . time()) ?>"></script>
<?= $this->endSection() ?>