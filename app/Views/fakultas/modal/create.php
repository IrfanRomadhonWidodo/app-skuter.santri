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
                <form action="<?= base_url('fakultas/save') ?>" method="post" id="form-save" enctype="multipart/form-data">
                    <div class="form-group mt-2">
                        <label for="fk_id">Kode Fakultas</label>
                        <div class="input-group mb-3">
                            <input type="text" name="fk_id" id="fk_id" class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2"># 6 digit</span>
                            </div>
                            <div class="invalid-feedback errorfk_id"></div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="fk_nama">Nama Fakultas</label>
                        <input type="text" name="fk_nama" id="fk_nama" class="form-control">
                        <div class="invalid-feedback errorfk_nama"></div>
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
    $('#fk_id').on('input change', function() {
        // max 6 digit
        if ($(this).val().length > 6) {
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
                    if (resp['error']['fk_id']) {
                        $('#fk_id').addClass('is-invalid');
                        $('.errorfk_id').html(resp['error']['fk_id']);
                    } else {
                        $('#fk_id').removeClass('is-invalid');
                    }
                    if (resp['error']['fk_nama']) {
                        $('#fk_nama').addClass('is-invalid');
                        $('.errorfk_nama').html(resp['error']['fk_nama']);
                    } else {
                        $('#fk_nama').removeClass('is-invalid');
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
                        reloadDatatablesFakultas();
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