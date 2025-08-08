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

<main class="container-fluid px-5 mb-5 mt-3">
    <div class="row">
        <div class="col-md-8 d-flex flex-column" id="counting" data-url="<?= base_url('counting') ?>" data-method="get">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Total Pendaftar</h3>
                            <h2 class="card-text" id="totalPendaftarCount">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Dalam Proses Pendaftaran</h3>
                            <h2 class="card-text" id="totalDalamProses">1</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Selesai</h3>
                            <h2 class="card-text" id="totalSelesai">1</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header bg-transparent border-0">
                            <div class="card-title">
                                <div class="mb-3">
                                    Program Studi
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <canvas id="chartProdi" data-url="<?= base_url('chart/prodi-registrasi') ?>" data-method="get"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex flex-column">
            <div class="card flex-grow-1">
                <div class="card-header bg-transparent border-0">
                    <div class="card-title">
                        <div class="mb-3">
                            Fakultas
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <canvas id="fakultasChart" width="400" height="400" data-url="<?= base_url('chart/fakultas-registrasi') ?>" data-method="get"></canvas>
                            <div id="totalPendaftar" class="text-center" style="margin-top: 20px; font-weight: bold;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<script>
    var owlAuthSlider = $('.owl-carousel');
    $(document).ready(function () {
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const counting = $('#counting');

    $.ajax({
        url: counting.data('url'),
        method: counting.data('method'),
        dataType: 'json',
        success: function (response) {
            $('#totalPendaftarCount').text(response.total_pendaftar);
            $('#totalDalamProses').text(response.dalam_proses_pendaftaran);
            $('#totalSelesai').text(response.diterima);
        }
    });
</script>
<script>
   const ctx = document.getElementById('chartProdi').getContext('2d');
const chartProdi = $('#chartProdi');

$.ajax({
    url: chartProdi.data('url'),
    method: chartProdi.data('method'),
    dataType: 'json',
    success: function (response) {
        // Asumsikan response sudah berupa array JSON
        let labels = response.map(item => item.prodi_nama);
        let dataPilihan1 = response.map(item => item.jml_pilihan_1); // 'jumlah' harus sesuai dengan field dari response
        let dataPilihan2 = response.map(item => item.jml_pilihan_2); // 'jumlah' harus sesuai dengan field dari response
        let dataDiterima = response.map(item => item.jml_pilihan_diterima); // 'jumlah' harus sesuai dengan field dari response

        // Buat chart
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Prodi Pilihan 1',
                        data: dataPilihan1,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Prodi Pilihan 2',
                        data: dataPilihan2,
                        backgroundColor: 'rgba(255, 224, 99, 0.5)',
                        borderColor: 'rgb(255, 219, 99)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pilihan Diterima',
                        data: dataDiterima,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        },
                        ticks: {
                            stepSize: 1, // Mengatur kelipatan menjadi genap
                            callback: function (value) {
                                return Number.isInteger(value) ? value : ''; // Hanya menampilkan angka bulat
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Program Studi'
                        }
                    }
                }
            }
        });
    },
    error: function (xhr, status, error) {
        console.error(`Error: ${status} - ${error}`);
        alert('Gagal memuat data chart. Silakan coba lagi.');
    }
});
    
</script>

<script>
    // Ambil data pendaftar
    const fakultasChart = $('#fakultasChart');
    $.ajax({
        url: fakultasChart.data('url'),
        method: fakultasChart.data('method'),
        dataType: 'json',
        success: function (resp) {

            const total = resp.map(item => item.jml_pendaftar).reduce((a, b) => a + b, 0);
            document
                    .getElementById('totalPendaftar')
                    .innerText = `Total Pendaftar: ${total}`;

            const labels = resp.map(item => item.fk_nama);

            console.log(labels);
            

            const dataPendaftar = resp.reduce((acc, item) => {
                acc[item.fk_nama] = item.jml_pendaftar;
                return acc;
            }, {});

            const pie = document
                    .getElementById('fakultasChart')
                    .getContext('2d');
            new Chart(pie, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            data: Object.values(dataPendaftar),
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const percentage = ((value / total) * 100).toFixed(2);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        },
        error: function (xhr, status, error) {
            console.error(`Error: ${status} - ${error}`);
            alert('Gagal memuat data chart. Silakan coba lagi.');
        }
    })
</script>

<?= $this->endSection() ?>