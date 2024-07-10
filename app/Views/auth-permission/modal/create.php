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
                    action="<?= base_url('permission/save') ?>"
                    method="post"
                    id="form-save"
                    enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="prm_nama">Nama Permission</label>
                        <input type="text" name="prm_nama" id="prm_nama" class="form-control">
                        <div class="invalid-feedback errorprm_nama"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prm_tipe">Tipe</label>
                                <select name="prm_tipe" id="prm_tipe" class="form-control">
                                    <option value="action">Aksi</option>
                                    <option value="view">View</option>
                                </select>
                                <div class="invalid-feedback errorprm_tipe"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <div class="form-group">
                                <label for="prm_tipe">Aplikasi</label>
                                <select name="prm_tipe" id="prm_tipe" class="form-control">
                                    <option value="action">Aksi</option>
                                    <option value="view">View</option>
                                </select>
                                <div class="invalid-feedback errorprm_tipe"></div>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                    <div class="form-group">
                        <label for="jbtn_keterangan">Keterangan</label>
                        <textarea name="jbtn_keterangan" id="jbtn_keterangan" class="form-control"></textarea>
                        <div class="invalid-feedback errorjbtn_keterangan"></div>
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
                    if (resp['error']['jbtn_id']) {
                        $('#jbtn_id').addClass('is-invalid');
                        $('.errorjbtn_id').html(resp['error']['jbtn_id']);
                    } else {
                        $('#jbtn_id').removeClass('is-invalid');
                    }
                    if (resp['error']['jbtn_nama']) {
                        $('#jbtn_nama').addClass('is-invalid');
                        $('.errorjbtn_nama').html(resp['error']['jbtn_nama']);
                    } else {
                        $('#jbtn_nama').removeClass('is-invalid');
                    }
                    if (resp['error']['jbtn_keterangan']) {
                        $('#jbtn_keterangan').addClass('is-invalid');
                        $('.errorjbtn_keterangan').html(resp['error']['jbtn_keterangan']);
                    } else {
                        $('#jbtn_keterangan').removeClass('is-invalid');
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