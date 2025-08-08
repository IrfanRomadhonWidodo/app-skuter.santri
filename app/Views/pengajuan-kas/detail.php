<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>
<link
    rel="stylesheet"
    href="<?= base_url('assets/css/pengajuan-kas.css?v=' . time()) ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?= $this->include('pengajuan-kas/assets/step') ?>
<?= $this->include('pengajuan-kas/assets/nav-tabs') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Pembayaran</h3>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-4 col-md-2">Judul Pengajuan</dt>
            <dd class="col-8 col-md-10">:
                <?= $pengajuan['kas_judul'] ?></dd>
            <dt class="col-4 col-md-2">Nomor Pengajuan</dt>
            <dd class="col-8 col-md-10">:
                <?= $pengajuan['kas_nomor_pengajuan'] ?></dd>
            <dt class="col-4 col-md-2">Unit Kerja</dt>
            <dd class="col-8 col-md-10">:
                <?= $pengajuan['unkj_nama'] ?></dd>
            <dt class="col-4 col-md-2">Keterangan</dt>
            <dd class="col-8 col-md-10">:
                <?= $pengajuan['kas_keterangan'] ?></dd>
            <dt class="col-4 col-md-2">Nominal</dt>
            <dd class="col-8 col-md-10">:
                <?= $pengajuan['kas_nominal'] ?></dd>
            <dt class="col-4 col-md-2">Tanggal Submit</dt>
            <dd class="col-8 col-md-10">:
                <?= $pengajuan['kas_submited_date'] ?></dd>
            <dt class="col-4 col-md-2">Status</dt>
            <dd class="col-8 col-md-10">:
                <?= statusKas($pengajuan['kas_status']) ?></dd>

        </dl>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>

<script
    src="<?= base_url('assets/js/pengajuan-kas/index.js?v=' . time()) ?>"
    ,script=",script">
    <?= $this->endSection() ?>