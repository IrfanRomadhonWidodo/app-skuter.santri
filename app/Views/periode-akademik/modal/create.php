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
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form
                    action="<?= base_url('periode-akademik/save') ?>"
                    method="post"
                    id="form-save"
                    enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="prd_akd_tahun_1">Tahun Akademik</label>
                                <div class="input-group mb-3">
                                    <select
                                        class="form-control"
                                        id="prd_akd_tahun_1"
                                        name="prd_akd_tahun_1"
                                        aria-describedby="prd_akd_tahun_1_feedback">
                                        <?php
                                            $currentYear = date("Y");
                                            for ($year = 2015; $year <= $currentYear + 1; $year++) {
                                                echo "<option value=\"$year\" ".($year == date("Y") ? "selected" : "").">$year</option>";
                                            }
                                            ?>
                                    </select>
                                    <div class="invalid-feedback" id="prd_akd_tahun_1_feedback"></div>
                                    <div class="input-group-append">
                                        <span class="input-group-text font-weight-bold">/
                                            <span id="input-group-tahun-2" class="ml-2"><?= $currentYear+1 ?></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prd_akd_semester">Semester</label>
                                <select name="prd_akd_semester" id="prd_akd_semester" class="form-control">
                                    <option value="1">Ganjil</option>
                                    <option value="2">Genap</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="prd_akd_expired" class="col-sm-3 col-form-label">Masa berlaku</label>
                        <div class="col-sm-9">
                            <input
                                type="date"
                                class="form-control"
                                id="prd_akd_expired"
                                name="prd_akd_expired">
                            <div class="invalid-feedback errorprd_akd_expired"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="prd_akd_aktif" name="prd_akd_aktif">
                            <label class="custom-control-label" for="prd_akd_aktif">Periode Akademik ini aktif digunakan</label>
                        </div>
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
    $('#prd_akd_tahun_1').on('change', function () {
        $('#input-group-tahun-2').html(parseInt($(this).val()) + 1);
    });

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
                    if (resp['error']['prd_akd_tahun_1']) {
                        $('#prd_akd_tahun_1').addClass('is-invalid');
                        $('.errorprd_akd_tahun_1').html(resp['error']['prd_akd_tahun_1']);
                    } else {
                        $('#prd_akd_tahun_1').removeClass('is-invalid');
                    }

                    if (resp['error']['prd_akd_expired']) {
                        $('#prd_akd_expired').addClass('is-invalid');
                        $('.errorprd_akd_expired').html(resp['error']['prd_akd_expired']);
                    } else {
                        $('#prd_akd_expired').removeClass('is-invalid');
                    }

                    if (resp['error']['msg']) {
                        Swal.fire(
                            {icon: 'error', title: 'Gagal', text: resp['error']['msg'], timer: 1800, showConfirmButton: false}
                        )
                        return false;
                    }
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