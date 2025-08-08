<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-4">
    <h2><?= $title ?></h2>
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Fakultas</th>
                    <th>Prodi</th>
                    <th>Semester</th>
                    <th>Total Bayar</th>
                    <th>Sudah Bayar</th>
                    <th>Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($list)) : ?>
                    <?php foreach ($list as $row) : ?>
                        <tr>
                            <td><?= esc($row['nim']) ?></td>
                            <td><?= esc($row['nama_lengkap']) ?></td>
                            <td><?= esc($row['fakultas']) ?></td>
                            <td><?= esc($row['prodi']) ?></td>
                            <td><?= esc($row['semester']) ?></td>
                            <td><?= number_format($row['total_bayar']) ?></td>
                            <td><?= number_format($row['sudah_bayar']) ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_bayar'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>