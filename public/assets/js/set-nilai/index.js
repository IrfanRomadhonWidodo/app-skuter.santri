let dataAjax = {

}

table = null;
table = $('#table-setNilai').DataTable({
    "processing": true, "serverSide": true, "ordering": true,
    "scrollX": true,
    "ajax": {
        "url": $('#table-setNilai').data('url'),
        "type": $('#table-setNilai').data('method'),
        "data": function(d){
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
            "data": "stn_id",
            "sortable": false,
            "class": "text-center",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            "data": "stn_id",
            "class": "text-nowrap",
            "sortable": false,
            "render": function (data, type, row, meta) {
                let btnEdit = `<button class="btn btn-success btn-sm mb-1 mr-1" data-url="${base_url() + 's' +
                        'et-nilai/' + data + '/modal-edit'}"
                    data-method="post" id="btnEdit-${data.replace(
                    /\./g,
                    ""
                )}" onclick="return viewModal('btnEdit-${data.replace(/\./g, "")}' , false)"
                    data-btn="<i class='fas fa-edit'></i>"
                    ><i class="fas fa-edit"></i></button>`

                let btnDelete = `<button class="btn btn-danger btn-sm mb-1 mr-1" data-url="${base_url() + 'set-nilai/' +
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
            "render": function(data, type, row) {
                if (row.stn_dari == 0) {
                    return '< ' + row.stn_sampai;
                } else {
                    return row.stn_dari + ' ≤ ' + row.stn_sampai;
                }
            }
        }, {
            "data": "stn_nilai_huruf",
        }, {
            "data": "stn_bobot",
        }
    ],
});

function reloadDatatables() {
    table
        .ajax
        .reload();
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
