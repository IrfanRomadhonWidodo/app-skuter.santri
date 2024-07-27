<?= $this->extend('layout/template') ?>
<?= $this->section('css') ?>

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
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-file mr-1"></i>
                    Draft Pengajuan Kas</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="kas_judul">Judul Pengajuan
                                <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="kas_judul"
                                id="kas_judul"
                                class="form-control"
                                onchange="return autosave('kas_judul')"
                                value="<?= $kas['kas_judul'] ?>"
                                data-url="<?= base_url('pengajuan-kas/'.$kas['kas_id'].'/autosave') ?>">
                            <div class="invalid-feedback errorkas_judul"></div>
                            <div class="valid-feedback successkas_judul"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kas_nominal">Nominal Pengajuan
                                <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="kas_nominal"
                                id="kas_nominal"
                                class="form-control"
                                onchange="return autosave('kas_nominal')"
                                value="<?= $kas['kas_nominal'] ?>"
                                data-url="<?= base_url('pengajuan-kas/'.$kas['kas_id'].'/autosave') ?>">
                            <div class="invalid-feedback errorkas_nominal"></div>
                            <div class="valid-feedback successkas_nominal"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kas_unkj_id">Unit
                                <span class="text-danger">*</span></label>
                            <select
                                name="kas_unkj_id"
                                id="kas_unkj_id"
                                class="form-control"
                                onchange="return autosave('kas_unkj_id')"
                                data-url="<?= base_url('pengajuan-kas/'.$kas['kas_id'].'/autosave') ?>">
                                <option value=""></option>
                                <?php foreach($unit as $u) : ?>
                                <option value="<?= $u['unkj_id'] ?>" <?= $u['unkj_id'] == $kas['kas_unkj_id'] ? 'selected' : '' ?>><?= $u['unkj_nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback errorkas_unkj_id"></div>
                            <div class="valid-feedback successkas_unkj_id"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="kas_keterangan">Keterangan
                                <span class="text-danger">*</span></label>
                            <textarea
                                type="text"
                                name="kas_keterangan"
                                id="kas_keterangan"
                                class="form-control"
                                data-url="<?= base_url('pengajuan-kas/'.$kas['kas_id'].'/autosave') ?>"
                                onchange="return autosave('kas_keterangan')"><?= $kas['kas_keterangan'] ?></textarea>
                            <div class="invalid-feedback errorkas_keterangan"></div>
                            <div class="valid-feedback successkas_keterangan"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <h6 class="font-weight-bold"># Berkas pendukung
                            <button class="btn btn-link" id="btnAddBerkas" data-show="0">
                                <i class="fas fa-plus"></i>
                            </button>
                        </h6>

                        <form
                            action="<?= base_url('pengajuan-kas/'.$kas['kas_id'].'/save-berkas') ?>"
                            method="post"
                            id="form-berkas"
                            enctype="multipart/form-data">
                            <div
                                class="form-group border rounded px-2 py-1"
                                id="berkasView"
                                style="display: none;">
                                <label for="berkas">File</label>
                                <div class="custom-file mb-1">
                                    <input
                                        type="file"
                                        class="custom-file-input"
                                        id="custom-file-input-1"
                                        name="files[]"
                                        accept=".pdf, .txt"
                                        onchange="changeLabel('1')">
                                    <label
                                        class="custom-file-label"
                                        id="custom-file-label-1"
                                        for="custom-file-input-1">Choose file</label>
                                </div>
                                <div class="additional-berkas"></div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-primary btn-block"
                                            id="btnAdditionalBerkas">
                                            <i class="fas fa-plus-square mr-2"></i>Tambah</button>
                                    </div>
                                    <div class="col-6">
                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-primary btn-block"
                                            id="btnSaveBerkas">
                                            <i class="fas fa-upload mr-2"></i>
                                            Upload</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <section
                            id="berkas-section"
                            data-url="<?= base_url('pengajuan-kas/'.$kas['kas_id'].'/get-berkas') ?>"
                            data-method="get"></section>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button
                    type="button"
                    class="btn btn-primary"
                    id="btn-submit"
                    data-url="<?= base_url('pengajuan-kas/'.$kas['kas_id'].'/submit') ?>"
                    data-method="post"
                    data-btn="<i class='fas fa-paper-plane mr-1'></i>Submit">
                    <i class="far fa-paper-plane mr-1"></i>
                    Submit</button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-info mr-1"></i>
                    User Guide</h3>
            </div>
            <div class="card-body">
                <ul>
                    <li>Form pengajuan ini menggunakan fitur autosave, yang akan menyimpan secara
                        otomatis setiap kali ada perubahan pada form.</li>
                    <li>Pengajuan Anda saat ini masih dalam bentuk draft.</li>
                    <li>Pastikan semua data telah sesuai dan lengkap sebelum menekan tombol
                        <span class="badge badge-primary">Submit</span></li>
                    <li>
                        <span class="text-danger">*</span>
                        wajib diisi.</li>
                    <li>File yang diupload harus berformat PDF atau TXT</li>
                    <li>Filte berjenis txt dapat digunakan untuk menyimpan tautan seperti Link
                        Google Drive, Bukti Transfer, Proposal , dll
                    </li>
                    <li>File yang diupload tidak boleh melebihi 10 MB</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"
    integrity="sha512-U5C477Z8VvmbYAoV4HDq17tf4wG6HXPC6/KM9+0/wEXQQ13gmKY2Zb0Z2vu0VNUWch4GlJ+Tl/dfoLOH4i2msw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
<script src="<?= base_url('assets/js/pengajuan-kas/draft.js?v='.time()) ?>"></script>

<?= $this->endSection() ?>