<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Mahasiswa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (optional if you use icon somewhere else) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-4">
        <h2>Edit Data Mahasiswa</h2>

        <form action="<?= base_url('pembayaranspp/update/' . $data['nim']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" id="nim" name="nim" value="<?= esc($data['nim'] ?? '') ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= esc($data['nama_lengkap'] ?? '') ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <input type="number" id="semester" name="semester" value="<?= esc($data['semester'] ?? '') ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="fakultas" class="form-label">Fakultas</label>
                <select name="fakultas" id="fakultas" class="form-select" required>
                    <?php
                    $fakultasList = ['FAI', 'FSH', 'FST'];
                    foreach ($fakultasList as $f): ?>
                        <option value="<?= $f ?>" <?= ($data['fakultas'] ?? '') == $f ? 'selected' : '' ?>>
                            <?= $f ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="prodi" class="form-label">Program Studi</label>
                <select name="prodi" id="prodi" class="form-select" required>
                    <?php
                    $prodiList = [
                        'Agroteknologi',
                        'Teknik Pangan',
                        'Teknik Pertanian & Biosistem',
                        'Peternakan',
                        'Biologi',
                        'Matematika',
                        'Ilmu Perikanan',
                        'Ilmu Keolahragaan',
                        'Sains Lingkungan',
                        'Manajemen',
                        'Akuntansi',
                        'Administrasi Publik',
                        'Ilmu Hukum',
                        'Hukum Syari\'ah',
                        'Pendidikan Bahasa Inggris',
                        'Pendidikan Agama Islam',
                        'Pendidikan Islam Anak Usia Dini',
                        'Pendidikan Guru Madrasah Ibtidaiyah',
                        'Pendidikan Bahasa Arab'
                    ];
                    foreach ($prodiList as $p): ?>
                        <option value="<?= $p ?>" <?= ($data['prodi'] ?? '') == $p ? 'selected' : '' ?>>
                            <?= $p ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="total_bayar" class="form-label">Total Biaya (Rp)</label>
                <input type="number" id="total_bayar" name="total_bayar" value="<?= esc($data['total_bayar'] ?? '') ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="sudah_bayar" class="form-label">Sudah Dibayar (Rp)</label>
                <input type="number" id="sudah_bayar" name="sudah_bayar" value="<?= esc($data['sudah_bayar'] ?? '') ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                <input type="date" id="tanggal_bayar" name="tanggal_bayar" value="<?= esc($data['tanggal_bayar'] ?? '') ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status Pembayaran</label>
                <select name="status" id="status" class="form-select" required>
                    <?php
                    $statusList = ['Lunas', 'Cicilan', 'Belum Lunas'];
                    foreach ($statusList as $s): ?>
                        <option value="<?= $s ?>" <?= ($data['status'] ?? '') == $s ? 'selected' : '' ?>>
                            <?= $s ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="approval" class="form-label">Approval</label>
                <select name="approval" id="approval" class="form-select" required>
                    <?php
                    $approvalList = ['Approved', 'Non Approved'];
                    foreach ($approvalList as $a): ?>
                        <option value="<?= $a ?>" <?= ($data['approval'] ?? '') == $a ? 'selected' : '' ?>>
                            <?= $a ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>


        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="<?= base_url('pembayaranspp') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>