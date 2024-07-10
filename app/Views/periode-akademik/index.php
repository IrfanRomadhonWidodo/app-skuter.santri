<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Periode Akademik</h3>
        <div class="card-tools">
            <ul class="nav nav-fill">
                <li class="nav-item">
                    <button class="btn btn-success btn-sm rounded-pill px-3" id="btnTambah" data-url="<?= base_url('periode-akademik/modal-create') ?>" data-method="post" data-btn="<i class='fas fa-plus mr-1'></i>Tambah Data" onclick="return viewModal('btnTambah', true)">
                        <i class="fas fa-plus mr-1"></i>Tambah Data
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover table-sm w-100" id="table-periodeAkademik" data-url="<?= base_url('periode-akademik/datatables') ?>" data-method="post">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Aksi</th>
                    <th>Tahun Akademik</th>
                    <th>Semester</th>
                    <th>Masa Aktif</th>
                    <th>Status</th>
                    <!-- <th>Program Studi</th> -->
                    <!-- <th>Status</th> -->
                </tr>

            </thead>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/periode-akademik/index.js?v=' . time()) ?>"></script>
<?= $this->endSection() ?>