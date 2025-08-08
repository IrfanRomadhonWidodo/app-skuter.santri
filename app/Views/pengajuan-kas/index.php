<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="card collapsed-card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter mr-1"></i>Filter
        </h3>
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
                    <label for="">Tahun Anggaran</label>
                    <select class="form-control" id="fakultas">
                        <option value="">Semua</option>
                    </select>
                    <div class="invalid-feedback "></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Status Pengajuan</label>
                    <select class="form-control" id="fakultas">
                        <option value="">Semua</option>
                        <option value="">Draft</option>
                        <option value="">Diterima</option>
                        <option value="">Ditolak</option>
                    </select>
                    <div class="invalid-feedback "></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Unit</label>
                    <select class="form-control" id="fakultas">
                        <option value="">Semua</option>
                        <option value="">Draft</option>
                        <option value="">Diterima</option>
                        <option value="">Ditolak</option>
                    </select>
                    <div class="invalid-feedback "></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Pengajuan KAS</h3>
        <div class="card-tools">
            <ul class="nav nav-fill">
                <li class="nav-item">
                    <button
                        class="btn btn-success btn-sm rounded-pill px-3"
                        id="btnTambah"
                        data-url="<?= base_url('pengajuan-kas/create-draft') ?>"
                        data-method="post"
                        data-btn="<i class='fas fa-plus mr-1'></i>Tambah Data">
                        <i class="fas fa-plus mr-1"></i>Tambah Data
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <table
            class="table table-bordered table-hover table-sm"
            id="table-pengajuan-kas"
            data-url="<?= base_url('pembayaran-spp/datatables') ?>"
            data-method="post">
            <thead class="thead-dark">
                <tr>
                    <th class="text-nowrap">#</th>
                    <th class="text-nowrap">ID Mahasiswa</th>
                    <th class="text-nowrap">Nama Mahasiswa</th>
                    <th class="text-nowrap">Prodi</th>
                    <th class="text-nowrap">Semester</th>
                    <th class="text-nowrap">Status</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>

<script src="<?= base_url('assets/js/pengajuan-kas/index.js?v=' . time()) ?>" ,script>
    <?= $this->endSection() ?>