<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="card collapsed-card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter mr-1"></i>Filter</h3>
        <div class="card-tools">
            <button class="btn btn-tool">
                <i class="fas fa-sync mr-1"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Fakultas</label>
                    <select class="form-control" id="fakultas">
                        <option value="">Semua</option>
                    </select>
                    <div class="invalid-feedback "></div>
                </div>
            </div>    
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Program Studi</label>
                    <select class="form-control" id="fakultas">
                        <option value="">Semua</option>
                    </select>
                    <div class="invalid-feedback "></div>
                </div>
            </div>    
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Angkatan</label>
                    <select class="form-control" id="fakultas">
                        <option value="">Semua</option>
                    </select>
                    <div class="invalid-feedback "></div>
                </div>
            </div>    
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Status dosen</label>
                    <select class="form-control" id="fakultas">
                        <option value="">Semua</option>
                    </select>
                    <div class="invalid-feedback "></div>
                </div>
            </div>    
    </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Dosen</h3>
        <div class="card-tools">
            <ul class="nav nav-fill">
                <li class="nav-item">
                    <button
                        class="btn btn-success btn-sm rounded-pill px-3"
                        id="btnTambah"
                        data-url="<?= base_url('dosen/create') ?>"
                        data-method="post"
                        data-btn="<i class='fas fa-plus mr-1'></i>Tambah Data"
                        onclick="return movePage('btnTambah', true)">
                        <i class="fas fa-plus mr-1"></i>Tambah Data
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <table
            class="table table-bordered table-hover table-sm"
            id="table-dosen"
            data-url="<?= base_url('dosen/datatables') ?>"
            data-method="post">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Aksi</th>
                    <th>NIDN</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Program Studi</th>
                    <th>Fakultas</th>
                    <th>Otoritas</th>
                    <th>Status</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>

<script src="<?= base_url('assets/js/user-dosen/index.js?v='.time()) ?>",script>

<?= $this->endSection() ?>