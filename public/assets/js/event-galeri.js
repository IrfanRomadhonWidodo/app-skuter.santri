function reloadGaleri()
{
    let view = $('#view-fbox');
    $.ajax({
        url: view.data('url'),
        type: view.data('method'),
        success: function (result) {
            // console.log(result);
            resp = JSON.parse(result);

            if (resp['success']) {
                view.html('')
                $.each(resp['success']['data'], function (key, value) {
                    let html = `
                    <div class="col-sm-4">
                        <a
                            data-fancybox="gallery"
                            href="${base_url() + 'file?file=' + value['evt_gl_foto']}"><img
                            class="img-fluid img-fancy"
                            src="${base_url() + 'file?file=' + value['evt_gl_foto']}"></a>
                            <div class="text-center mt-2">
                            <button class="btn btn-success btn-sm mb-1 mr-1" data-url="${base_url() +
                                'event/galeri/' + value['evt_gl_id'] + '/modal-edit'}"
                                data-method="post" id="btnEdit-${value['evt_gl_id']}" onclick="return viewModal('btnEdit-${value['evt_gl_id']}' , false)"
                                data-btn="<i class='fas fa-edit'></i>" ${value['prm_edit'] == false ? 'disabled' : ''}
                                ><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm mb-1 mr-1" data-url="${base_url() +
                                    'event/galeri/' + value['evt_gl_id'] + '/delete'}"
                                    data-method="delete" id="btnDelete-${value['evt_gl_id']}" onclick="return btnDelete('${value['evt_gl_id']}')"
                                    data-btn="<i class='fas fa-trash'></i>" ${value['prm_delete'] == false ? 'disabled' : ''}
                                    ><i class="fas fa-trash"></i></button>
                            </div>
                    </div>
                    `
                    view.append(html);
                })
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

$(document).ready(function () {
    reloadGaleri();
})


function btnDelete(id) {
    Swal
        .fire({
            text: "Apakah anda yakin akan menghapus foto ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#001F3F',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Hapus!'
        })
        .then((result) => {
            if (result.isConfirmed) {
                let btn = $('#btnDelete-' + id);
                $.ajax({
                    url: btn.data('url'),
                    type: btn.data('method'),
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Menghapus...',
                            html: 'Harap Tunggu...',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                    },
                    success: function (result) {
                        // console.log(result);
                        resp = JSON.parse(result);

                        if (resp['error']) {
                            Swal.fire({icon: 'error', text: resp['error']['msg'], timer: 1800, showConfirmButton: false
                            })

                            return false;
                        }

                        if (resp['success']) {
                            Swal.fire({icon: 'success', text: resp['success']['msg'], timer: 1800, showConfirmButton: false
                            })
                        }
                        reloadGaleri();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            }

        });
}
