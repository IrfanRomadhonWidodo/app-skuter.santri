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
                <form action="<?= base_url('program-studi/save') ?>" method="post" id="form-save" enctype="multipart/form-data">
                    <div class="form-group mt-2">
                        <label for="prodi_fk_id">Fakultas</label>
                            <select name="prodi_fk_id" id="prodi_fk_id" class="form-control">
                                <option value="">-- Pilih Fakultas --</option>
                                <?php foreach($fakultas as $fk) : ?>
                                <option value="<?= $fk['fk_id'] ?>"><?= $fk['fk_nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <div class="invalid-feedback errorprodi_fk_id"></div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="prodi_id">Kode Program Studi</label>
                        <div class="input-group mb-3">
                            <input type="text" name="prodi_id" id="prodi_id" class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2"># 6 digit</span>
                            </div>
                            <div class="invalid-feedback errorprodi_id"></div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="prodi_nama">Nama Program Studi</label>
                        <input type="text" name="prodi_nama" id="prodi_nama" class="form-control">
                        <div class="invalid-feedback errorprodi_nama"></div>
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
    $('#prodi_id').on('input change', function() {
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
                    if (resp['error']['prodi_fakultas']) {
                        $('#prodi_fakultas').addClass('is-invalid');
                        $('.prodi_fakultas').html(resp['error']['prodi_fakultas']);
                    } else {
                        $('#prodi_fakultas').removeClass('is-invalid');
                    }
                    if (resp['error']['prodi_id']) {
                        $('#prodi_id').addClass('is-invalid');
                        $('.errorprodi_id').html(resp['error']['prodi_id']);
                    } else {
                        $('#prodi_id').removeClass('is-invalid');
                    }
                    if (resp['error']['prodi_nama']) {
                        $('#prodi_nama').addClass('is-invalid');
                        $('.errorprodi_nama').html(resp['error']['prodi_nama']);
                    } else {
                        $('#prodi_nama').removeClass('is-invalid');
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
                        reloadDatatablesProgramStudi();
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