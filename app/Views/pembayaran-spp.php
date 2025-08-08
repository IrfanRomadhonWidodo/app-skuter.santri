<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

<div class="container mt-4">
    <h2 class="mb-4">Dashboard Pembayaran SPP</h2>

    <div class="row mb-4">
        <?php
        $totalSemua = $totalLunas + $totalCicilan + $totalBelum;
        function persen($jumlah, $total)
        {
            return $total ? round(($jumlah / $total) * 100, 1) : 0;
        }
        ?>
        <div class="col-md-4" data-aos="fade-up">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6>LUNAS</h6>
                    <h3><?= $totalLunas ?></h3>
                    <p><?= persen($totalLunas, $totalSemua) ?>% mahasiswa</p>
                    <a href="<?= base_url('detail/lunas') ?>" class="btn btn-light btn-sm mt-2">Lihat Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6>CICILAN</h6>
                    <h3><?= $totalCicilan ?></h3>
                    <p><?= persen($totalCicilan, $totalSemua) ?>% mahasiswa</p>
                    <a href="<?= base_url('detail/cicilan') ?>" class="btn btn-light btn-sm mt-2">Lihat Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h6>BELUM BAYAR</h6>
                    <h3><?= $totalBelum ?></h3>
                    <p><?= persen($totalBelum, $totalSemua) ?>% mahasiswa</p>
                    <a href="<?= base_url('detail/belum') ?>" class="btn btn-light btn-sm mt-2">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8" data-aos="fade-right">
            <canvas id="lineChart" height="160"></canvas>
        </div>
        <div class="col-md-4" data-aos="fade-left">
            <h5 class="text-center">Distribusi Status Pembayaran</h5>
            <canvas id="donutChart" height="200"></canvas>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6" data-aos="fade-right">
            <h5>Progress Pembayaran per Fakultas</h5>
            <?php foreach ($fakultasProgress as $f): ?>
                <p class="mb-1 fw-bold"><?= $f['nama'] ?> (<?= $f['persen'] ?>%)</p>
                <div class="progress mb-3">
                    <div class="progress-bar bg-info" role="progressbar" style="width: <?= $f['persen'] ?>%"></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-md-6" data-aos="fade-left">
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">5 Mahasiswa Terakhir Bayar</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0" id="latestPaymentTable">
                            <thead class="table-light">
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Fakultas</th>
                                    <th>Prodi</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($terakhirBayar)) : ?>
                                    <?php foreach ($terakhirBayar as $row) : ?>
                                        <tr>
                                            <td><?= esc($row['nim']) ?></td>
                                            <td><?= esc($row['nama_lengkap']) ?></td>
                                            <td><?= esc($row['fakultas']) ?></td>
                                            <td><?= esc($row['prodi']) ?></td>
                                            <td><?= esc(date('d M Y', strtotime($row['tanggal_bayar']))) ?></td>
                                            <td><span class="badge bg-secondary"><?= esc($row['status']) ?></span></td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data pembayaran.</td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    AOS.init();

    $(document).ready(function() {
        $('#latestPaymentTable').DataTable();
    });

    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($grafikLabel) ?>,
            datasets: [{
                    label: 'Lunas',
                    data: <?= json_encode($grafikLunas) ?>,
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4
                },
                {
                    label: 'Cicilan',
                    data: <?= json_encode($grafikCicilan) ?>,
                    borderColor: '#f1c40f',
                    backgroundColor: 'rgba(241, 196, 15, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4
                },
                {
                    label: 'Belum Bayar',
                    data: <?= json_encode($grafikBelum) ?>,
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Tren Pembayaran SPP',
                    font: {
                        size: 18
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });

    const donutCtx = document.getElementById('donutChart').getContext('2d');
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Lunas', 'Cicilan', 'Belum Bayar'],
            datasets: [{
                data: [<?= $totalLunas ?>, <?= $totalCicilan ?>, <?= $totalBelum ?>],
                backgroundColor: ['#2ecc71', '#f1c40f', '#e74c3c'],
                hoverOffset: 8
            }]
        },
        options: {
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
<?= $this->endSection(); ?>