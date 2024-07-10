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
                    action="<?= base_url('unit-kerja/'.$unit_kerja['unkj_id'].'/update') ?>"
                    method="post"
                    id="form-save"
                    enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="unkj_id">Kode Unit</label>
                        <div class="input-group">
                            <input type="text" name="unkj_id" id="unkj_id" class="form-control" value="<?= $unit_kerja['unkj_id'] ?>">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2"># 6 digit</span>
                            </div>
                            <div class="invalid-feedback errorunkj_id"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="unkj_nama">Nama Unit</label>
                        <input
                            type="text"
                            name="unkj_nama"
                            id="unkj_nama"
                            class="form-control" value="<?= $unit_kerja['unkj_nama'] ?>">
                        <div class="invalid-feedback errorunkj_nama"></div>
                    </div>
                    <div class="form-group">
                        <label for="unkj_keterangan">Keterangan</label>
                        <textarea
                            name="unkj_keterangan"
                            id="unkj_keterangan"
                            class="form-control"><?= $unit_kerja['unkj_keterangan'] ?></textarea>
                        <div class="invalid-feedback errorunkj_keterangan"></div>
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
                    if (resp['error']['unkj_id']) {
                        $('#unkj_id').addClass('is-invalid');
                        $('.errorunkj_id').html(resp['error']['unkj_id']);
                    } else {
                        $('#unkj_id').removeClass('is-invalid');
                    }
                    if (resp['error']['unkj_nama']) {
                        $('#unkj_nama').addClass('is-invalid');
                        $('.errorunkj_nama').html(resp['error']['unkj_nama']);
                    } else {
                        $('#unkj_nama').removeClass('is-invalid');
                    }
                    if (resp['error']['unkj_keterangan']) {
                        $('#unkj_keterangan').addClass('is-invalid');
                        $('.errorunkj_keterangan').html(resp['error']['unkj_keterangan']);
                    } else {
                        $('#unkj_keterangan').removeClass('is-invalid');
                    }
                    
                    if(resp['error']['msg'])
                    {
                        Swal.fire({icon: 'error', title: 'Gagal', text: resp['error']['msg'],timer: 1800, showConfirmButton: false})
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