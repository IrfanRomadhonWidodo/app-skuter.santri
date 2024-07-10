<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Mata Kuliah</h3>
        <div class="card-tools">
            <ul class="nav nav-fill">
                <li class="nav-item">
                    <button class="btn btn-success btn-sm rounded-pill px-3" id="btnTambah" data-url="<?= base_url('mata-kuliah/modal-create') ?>" data-method="post" data-btn="<i class='fas fa-plus mr-1'></i>Tambah Data" onclick="return viewModal('btnTambah', true)">
                        <i class="fas fa-plus mr-1"></i>Tambah Data
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover table-sm w-100" id="table-mataKuliah" data-url="<?= base_url('mata-kuliah/datatables') ?>" data-method="post">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Aksi</th>
                    <th>Kode Mata Kuliah</th>
                    <th>Mata Kuliah</th>
                    <th>Mata Kuliah (English)</th>
                    <th>SKS</th>
                    <!-- <th>Program Studi</th> -->
                    <!-- <th>Status</th> -->
                </tr>

            </thead>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/mata-kuliah/index.js?v=' . time()) ?>"></script>
<?= $this->endSection() ?>