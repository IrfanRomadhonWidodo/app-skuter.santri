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
                <form action="<?= base_url('set-nilai/' . $setNilai['stn_id'] . '/update') ?>" method="post" id="form-save" enctype="multipart/form-data">

                    <div class="form-group mt-2">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="stn_nilai_huruf">Nilai Huruf</label>
                                    <input type="text" name="stn_nilai_huruf" id="stn_nilai_huruf" class="form-control" value="<?= htmlspecialchars($setNilai['stn_nilai_huruf'], ENT_QUOTES, 'UTF-8') ?>">
                                    <div class="invalid-feedback errorstn_nilai_huruf"></div>
                                </div>


                                <div class="col-md-6">
                                    <label for="stn_bobot">Bobot Nilai</label>
                                    <input type="number" name="stn_bobot" id="stn_bobot" class="form-control" step="0.01" value="<?= htmlspecialchars($setNilai['stn_bobot'], ENT_QUOTES, 'UTF-8') ?>">
                                    <div class="invalid-feedback errorstn_bobot"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="stn_dari">Dari (>)</label>
                                    <input type="number" name="stn_dari" id="stn_dari" class="form-control" value="<?= htmlspecialchars($setNilai['stn_dari'], ENT_QUOTES, 'UTF-8') ?>">
                                    <div class="invalid-feedback errorstn_dari"></div>
                                </div>


                                <div class="col-md-6">
                                    <label for="stn_sampai">Sampai (<=) </label>
                                            <input type="number" name="stn_sampai" id="stn_sampai" class="form-control" value="<?= htmlspecialchars($setNilai['stn_sampai'], ENT_QUOTES, 'UTF-8') ?>">
                                            <div class="invalid-feedback errorstn_sampai"></div>
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
                    if (resp['error']['stn_nilai_huruf']) {
                        $('#stn_nilai_huruf').addClass('is-invalid');
                        $('.errorstn_nilai_huruf').html(resp['error']['stn_nilai_huruf']);
                    } else {
                        $('#stn_nilai_huruf').removeClass('is-invalid');
                    }
                    if (resp['error']['stn_dari']) {
                        $('#stn_dari').addClass('is-invalid');
                        $('.errorstn_dari').html(resp['error']['stn_dari']);
                    } else {
                        $('#stn_dari').removeClass('is-invalid');
                    }
                    if (resp['error']['stn_sampai']) {
                        $('#stn_sampai').addClass('is-invalid');
                        $('.errorstn_sampai').html(resp['error']['stn_sampai']);
                    } else {
                        $('#stn_sampai').removeClass('is-invalid');
                    }
                    if (resp['error']['stn_bobot']) {
                        $('#stn_bobot').addClass('is-invalid');
                        $('.errorstn_bobot').html(resp['error']['stn_bobot']);
                    } else {
                        $('#stn_bobot').removeClass('is-invalid');
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