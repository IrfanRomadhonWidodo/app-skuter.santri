<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>
<link
    rel="stylesheet"
    href="<?= base_url('assets/css/pengajuan-kas.css?v='.time()) ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?= $this->include('pengajuan-kas/assets/step') ?>
<?= $this->include('pengajuan-kas/assets/nav-tabs') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Persetujuan</h3>
    </div>
    <div class="card-body">
        
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>

<script
    src="<?= base_url('assets/js/pengajuan-kas/index.js?v='.time()) ?>"
    ,script=",script">

    <?= $this->endSection() ?>