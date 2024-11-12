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
                    action="<?= base_url('setting/approver-pengajuan/save') ?>"
                    method="post"
                    id="form-save"
                    enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="set_appr_grp_label">Group / Otoritas</label>
                        <div class="input-group">
                            <select name="set_appr_grp_label" id="set_appr_grp_label" class="form-control">
                                <option value="">-- Pilih --</option>
                                <?php foreach($groups as $group) : ?>
                                <option value="<?= $group['grp_label'] ?>"><?= $group['grp_nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <div class="invalid-feedback errorset_appr_grp_label"></div>
                        </div>
                        <div class="form-group">
                            <label for="set_appr_level">Level</label>
                            <select name="set_appr_level" id="set_appr_level" class="form-control">
                                <option value="0">-- Pilih --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
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
                    data-btn="<i class='fas fa-save mr-1'></i>Simpan">
                    <i class="fas fa-save mr-1"></i>
                    Simpan</button>
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
                    if (resp['error']['set_appr_grp_label']) {
                        $('#set_appr_grp_label').addClass('is-invalid');
                        $('.errorset_appr_grp_label').html(resp['error']['set_appr_grp_label']);
                    } else {
                        $('#set_appr_grp_label').removeClass('is-invalid');
                    }
                    if (resp['error']['set_appr_level']) {
                        $('#set_appr_level').addClass('is-invalid');
                        $('.errorset_appr_level').html(resp['error']['set_appr_level']);
                    } else {
                        $('#set_appr_level').removeClass('is-invalid');
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
                        reloadDatatablesPengajuan();
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