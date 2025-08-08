<?= $this->extend('layout/template'); ?> <!-- Jika pakai template -->
<?= $this->section('content'); ?> <!-- Awal konten -->

<div class="container mt-4">
    <h2 class="mb-4">Dashboard Pembayaran SPP</h2>

    <div class="row">
        <!-- Kartu LUNAS -->
        <div class="col-md-4 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">LUNAS</h5>
                        <a href="<?= base_url('detail/lunas') ?>" class="btn btn-sm btn-primary">Lihat Detail</a>
                    </div>
                    <h2 class="card-text mt-3 text-success" id="totalLunas"><?= $totalLunas ?></h2>
                </div>
            </div>
        </div>

        <!-- Kartu CICILAN -->
        <div class="col-md-4 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">CICILAN</h5>
                        <a href="<?= base_url('detail/cicilan') ?>" class="btn btn-sm btn-primary">Lihat Detail</a>
                    </div>
                    <h2 class="card-text mt-3 text-warning" id="totalCicilan"><?= $totalCicilan ?></h2>
                </div>
            </div>
        </div>

        <!-- Kartu BELUM BAYAR -->
        <div class="col-md-4 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">BELUM BAYAR</h5>
                        <a href="<?= base_url('detail/belum') ?>" class="btn btn-sm btn-primary">Lihat Detail</a>
                    </div>
                    <h2 class="card-text mt-3 text-danger" id="totalBelum"><?= $totalBelum ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- (Opsional) Daftar semua data -->
    <div class="mt-5">
        <h4>Semua Data Pembayaran</h4>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Semester</th>
                    <th>Fakultas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pembayaranspp)) : ?>
                    <?php foreach ($pembayaranspp as $row) : ?>
                        <tr>
                            <td><?= esc($row['nim']) ?></td>
                            <td><?= esc($row['nama_mahasiswa']) ?></td>
                            <td><?= esc($row['semester']) ?></td>
                            <td><?= esc($row['fakultas']) ?></td>
                            <td><?= esc($row['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Lunas', 'Cicilan', 'Belum Lunas'],
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: [
                    <?= $totalLunas ?>,
                    <?= $totalCicilan ?>,
                    <?= $totalBelum ?>
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)', // Lunas
                    'rgba(255, 206, 86, 0.7)', // Cicilan
                    'rgba(255, 99, 132, 0.7)' // Belum Lunas
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Grafik Status Pembayaran SPP'
                }
            }
        }
    });
</script>


<?= $this->endSection(); ?> <!-- Akhir konten -->