<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>
<style>
    .img-mahasiswa img {
        border: 5px solid #eee;
        object-fit: cover;
        width: 350px;
        aspect-ratio: 1/1;
    }
</style>

<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mb-2">
    <button
        class="btn btn-outline-secondary rounded-pill px-3"
        id="btnBack"
        onclick="btnBack('btnBack')"
        data-btn="<i class='fa fa-arrow-left'></i> Kembali">
        <i class="fa fa-arrow-left"></i>
        Kembali</button>
</div>
<?= $this->include('user-mahasiswa/assets/nav-tabs') ?>
<div class="card mt-1">
    <div class="card-body">
        <form
            action="<?= base_url('mahasiswa/save') ?>"
            method="post"
            id="form-save"
            enctype="multipart/form-data">
            <input type="hidden" name="usr_foto_old" value="<?= $user['usr_foto'] ?>">
            <div class="row">
                <div class="col-md-5 my-auto">
                    <div class="form-group mb-3">
                        <div class="img-mahasiswa text-center">
                            <?php if(!empty($user['usr_foto']) && file_exists(base_url('file?file='.$user['usr_foto']))){
                                    $img = base_url('file?file='.$user['usr_foto']);
                                }else{
                                    $img = base_url('file?file=/master/default.png');
                                } ?>
                            <img
                                src="<?= $img ?>"
                                class="rounded-circle img-preview"
                                alt="">
                        </div>
                        <div class="row mt-3">
                            <div class="col-6 offset-3">
                                <div class="custom-file">
                                    <input
                                        type="file"
                                        class="custom-file-input"
                                        data-default="<?= base_url('file?file=/master/default.png') ?>"
                                        id="usr_foto"
                                        name="usr_foto"
                                        onchange="singlePreviewImage('usr_foto')">
                                    <label class="custom-file-label" for="usr_foto">Pilih Foto</label>
                                </div>
                                <small>*Foto yg diupload foto resmi. File maximal berukuran 500kb dan format
                                    file .png/.jpg/jpeg</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="usr_nama">Nama Mahasiswa
                                    <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="usr_nama"
                                    name="usr_nama"
                                    aria-describedby="usr_nama"
                                    value="<?= $user['usr_nama'] ?>"
                                    placeholder="Masukkan nama mahasiswa...">
                                <div class="invalid-feedback errorusr_nama"></div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="usr_nik">NIK KTP
                                    <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="usr_nik"
                                    name="usr_nik"
                                    value="<?= $user['usr_nik'] ?>"
                                    aria-describedby="usr_nik"
                                    placeholder="Masukkan NIK KTP...">
                                <div class="invalid-feedback errorusr_nik"></div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="usr_tempat_lahir">Tempat Lahir
                                    <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="usr_tempat_lahir"
                                    name="usr_tempat_lahir"
                                    value="<?= $user['usr_tempat_lahir'] ?>"
                                    aria-describedby="usr_tempat_lahir"
                                    placeholder="Masukkan tempat lahir...">
                                <div class="invalid-feedback errorusr_tempat_lahir"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr_tanggal_lahir">Tanggal Lahir
                                    <span class="text-danger">*</span></label>
                                <input
                                    type="date"
                                    class="form-control"
                                    name="usr_tanggal_lahir"
                                    id="usr_tanggal_lahir"
                                    value="<?= $user['usr_tanggal_lahir'] ?>"
                                    aria-describedby="usr_tanggal_lahir">
                                <div class="invalid-feedback errorusr_tanggal_lahir"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="usr_jenis_kelamin">Jenis Kelamin
                                    <span class="text-danger">*</span></label>
                                <select name="usr_jenis_kelamin" id="usr_jenis_kelamin" class="form-control">
                                    <option value="L" <?= $user['usr_jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="P" <?= $user['usr_jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr_kewarganegaraan">Kewarganegaraan
                                    <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend bg-light">
                                        <label class="input-group-text bg-light" id="usr_kewarganegaraan_bendera"><img src="<?= base_url('file?file=/master/img-country-flag/ID.png') ?>" alt=""></label>
                                    </div>
                                    <select
                                        name="usr_kewarganegaraan"
                                        id="usr_kewarganegaraan"
                                        class="form-control">
                                        <?php foreach ($negara as $key => $value) : ?>
                                        <option
                                            value="<?= $value['ngr_id'] ?>"
                                            <?= $value['ngr_id'] == $user['usr_kewarganegaraan'] ? 'selected' : '' ?>
                                            data-flag="<?= $value['ngr_bendera'] ?>"><?= $value['ngr_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback errorusr_kewarganegaraan"></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr_agama">Agama
                                    <span class="text-danger">*</span></label>
                                <select name="usr_agama" id="usr_agama" class="form-control">
                                    <?php foreach($agama as $agm) : ?>
                                    <option
                                        value="<?= $agm['ag_nama'] ?>"
                                        <?= $agm['ag_nama'] == $user['usr_agama'] ? 'selected' : '' ?>><?= $agm['ag_nama'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="invalid-feedback errorusr_agama"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr_golongan_darah">Golongan Darah</label>
                                <select name="usr_golongan_darah" id="usr_golongan_darah" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="A" <?= $user['usr_golongan_darah'] == 'A' ? 'selected' : '' ?>>A</option>
                                    <option value="B" <?= $user['usr_golongan_darah'] == 'B' ? 'selected' : '' ?>>B</option>
                                    <option value="AB" <?= $user['usr_golongan_darah'] == 'AB' ? 'selected' : '' ?>>AB</option>
                                    <option value="O" <?= $user['usr_golongan_darah'] == 'O' ? 'selected' : '' ?>>O</option>

                                </select>
                                <div class="invalid-feedback errorusr_golongan_darah"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr_nomor_hp">Nomor HP
                                    <span class="text-danger">*</span></label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="usr_nomor_hp"
                                    name="usr_nomor_hp"
                                    value="<?= $user['usr_nomor_hp'] ?>"
                                    placeholder="Masukkan Nomor HP...">
                                <div class="invalid-feedback errorusr_nomor_hp"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr_email">Email
                                    <span class="text-danger">*</span></label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="usr_email"
                                    name="usr_email"
                                    value="<?= $user['usr_email'] ?>"
                                    placeholder="Masukkan Email...">
                                <div class="invalid-feedback errorusr_email"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr_bahasa">Default Bahasa</label>
                                <select name="usr_bahasa" id="usr_bahasa" class="form-control">
                                    <option value="ID" <?= $user['usr_bahasa'] == 'ID' ? 'selected' : '' ?>>Indonesia (ID)</option>
                                    <option value="EN" <?= $user['usr_bahasa'] == 'EN' ? 'selected' : '' ?>>Inggris (EN)</option>
                                </select>
                                <div class="invalid-feedback errorusr_bahasa"></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="usr_mhs_prodi_id">Program Studi - Fakultas
                                    <span class="text-danger">*</span></label>
                                <select name="usr_mhs_prodi_id" id="usr_mhs_prodi_id" class="form-control">
                                    <option value="">Pilih Program Studi</option>
                                    <?php foreach ($program_studi as $key => $value) : ?>
                                    <option
                                        value="<?= $value['prodi_id'] ?>"
                                        <?= $value['prodi_id'] == $user['usr_mhs_prodi_id'] ? 'selected' : '' ?>><?= $value['prodi_id'] ?>
                                        - Program Studi
                                        <?= $value['prodi_nama'] ?>
                                        - Fakultas
                                        <?= $value['fk_nama'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="invalid-feedback errorusr_mhs_prodi_id"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usr_mhs_angkatan">Angkatan
                                    <span class="text-danger">*</span></label>
                                <select name="usr_mhs_angkatan" id="usr_mhs_angkatan" class="form-control">
                                    <option value="">-- Pilih Angkatan --</option>
                                    <?php for ($i = 2016; $i <= date('Y') + 1; $i++) : ?>
                                    <option
                                        value="<?= $i ?>"
                                        <?= $i == $user['usr_mhs_angkatan'] ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor ?>
                                </select>
                                <div class="invalid-feedback errorusr_mhs_angkatan"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usr_id">Username / NIM
                                    <span class="text-danger">**</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="usr_id"
                                    name="usr_id"
                                    readonly="readonly"
                                    value="<?= $user['usr_id'] ?>"
                                    placeholder="Masukkan NIM mahasiswa...">
                                <div class="invalid-feedback errorusr_id"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer">
        <button
            class="btn btn-success rounded-pill px-3"
            type="button"
            id="btn-save"
            data-btn="<i class='fas fa-save mr-1'></i>Simpan">
            <i class="fas fa-save mr-1"></i>Simpan</button>
        <button
            class="btn btn-primary rounded-pill px-3"
            type="button"
            id="btn-save-and-add"
            data-btn="<i class='fas fa-save mr-1'></i>Simpan dan Tambah Baru">
            <i class="fas fa-save mr-1"></i>Simpan dan Tambah Baru</button>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/user-mahasiswa/create.js?v='.time()) ?>"></script>
<?= $this->endSection() ?>