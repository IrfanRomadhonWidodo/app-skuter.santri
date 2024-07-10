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
                <form action="<?= base_url('ruang-kelas/save') ?>" method="post" id="form-save" enctype="multipart/form-data">
                    <div class="form-group mt-2">
                        <label for="rk_label">Label Ruang Kelas</label>
                        <div class="input-group mb-3">
                            <input type="text" name="rk_label" id="rk_label" class="form-control">
                            <div class="invalid-feedback errorrk_label"></div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="rk_lokasi">Lokasi Ruang Kelas</label>
                        <input type="text" name="rk_lokasi" id="rk_lokasi" class="form-control">
                        <div class="invalid-feedback errorrk_lokasi"></div>
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
                    if (resp['error']['rk_label']) {
                        $('#rk_label').addClass('is-invalid');
                        $('.errorrk_label').html(resp['error']['rk_label']);
                    } else {
                        $('#rk_label').removeClass('is-invalid');
                    }
                    if (resp['error']['rk_lokasi']) {
                        $('#rk_lokasi').addClass('is-invalid');
                        $('.errorrk_lokasi').html(resp['error']['rk_lokasi']);
                    } else {
                        $('#rk_lokasi').removeClass('is-invalid');
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

<!-- <script>
    $('#form-save').on('submit', function(e) {
        let btnSave = $('#btn-save');
        e.preventDefault();

        var formData = new FormData(this);
        // Tambahkan CSRF token ke dalam FormData
        formData.append(
            $('meta[name=csrf-name]').attr('content'),
            $('meta[name=csrf-value]').attr('content')
        );

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            processData: false,
            contentType: false,
            cache: false, // Perbaikan pada penulisan cache
            data: formData,
            beforeSend: function() {
                btnSave.attr('disabled', 'disabled')
                    .html(`<i class="fas fa-spinner fa-pulse mr-1"></i>Loading...`);
            },
            complete: function() {
                btnSave.removeAttr('disabled')
                    .html(btnSave.data('btn'));
                reloadCsrf();
            },
            success: function(response) {
                console.log(response);
                let resp = JSON.parse(response);

                if (resp.error) {
                    if (resp.error.rk_label) {
                        $('#rk_label').addClass('is-invalid');
                        $('.errorrk_label').html(resp.error.rk_label);
                    } else {
                        $('#rk_label').removeClass('is-invalid');
                    }
                    if (resp.error.rk_lokasi) {
                        $('#rk_lokasi').addClass('is-invalid');
                        $('.errorrk_lokasi').html(resp.error.rk_lokasi);
                    } else {
                        $('#rk_lokasi').removeClass('is-invalid');
                    }
                    return false;
                }

                if (resp.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: resp.success.msg,
                        timer: 1800,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        reloadDatatables();
                        $('#<?= $modalId ?>').modal('hide');
                    }, 1000);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
</script> -->