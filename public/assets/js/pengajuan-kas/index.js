let dataAjax = {}

table = null;
table = $('#table-pengajuan-kas').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": true,
    "scrollX": true,
    "ajax": {
        "url": $('#table-pengajuan-kas').data('url'),
        "type": $('#table-pengajuan-kas').data('method'),
        "data": function (d) {
            return $.extend(d, dataAjax);
        },
        "dataSrc": function (json) {
            return json.data;
        }
    },
    "order": [
        [0, 'desc']
    ],
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
            "data": "kas_id",
            "sortable": false,
            "class": "text-center",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            "data": "kas_id",
            "class": "text-nowrap",
            "sortable": false,
            "render": function (data, type, row, meta) {
                let btnDetail = `<button class="btn btn-primary btn-sm mb-1 mr-1" data-url="${base_url() + 
                'pengajuan-kas/d/' + data }" id="btnDetail-${data.replace(/\./g,"")}" 
                onclick="return movePage('btnDetail-${data.replace(/\./g, "")}' , true)"
                data-btn="<i class='fas fa-search mr-1'></i>Detail">
                <i class="fas fa-search mr-1"></i>Detail</button>`

                let btnEdit = `<button class="btn btn-success btn-sm mb-1 mr-1" 
                    data-url="${base_url() + 'pengajuan-kas/' + data}"
                    id="btnEdit-${data.replace(/\./g,"")}" 
                    onclick="return movePage('btnEdit-${data.replace(/\./g, "")}' , false)"
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

                if(row['kas_status'] == '0'){
                    return btnEdit + btnDelete;
                }else if(row['kas_status'] == '1'){
                    return btnDetail + btnEdit + btnDelete;
                }
            }
        }, {
            "data": "kas_judul",
            "class": "text-nowrap",
        }, {
            "data": "kas_nomor_pengajuan"
        }, {
            "data": "unkj_nama",
            "class": "text-nowrap"
        }, {
            "data": "kas_submited_date",
            "render": function (data, type, row, meta) {
                if(data == null){
                    return '-';
                }
                return data;
            }
        }, {
            "data": "prd_tahun",
        }, {
            "data": "kas_status",
            "render": function (data, type, row, meta) {
                return statusKas(data);
            }
        }
    ]
});

function statusKas(statusCode)
{
    switch (statusCode) {
        case '0':
            return '<span class="badge badge-dark">Draft</span>';
            break;
        case '1':
            return '<span class="badge badge-success">Submited</span>';
            break;
        case '2':
            return '<span class="badge badge-secondary>Canceled</span>';
            break;
        default:
            return 'Unknown';
            break;
    }
}

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


$('#btnTambah').on('click', function () {
    let btn = $(this);
    
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Ingin membuat pengajuan kas baru ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#186429',
        cancelButtonColor: '#353535',
        confirmButtonText: 'Ya, Buat Pengajuan'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: btn.data('url'),
                type: btn.data('method'),
                beforeSend: function () {
                    Swal.fire({
                        html: 'Memproses...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                },
                complete: function () {
                    Swal.close();
                },
                success: function (data) {
                    let resp = JSON.parse(data);
                    if(resp['error']) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Mohon maaf...',
                            text: resp['error']['msg']
                        })
                        return false;
                    }
                    if(resp['success']) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: resp['success']['msg'],
                            timer: 1800,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = resp['success']['redirect'];
                        })
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        }
    })
})