let dataAjax = {}

table = null;
table = $('#table-authSlider').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": true,
    "scrollX": true,
    "ajax": {
        "url": $('#table-authSlider').data('url'),
        "type": $('#table-authSlider').data('method'),
        "data": function (d) {
            return $.extend(d, dataAjax);
        },

    },
    "deferRender": true,
    "aLengthMenu": [
        [
            5, 10, 50
        ],
        [
            5, 10, 50
        ]
    ],
    "columns": [
        {
            "data": "auth_slider_id",
            "sortable": false,
            "class": "text-center",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            "data": "auth_slider_id",
            "class": "text-nowrap",
            "sortable": false,
            "render": function (data, type, row, meta) {
                let btnEdit = `<button class="btn btn-success btn-sm mb-1 mr-1" data-url="${base_url() + 'a' +
                        'uth-slider/' + data + '/modal-edit'}"
                    data-method="post" id="btnEdit-${data.replace(
                    /\./g,
                    ""
                )}" onclick="return viewModal('btnEdit-${data.replace(/\./g, "")}' , false)"
                    data-btn="<i class='fas fa-edit'></i>"
                    ><i class="fas fa-edit"></i></button>`

                let btnDelete = `<button class="btn btn-danger btn-sm mb-1 mr-1" data-url="${base_url() + 'mata-kuliah/' +
                        data + '/delete'}"
                    data-method="delete" id="btnDelete-${data.replace(
                    /\./g,
                    ""
                )}" onclick="return btnDelete('${data.replace(/\./g, "")}')"
                    data-btn="<i class='fas fa-trash'></i>"
                    ><i class="fas fa-trash"></i></button>`

                return btnEdit + btnDelete;
            }
        }, {
            "data": "auth_slider_img",
            "render": function (data, type, row, meta) {
                return `<img src="${base_url()+'file?file='+ data}" width="100" />`;
            }
        }, {
            "data": "auth_slider_aktif",
            "render": function (data, type, row, meta) {
                return `<div class="custom-control custom-switch switch-aktif">
                            <input type="checkbox" class="custom-control-input" 
                            data-url="${base_url() + 'auth-slider/' + row.auth_slider_id + '/change-status'}" 
                            data-method="post" 
                            onchange="return changeStatus('${ 'auth_slider_aktif-' +row.auth_slider_id}')" id="${ 'auth_slider_aktif-' + row.auth_slider_id}" ${data == 1? 'checked ': ''}>
                            <label class="custom-control-label" for="${ 'auth_slider_aktif-' +
                            row.auth_slider_id}">Aktif</label>
                        </div>`;
        }
    }
    ],
});

function reloadDatatables() {
    table
        .ajax
        .reload();
}

function changeStatus(id) {
    let status = $('#'+id).is(':checked') ? 1 : 0;
    $.ajax({
        url: $('#'+id).data('url'),
        type: $('#'+id).data('method'),
        data: {
            auth_slider_aktif: status
        },
        beforeSend: function () {
            $('#'+id).attr('disabled', 'disabled');
        },
        complete: function () {
            $('#'+id).removeAttr('disabled');
        },
        success: function (data) {
            reloadDatatables();
        }
    })
}

function btnDelete(id) {

    Swal.fire({
        text: "Apakah anda yakin akan menghapus data ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#001F3F',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            let btn = $('#btnDelete-' + id);
            $.ajax({
                url: btn.data('url'),
                type: btn.data('method'),
                success: function (data) {
                    let resp = JSON.parse(data);
                    if(resp['error']) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: resp['error']['msg']
                        })
                    }
                    

                    if(resp['success'])
                    {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: resp['success']['msg'],
                            timer: 1800,
                            showConfirmButton: false
                        })
                        reloadDatatables();
                    }
                }
            })
        }
    })
}