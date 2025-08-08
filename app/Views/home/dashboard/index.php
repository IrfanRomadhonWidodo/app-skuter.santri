<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<header class="home container-fluid">
    <div class="owl-carousel owl-theme owl-loaded">
        <div class="owl-stage-outer">
            <div class="owl-stage">
                <div class="owl-item">
                    <img
                        class="owl-lazy"
                        data-src="<?= base_url('assets/img/slider-1.jpg') ?>"
                        data-src-retina=""
                        alt="">
                </div>
            </div>
        </div>
    </div>
    <div class="home-content">
        <h1>Selamat Datang di Portal Pendaftaran Mahasiswa Baru</h1>
        <h2>Universitas Nahdlatul Ulama Purwokerto</h2>
    </div>
</header>
<section id="content" class="container-fluid px-5 my-5">
    <?php


    $no = 1;
    $array_info_grafis = [
        [
            "no" => $no++,
            "title" => "Registrasi Akun",
            "description" => "Calon mahasiswa baru melakukan registrasi akun",
            "checked" => $task['usrt_registrasi'] == 1 ? true : false
        ],
        [
            "no" => $no++,
            "title" => "Upload Berkas",
            "description" => "Calon mahasiswa baru mengumpulkan berkas persyaratan ke sistem PMB UNU Purwokerto sesuai dengan yang telah ditetapkan",
            "checked" => $task['usrt_pemberkasan'] == 1 ? true : false
        ],
        [
            "no" => $no++,
            "title" => "Test Pemantapan",
            "description" => "Evaluasi untuk mengukur pemahaman dasar individu terhadap materi atau kompetensi awal.",
            "checked" => $task['usrt_test_pemantapan'] == 1 ? true : false
        ],
        [
            "no" => $no++,
            "title" => "Pembayaran",
            "description" => "Calon mahasiswa baru melakukan pembayaran biaya pendaftaran",
            "checked" => $task['usrt_pembayaran'] == 1 ? true : false
        ],
        [
            "no" => $no++,
            "title" => "Pengumuman",
            "description" => "Pengumuman hasil seleksi penerimaan mahasiswa Universitas Nahdlatul Ulama Purwokerto, mencakup status diterima atau tidaknya calon mahasiswa.",
            "checked" => $task['usrt_pengumuman'] == 1 ? true : false
        ],
        [
            "no" => $no++,
            "title" => "Melengkapi Data Diri",
            "description" => "Calon mahasiswa baru melakukan melengkapi data diri sebagai syarat mendapatkan NIM",
            "checked" => $task['usrt_melengkapi'] == 1 ? true : false
        ],
    ]
    ?>
    <div>
        <h4 class="font-weight-bold fs-medium">INFO GRAFIS</h4>
        <hr>
    </div>
    <div class="row">
        <?php foreach ($array_info_grafis as $info) : ?>
            <div class="card-info-grafis col-md-4 mb-5 <?= $info['checked'] ? 'checked' : '' ?>">
                <div class="card rounded-0 p-0">
                    <div class="card-body row">
                        <h4 class="font-weight-bolder col-3"><?= $info['no'] ?></h4>
                        <div class="col-7">
                            <h3 class="fs-normal"><?= $info['title'] ?></h3>
                            <small class="text-muted fs-small"><?= $info['description'] ?></small>
                        </div>
                        <?php if ($info['checked']) : ?>
                            <div class="checklist col-2">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>

        <?php endforeach ?>
    </div>
</section>

<?= $this->endSection() ?>
<?= $this->section('js') ?>

<script>
    var owlAuthSlider = $('.owl-carousel');
    $(document).ready(function() {
        owlAuthSlider.owlCarousel({
            items: 1,
            loop: true,
            nav: false,
            autoplay: true,
            smartSpeed: 1500,
            autoplayTimeout: 3000,
            lazyLoad: true
        });
    })
</script>

<?= $this->endSection() ?>