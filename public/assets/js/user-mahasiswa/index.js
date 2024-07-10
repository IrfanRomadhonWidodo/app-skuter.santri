let dataAjax = {}

table = null;
table = $('#table-mahasiswa').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": true,
    "scrollX": true,
    "ajax": {
        "url": $('#table-mahasiswa').data('url'),
        "type": $('#table-mahasiswa').data('method'),
        "data": function (d) {
            return $.extend(d, dataAjax);
        },
        "dataSrc": function (json) {
            return json.data;
        }
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
            "data": "usr_id",
            "sortable": false,
            "class": "text-center",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            "data": "usr_id",
            "class": "text-nowrap",
            "sortable": false,
            "render": function (data, type, row, meta) {
                let btnResetPassword = `<button class="btn btn-warning btn-sm mb-1 mr-1" data-url="${base_url() +
                    'user/' + data + '/reset-password'}"
                    data-method="post" id="btnResetPassword-${data}" onclick="return btnResetPassword('${data}')"
                    data-btn="<i class='fas fa-sync mr-1'></i>Reset Password"><i class="fas fa-sync mr-1"></i>Reset Password</button>`

                let btnEdit = `<button class="btn btn-success btn-sm mb-1 mr-1" 
                    data-url="${base_url() +'mahasiswa/' + data + '/e'}" 
                    id="btnEdit-${data.replace(/\./g,"")}" onclick="return movePage('btnEdit-${data.replace(/\./g, "")}' , false)"
                    data-btn="<i class='fas fa-edit'></i>"
                    ><i class="fas fa-edit"></i></button>`

                let btnDelete = `<button class="btn btn-danger btn-sm mb-1 mr-1" data-url="${base_url() + 'periode-akademik/' +
                        data + '/delete'}"
                    data-method="delete" id="btnDelete-${data.replace(
                    /\./g,
                    ""
                )}" onclick="return btnDelete('${data.replace(/\./g, "")}')"
                    data-btn="<i class='fas fa-trash'></i>"
                    ><i class="fas fa-trash"></i></button>`

                return btnResetPassword + btnEdit + btnDelete;
            }
        }, {
            "data": "usr_id"
        }, {
            "data": "usr_nama",
            "class": "text-nowrap",
        }, {
            "data": "prodi_nama"
        }, {
            "data": "fk_nama"
        }, {
            "data": "usr_mhs_angkatan"
        }, {
            "data": "usr_status"
        }
    ]
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
            prd_akd_aktif: status
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
    if($('#prd_akd_aktif-' + id).is(':checked'))
    {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Tidak bisa menghapus data periode akademik aktif!'
        })
        return false
    }
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
