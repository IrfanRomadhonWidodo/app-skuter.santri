tabel = null
tabel = $('#table-user').DataTable({
    "processing": true, "responsive": true, "serverSide": true, "ordering": true,
    // "scrollX": true,
    "order": [
        [0, 'asc']
    ],
    "ajax": {
        "url": $('#table-user').data('url'),
        "type": $('#table-user').data('method')
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
            "data": 'usr_id',
            "sortable": false,
            "class": "text-center",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            "data": "usr_nama"
        }, {
            "data": "usr_username"
        }, {
            "data": "usr_otoritas"
        }, {
            "data": "usr_id",
            "sortable": false,
            "render": function (data, type, row, meta) {
                let btnResetPassword = `<button class="btn btn-warning btn-sm mb-1 mr-1" data-url="${base_url() +
                    'user/' + data + '/reset-password'}"
                    data-method="post" id="btnResetPassword-${data}" onclick="return btnResetPassword('${data}')"
                    data-btn="<i class='fas fa-sync mr-1'></i>Reset Password"
                    ${ row.prm_reset_pass == false ? 'disabled' : '' }><i class="fas fa-sync mr-1"></i>Reset Password</button>`

                let btnPermission = `<button class="btn btn-dark btn-sm mb-1 mr-1" data-url="${base_url() +
                    'user/' + data + '/modal-permission'}"
                    data-method="post" id="btnPermission-${data}" onclick="return viewModal('btnPermission-${data}' , false)"
                    data-btn="<i class='fas fa-user-tag'></i>"
                    ${ row.prm_edit_permission == false ? 'disabled' : '' }><i class="fas fa-user-tag"></i></button>`

                let btnEdit = `<button class="btn btn-success btn-sm mb-1 mr-1" data-url="${base_url() +
                    'user/' + data + '/modal-edit'}"
                    data-method="post" id="btnEdit-${data}" onclick="return viewModal('btnEdit-${data}' , false)"
                    data-btn="<i class='fas fa-edit'></i>"
                    ${ row.prm_edit == false ? 'disabled' : '' }><i class="fas fa-edit"></i></button>`

                let btnDelete = `<button class="btn btn-danger btn-sm mb-1 mr-1" data-url="${base_url() +
                    'user/' + data + '/delete'}"
                    data-method="delete" id="btnDelete-${data}" onclick="return btnDelete('${data}')"
                    data-btn="<i class='fas fa-trash'></i>"
                    ${ row.prm_delete == false ? 'disabled' : '' }><i class="fas fa-trash"></i></button>`

                return  btnResetPassword + btnPermission + btnEdit + btnDelete;
            }
        }
    ]
});
function reloadDatatables() {
    tabel
        .ajax
        .reload();
}



function btnDelete(id) {
    Swal
        .fire({
            text: "Apakah anda yakin akan menghapus user ini",
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
                        reloadDatatables();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            }

        });
}

function btnResetPassword(id) {
    Swal
        .fire({
            text: "Apakah anda yakin akan mereset password user ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#001F3F',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Reset Password!'
        })
        .then((result) => {
            if (result.isConfirmed) {
                let btn = $('#btnResetPassword-' + id);
                $.ajax({
                    url: btn.data('url'),
                    type: btn.data('method'),
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Mereset password...',
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
                        reloadDatatables();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            }

        });
}
