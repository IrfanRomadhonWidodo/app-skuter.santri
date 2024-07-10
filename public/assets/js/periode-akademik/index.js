let dataAjax = {}

table = null;
table = $('#table-periodeAkademik').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": true,
    "scrollX": true,
    "ajax": {
        "url": $('#table-periodeAkademik').data('url'),
        "type": $('#table-periodeAkademik').data('method'),
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
            "data": "prd_akd_id",
            "sortable": false,
            "class": "text-center",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            "data": "prd_akd_id",
            "class": "text-nowrap",
            "sortable": false,
            "render": function (data, type, row, meta) {
                let btnEdit = `<button class="btn btn-success btn-sm mb-1 mr-1" data-url="${base_url() + 'p' +
                        'eriode-akademik/' + data + '/modal-edit'}"
                    data-method="post" id="btnEdit-${data.replace(
                    /\./g,
                    ""
                )}" onclick="return viewModal('btnEdit-${data.replace(/\./g, "")}' , false)"
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

                return btnEdit + btnDelete;
            }
        }, {
            "data": "prd_akd_tahun"
        }, {
            "data": "prd_akd_semester",
            "render": function (data, type, row, meta) {
                if (data == 1) {
                    return "Ganjil";
                } else {
                    return "Genap";
                }
            }
        }, {
            "data": "prd_akd_expired",
            "render": function (data, type, row, meta) {
                return formatDate(data, 'DD-MM-YYYY')
            }
        }, {
            "data": "prd_akd_aktif",
            "render": function (data, type, row, meta) {
                return `<div class="custom-control custom-switch switch-aktif">
                            <input type="checkbox" class="custom-control-input" 
                            data-url="${base_url() + 'periode-akademik/' + row.prd_akd_id + '/change-status'}" 
                            data-method="post" 
                            onchange="return changeStatus('${ 'prd_akd_aktif-' +row.prd_akd_id}')" id="${ 'prd_akd_aktif-' + row.prd_akd_id}" ${data == 1? 'checked disabled': ''}>
                            <label class="custom-control-label" for="${ 'prd_akd_aktif-' +
                            row.prd_akd_id}">Aktif</label>
                        </div>`;
            }
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
