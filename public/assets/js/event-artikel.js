tabel = null;
tabel = $('#table-artikel').DataTable({
    "processing": true, "serverSide": true, "ordering": true,
    "scrollX": true,
    "order": [
        [0, 'asc']
    ],
    "ajax": {
        "url": $('#table-artikel').data('url'),
        "type": $('#table-artikel').data('method')
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
            "data": 'evt_art_id',
            "sortable": false,
            "class": "text-center",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            "data": "evt_art_judul",
        }, {
            "data": "evt_art_konten",
            "render": function (data, type, row) {
                return `<div class="teks-sembunyi">${data}</div>`
            }
        }, {
            "data": "evt_art_gambar",
            "render": function (data, type, row) {
                return `<img src="${base_url()+'file?file='+ data}" width="100px">`
            }
        }, {
            "data": "evt_art_aktif",
            "render": function (data, type, row) {
                return `<div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" 
                data-url="${base_url() +
                        'event/artikel/' + row.evt_art_id + '/change-status'}" 
                data-method="post" ${data == 1
                    ? 'checked'
                    : ''} 
                id="switch-${row.evt_art_id}" onchange="return changeStatus('${row.evt_art_id}')"
                ${row.prm_edit == false ? 'disabled' : ''}>
                <label class="custom-control-label" for="switch-${row.evt_art_id}">Aktif</label>
              </div>`;
            }
        }, {
            "data": "evt_art_id",
            "class": "text-nowrap",
            "sortable": false,
            "render": function (data, type, row, meta) {
                let btnEdit = `<button class="btn btn-success btn-sm mb-1 mr-1" data-url="${base_url() +
                    'event/artikel/' + data + '/edit'}" id="btnEdit-${data}" onclick="return movePage('btnEdit-${data}' , false)"
                    data-btn="<i class='fas fa-edit'></i>" ${row.prm_edit == false ? 'disabled' : ''}
                    ><i class="fas fa-edit"></i></button>`

                let btnDelete = `<button class="btn btn-danger btn-sm mb-1 mr-1" data-url="${base_url() +
                    'event/artikel/' + data + '/delete'}"
                    data-method="delete" id="btnDelete-${data}" onclick="return btnDelete('${data}')"
                    data-btn="<i class='fas fa-trash'></i>" ${row.prm_delete == false ? 'disabled' : ''}
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


function changeStatus(prm_id) {
    let switchButton = $('#switch-' + prm_id);
    $.ajax({
        url: switchButton.data('url'),
        type: switchButton.data('method'),
        success: function (result) {
            // console.log(result);
            resp = JSON.parse(result);

            if (resp['success']) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                      toast.onmouseenter = Swal.stopTimer;
                      toast.onmouseleave = Swal.resumeTimer;
                    }
                  });
                  Toast.fire({
                    icon: "success",
                    title: resp['success']['msg']
                  });
            }
            reloadDatatables();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}


function btnDelete(id) {
    Swal
        .fire({
            text: "Apakah anda yakin akan menghapus artikel ini?",
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
