<!-- Modal -->
<div class="modal fade" id="<?= $modalId ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('auth-slider/' . $auth_slider['auth_slider_id'] . '/update') ?>" method="post" id="form-save" enctype="multipart/form-data">
                    <input type="hidden" name="auth_silder_img_old" value="<?= $auth_slider['auth_slider_img'] ?>">
                    <div class="form-group mb-3">
                        <div class="text-center">
                            <?php
                            if ($auth_slider['auth_slider_img'] != '' && file_exists(getSharedDirectory() . $auth_slider['auth_slider_img'])) {
                                $img_src = base_url('file?file=' . $auth_slider['auth_slider_img']);
                            } else {
                                $img_src = base_url('file?file=/master/default.png');
                            }
                            ?>
                            <img src="<?= $img_src ?>" class="w-75 img-preview" alt="">
                        </div>
                        <div class=" mt-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" data-default="<?= base_url('file?file=/master/default.png') ?>" id="auth_slider_img" name="auth_slider_img" accept=".jpg, .jpeg" onchange="singlePreviewImage('auth_slider_img')">
                                <label class="custom-file-label" for="auth_slider_img">Pilih Foto</label>
                                <div class="invalid-feedback errorauth_slider_img"></div>
                            </div>
                            <small>*File maximal berukuran 500kb dan format jpg atau jpeg</small>
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
                    if (resp['error']['auth_slider_img']) {
                        $('#auth_slider_img').addClass('is-invalid');
                        $('.errorauth_slider_img').html(resp['error']['auth_slider_img']);
                    } else {
                        $('#auth_slider_img').removeClass('is-invalid');
                    }
                    if (resp['error']['msg']) {
                        swal.fire({
                            icon: 'error',
                            title: 'gagal',
                            text: resp['error']['msg']
                        })
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