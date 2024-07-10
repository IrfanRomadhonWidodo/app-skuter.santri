
let dataAjaxProdi = {

}

tableProgramStudi = null;
tableProgramStudi = $('#table-program-studi').DataTable({
    "processing": true, "serverSide": true, "ordering": true,
    "scrollX": true,
    "ajax": {
        "url": $('#table-program-studi').data('url'),
        "type": $('#table-program-studi').data('method'),
        "data": function(d){
            return $.extend(d, dataAjaxProdi);
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
            "data": "prodi_id",
            "sortable": false,
            "class": "text-center",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            "data": "prodi_id",
            "class": "text-nowrap",
            "sortable": false,
            "render": function (data, type, row, meta) {
                let btnEdit = `<button class="btn btn-success btn-sm mb-1 mr-1" data-url="${base_url() + '' +
                        'program-studi/' + data + '/modal-edit'}"
                    data-method="post" id="btnEditProgramStudi-${data.replace(
                    /\./g,
                    ""
                )}" onclick="return viewModal('btnEditProgramStudi-${data.replace(/\./g, "")}' , false)"
                    data-btn="<i class='fas fa-edit'></i>"
                    ><i class="fas fa-edit"></i></button>`

                let btnDelete = `<button class="btn btn-danger btn-sm mb-1 mr-1" data-url="${base_url() + 'program-studi/' +
                        data + '/delete'}"
                    data-method="delete" id="btnDeleteProgramStudi-${data.replace(
                    /\./g,
                    ""
                )}" onclick="return btnDeleteProgramStudi('${data.replace(/\./g, "")}')"
                    data-btn="<i class='fas fa-trash'></i>"
                    ><i class="fas fa-trash"></i></button>`

                return btnEdit + btnDelete;
            }
        }, {
            "data": "fk_nama",
        }, {
            "data": "prodi_id",
        }, {
            "data": "prodi_nama",
        }
    ],
});

function reloadDatatablesProgramStudi() {
    tableProgramStudi
        .ajax
        .reload();
}

function btnDeleteProgramStudi(id) {

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
            let btn = $('#btnDeleteProgramStudi-' + id);
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
                        reloadDatatablesProgramStudi();
                    }
                }
            })
        }
    })
}
