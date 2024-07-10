table = null;
tabel = $('#table-tambah-poin').DataTable({
    "processing": true, "serverSide": true, "ordering": true,
    "scrollX": true,
    "order": [
        [0, 'desc']
    ],
    "ajax": {
        "url": $('#table-tambah-poin').data('url'),
        "type": $('#table-tambah-poin').data('method'),
        "data": {
            'trs_type': 'Bonus'
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
            "data": 'trs_id',
            "sortable": false,
            "class": "text-center",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            "data": "cust_nama"
        }, {
            "data": "trs_cust_id",
        }, {
            "data": "trs_tanggal"
        }, {
            "data": "trs_poin"
        }, {
            "data": "usr_nama"
        }, {
            "data": "trs_id",
            "sortable": false,
            "render": function (data, type, row, meta) {
                let btnEdit = `<button class="btn btn-success btn-sm mb-1 mr-1" data-url="${base_url() +
                    'tambah-poin/' + data + '/modal-edit'}"
                    data-method="post" id="btnEdit-${data}" onclick="return viewModal('btnEdit-${data}' , false)"
                    data-btn="<i class='fas fa-edit'></i>"  ${ row.edit ? '' : 'disabled' }
                    ><i class="fas fa-edit"></i></button>`

                let btnDelete = `<button class="btn btn-danger btn-sm mb-1 mr-1" data-url="${base_url() +
                    'tambah-poin/' + data + '/delete'}"
                    data-method="delete" id="btnDelete-${data}" onclick="return btnDelete('${data}')"
                    data-btn="<i class='fas fa-trash'></i>" ${ row.delete ? '' : 'disabled' }
                    ><i class="fas fa-trash"></i></button>`
                return   btnEdit + btnDelete;
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
            text: "Apakah anda yakin akan menghapus tambah-poin ini?",
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
