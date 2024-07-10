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
                <form action="<?= base_url('terjemahan/save') ?>" method="post" id="form-save" enctype="multipart/form-data">
                    <div class="form-group mt-2">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6"><label for="name">Aplikasi
                                        <span class="text-danger">*</span></label>
                                    <select name="tjmh_app" id="tjmh_app" class="form-control">
                                        <option value="">Pilihan</option>
                                        <option value="">Master Data</option>
                                        <option value="">Keuangan</option>
                                        <option value="">Kepegawaian</option>
                                        <option value="">General</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6"><label for="name">Type
                                        <span class="text-danger">*</span></label>
                                    <select name="tjmh_type" id="tjmh_type" class="form-control">
                                        <option value="">Pilihan</option>
                                        <option value="">Text</option>
                                        <option value="">Button</option>
                                        <option value="">Label</option>
                                        <option value="">Alert</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="tjmh_bahasa">Bahasa Indonesia</label>
                        <textarea name="tjmh_bahasa" id="tjmh_bahasa" class="form-control"></textarea>
                        <div class="invalid-feedback errortjmh_bahasa"></div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="tjmh_english">Bahasa Inggris</label>
                        <textarea name="tjmh_english" id="tjmh_english" class="form-control"></textarea>
                        <div class="invalid-feedback errortjmh_english"></div>
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