$('#btnTambahFakultas').on('click', function () {
    let btn = $(this);
    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        beforeSend: function () {
           btn.attr('disabled', 'disabled');
           btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');
        },
        complete: function () {
            btn.removeAttr('disabled');
            btn.html(btn.data('btn'));
        },
        success: function (response) {
            console.log(response);
            let resp = JSON.parse(response);
            if (resp['modal']) {
                $('.viewmodal').html(resp['modal']['view']);
                $('#' + resp['modal']['id']).modal('show');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
})
$('#btnTambahProdi').on('click', function () {
    let btn = $(this);
    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        beforeSend: function () {
           btn.attr('disabled', 'disabled');
           btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');
        },
        complete: function () {
            btn.removeAttr('disabled');
            btn.html(btn.data('btn'));
        },
        success: function (response) {
            console.log(response);
            let resp = JSON.parse(response);
            if (resp['modal']) {
                $('.viewmodal').html(resp['modal']['view']);
                $('#' + resp['modal']['id']).modal('show');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
})

function btnEdit(type,  id)
{
    switch(type)
    {
        case 'fakultas' :
            var btn = $('#btnEditFakultas-'+id);
        break;
        case 'prodi' :
            var btn = $('#btnEditProdi-'+id);
        break;
    }
    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        beforeSend: function () {
           btn.attr('disabled', 'disabled');
           btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');
        },
        complete: function () {
            btn.removeAttr('disabled');
            btn.html(btn.data('btn'));
        },
        success: function (response) {
            console.log(response);
            let resp = JSON.parse(response);
            if (resp['modal']) {
                $('.viewmodal').html(resp['modal']['view']);
                $('#' + resp['modal']['id']).modal('show');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function btnDelete(type, id) {
    Swal
        .fire({
            text: "Apakah anda yakin akan menghapus "+type+" ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#001F3F',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Hapus'
        })
        .then((result) => {
            if (result.isConfirmed) {
                 switch(type)
                    {
                        case 'fakultas' :
                            var btn = $('#btnDeleteFakultas-'+id);
                        break;
                        case 'prodi' :
                            var btn = $('#btnDeleteProdi-'+id);
                        break;
                    }
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

var petik = "'";
var tabel = null;
tabel = $('#table-fakultas').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "paging": false,
    "info": false,
    "ajax": {
        "url": $('#table-fakultas').data('url'),
        "type": $('#table-fakultas').data('method')
    },
    "deferRender": true,
    "aLengthMenu": [
        [
            20, 10, 50, 100
        ],
        [
            20, 10, 50, 100
        ]
    ],
    "columns": [
        {
            "render": function (data, type, row) {
                return row.no
            }
        }, {
            "render": function (data, type, row) {
                return row.nama_fakultas
            }
        }, {
            "render": function (data, type, row) {
                return row.count
            }
        }, {
            "render": function (data, type, row) {
                return row.aksi
            }
        }
    ],
    "columnDefs": [
        {
            className: "text-nowrap",
            "targets": [3]
        }
    ]
});

var petik = "'";
var table_prodi = null;
table_prodi = $('#table-prodi').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "paging": false,
    "info": false,
    "ajax": {
        "url": $('#table-prodi').data('url'),
        "type": $('#table-prodi').data('method')
    },
    "deferRender": true,
    "aLengthMenu": [
        [
            20, 10, 50, 100
        ],
        [
            20, 10, 50, 100
        ]
    ],
    "columns": [
        {
            "render": function (data, type, row) {
                return row.no
            }
        }, {
            "render": function (data, type, row) {
                return row.nama_prodi
            }
        }, {
            "render": function (data, type, row) {
                return row.fakultas
            }
        }, {
            "render": function (data, type, row) {
                return row.aksi
            }
        }
    ],
    "columnDefs": [
        {
            className: "text-nowrap",
            "targets": [3]
        }
    ]
});

function reloadDatatables() {
    tabel
        .ajax
        .reload();
    table_prodi
        .ajax
        .reload();
}
