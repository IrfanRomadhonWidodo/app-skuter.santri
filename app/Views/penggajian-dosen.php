<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>

<style>
    .card-alert {
        border-radius: 10px;
        background-color: #029a511f;
        border: none;
        color: var(--secondary-color);
        overflow: hidden;
    }

    .card-alert img {
        width: 120px;
        position: absolute;
        left: 50%;
        top: -37px;
    }
</style>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="card card-alert">
    <div class="card-body py-4">
        <div class="row">
            <div class="col-md-8">
                <h3 class="font-weight-bolder">INI HALAMAN PENGGAJIAN DOSEN</h3>
            </div>
            <div class="col-md-4 position-relative"><img src="/assets/files/penggajian.png" alt=""></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>

<?= $this->endSection() ?>