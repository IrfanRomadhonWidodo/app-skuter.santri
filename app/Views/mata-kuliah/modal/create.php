<!-- Modal -->
<div class="modal fade" id="<?= $modalId ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('mata-kuliah/save') ?>" method="post" id="form-save" enctype="multipart/form-data">
                    <div class="form-group mt-2">
                        <label for="mk_id">Kode Mata Kuliah</label>
                        <div class="input-group mb-3">
                            <input type="text" name="mk_id" id="mk_id" class="form-control">
                            <div class="input-group-append">

                            </div>
                            <div class="invalid-feedback errormk_id"></div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="mk_nama">Mata Kuliah</label>
                        <textarea name="mk_nama" id="mk_nama" class="form-control"></textarea>
                        <div class="invalid-feedback errormk_nama"></div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="mk_nama_en">Mata Kuliah (English)</label>
                        <textarea name="mk_nama_en" id="mk_nama_en" class="form-control"></textarea>
                        <div class="invalid-feedback errormk_nama_en"></div>
                    </div>
                    <!-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Agama
                                <span class="text-danger">*</span></label>
                            <select name="mk_agama" id="mk_agama" class="form-control">
                                <?php foreach ($agama as $agm) : ?>
                                    <option value="<?= $agm['ag_nama'] ?>"><?= $agm['ag_nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mk_sks">SKS</label>
                            <input type="number" name="mk_sks" id="mk_sks" class="form-control">
                        </div>
                    </div> -->

                    <div class="form-group mt-2">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6"><label for="name">Apakah Mata Kuliah Agama?
                                        <span class="text-danger">*</span></label>
                                    <select name="mk_agama" id="mk_agama" class="form-control">
                                        <option value="">Pilih</option>
                                        <?php foreach ($agama as $agm) : ?>
                                            <option value="<?= $agm['ag_nama'] ?>"><?= $agm['ag_nama'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="mk_sks">SKS</label>
                                    <input type="number" name="mk_sks" id="mk_sks" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>






            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-save" form="form-save" data-btn="<i class='fas fa-save mr-1'></i>Simpan Perubahan">
                    <i class="fas fa-save mr-1"></i>
                    Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#mk_id').on('input change', function() {
        // max 10 digit
        if ($(this).val().length > 10) {
            $(this).val($(this).val().substring(0, 6));
        }
    })

    $('#form-save').on('submit', function(e) {
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
            beforeSend: function() {
                btnSave
                    .attr('disabled', 'disabled')
                    .html(`<i class="fas fa-spinner fa-pulse mr-1"></i>Loading...`);
            },
            complete: function() {
                btnSave
                    .removeAttr('disabled')
                    .html(btnSave.data('btn'));
                reloadCsrf();
            },
            success: function(response) {
                console.log(response);
                let resp = JSON.parse(response);

                if (resp['error']) {
                    if (resp['error']['mk_id']) {
                        $('#mk_id').addClass('is-invalid');
                        $('.errormk_id').html(resp['error']['mk_id']);
                    } else {
                        $('#mk_id').removeClass('is-invalid');
                    }
                    if (resp['error']['mk_nama']) {
                        $('#mk_nama').addClass('is-invalid');
                        $('.errormk_nama').html(resp['error']['mk_nama']);
                    } else {
                        $('#mk_nama').removeClass('is-invalid');
                    }
                    if (resp['error']['mk_nama_en']) {
                        $('#mk_nama_en').addClass('is-invalid');
                        $('.errormk_nama_en').html(resp['error']['mk_nama_en']);
                    } else {
                        $('#mk_nama_en').removeClass('is-invalid');
                    }
                    return false;
                }

                if (resp['success']) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: resp['success']['msg'],
                        timer: 1800,
                        showConfirmButton: false
                    })

                    setTimeout(() => {
                        reloadDatatables();
                        $("<?= '#' . $modalId ?>").modal('hide');
                    }, 1000);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    })
</script>