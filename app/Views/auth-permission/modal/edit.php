<!-- Modal -->
<div
    class="modal fade"
    id="<?= $modalId ?>"
    data-backdrop="static"
    data-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form
                    action="<?= base_url('jabatan/'.$jabatan['jbtn_id'].'/update') ?>"
                    method="post"
                    id="form-save"
                    enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="jbtn_id">Kode Jabatan</label>
                        <div class="input-group">
                            <input type="text" name="jbtn_id" id="jbtn_id" class="form-control" value="<?= $jabatan['jbtn_id'] ?>">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2"># 6 digit</span>
                            </div>
                            <div class="invalid-feedback errorjbtn_id"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="jbtn_unkj_id">Unit Kerja</label>
                        <select name="jbtn_unkj_id" id="jbtn_unkj_id" class="form-control">
                            <option value="">-- Pilih --</option>
                            <?php foreach ($unit_kerja as $key => $value) : ?>
                            <option value="<?= $value['unkj_id'] ?>" <?= $jabatan['jbtn_unkj_id'] == $value['unkj_id'] ? 'selected' : '' ?>><?= $value['unkj_nama'] ?></option>
                            <?php endforeach ?>
                        </select>
                        <div class="invalid-feedback errorjbtn_unkj_id"></div>
                    </div>
                    <div class="form-group">
                        <label for="jbtn_nama">Nama Jabatan</label>
                        <input type="text" name="jbtn_nama" id="jbtn_nama" class="form-control" value="<?= $jabatan['jbtn_nama'] ?>">
                        <div class="invalid-feedback errorjbtn_nama"></div>
                    </div>
                    <div class="form-group">
                        <label for="jbtn_keterangan">Keterangan</label>
                        <textarea name="jbtn_keterangan" id="jbtn_keterangan" class="form-control"><?= $jabatan['jbtn_keterangan'] ?></textarea>
                        <div class="invalid-feedback errorjbtn_keterangan"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button
                    type="submit"
                    class="btn btn-primary"
                    id="btn-save"
                    form="form-save"
                    data-btn="<i class='fas fa-save mr-1'></i>Simpan Perubahan">
                    <i class="fas fa-save mr-1"></i>
                    Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#form-save').on('submit', function (e) {
        let btnSave = $('#btn-save');
        e.preventDefault();
        var formData = new FormData(this);
        formData.append(
            $('meta[name=csrf-name]').attr('content'),
            $('meta[name=csrf-value]').attr('content'),
        );
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            processData: false,
            contentType: false,
            chace: false,
            data: formData,
            beforeSend: function () {
                btnSave
                    .attr('disabled', 'disabled')
                    .html(`<i class="fas fa-spinner fa-pulse mr-1"></i>Loading...`);
            },
            complete: function () {
                btnSave
                    .removeAttr('disabled')
                    .html(btnSave.data('btn'));
                reloadCsrf();
            },
            success: function (response) {
                console.log(response);
                let resp = JSON.parse(response);

                if (resp['error']) {
                    if (resp['error']['jbtn_id']) {
                        $('#jbtn_id').addClass('is-invalid');
                        $('.errorjbtn_id').html(resp['error']['jbtn_id']);
                    } else {
                        $('#jbtn_id').removeClass('is-invalid');
                    }
                    if (resp['error']['jbtn_nama']) {
                        $('#jbtn_nama').addClass('is-invalid');
                        $('.errorjbtn_nama').html(resp['error']['jbtn_nama']);
                    } else {
                        $('#jbtn_nama').removeClass('is-invalid');
                    }
                    if (resp['error']['jbtn_keterangan']) {
                        $('#jbtn_keterangan').addClass('is-invalid');
                        $('.errorjbtn_keterangan').html(resp['error']['jbtn_keterangan']);
                    } else {
                        $('#jbtn_keterangan').removeClass('is-invalid');
                    }

                    if (resp['error']['msg']) {
                        Swal.fire(
                            {icon: 'error', title: 'Gagal', text: resp['error']['msg'], timer: 1800, showConfirmButton: false}
                        )
                    }
                    return false;
                }

                if (resp['success']) {
                    Swal.fire(
                        {icon: 'success', title: 'Berhasil', text: resp['success']['msg'], timer: 1800, showConfirmButton: false}
                    )

                    setTimeout(() => {
                        reloadDatatables();
                        $("<?= '#' . $modalId ?>").modal('hide');
                    }, 1000);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    })
</script>