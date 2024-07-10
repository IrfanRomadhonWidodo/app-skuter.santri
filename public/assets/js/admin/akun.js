$('#btnTambah').on('click', function () {
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
            btn.html('<i class="fas fa-plus mr-1"></i>Tambah');
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

function btnEdit(id)
{
    let btn = $('#btnEdit-'+id);

    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        beforeSend: function () {
            Swal.fire({
                html: 'Loading...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        },
        complete: function () {
            Swal.close()
        },
        success: function (response) {
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

function btnDetail(id)
{
    let btn = $('#btnDetail-'+id);

    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        beforeSend: function () {
            Swal.fire({
                html: 'Loading...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        },
        complete: function () {
            Swal.close()
        },
        success: function (response) {
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

function btnDelete(id)
{
    Swal
    .fire({
        text: "Apakah anda yakin akan menghapus akun ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#001F3F',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, Hapus!'
    })
    .then((result) => {
        if (result.isConfirmed) {
            let btn = $('#btnDelete-'+id);
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

                    if(resp['error'])
                    {
                        Swal
                            .fire({icon: 'error', text: resp['error']['msg']
                            })

                            return false;
                    }

                    if (resp['success']) {
                        Swal
                            .fire({icon: 'success', text: resp['success']['msg']
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

function btnResetPassword(id)
{
    Swal
    .fire({
        text: "Apakah anda yakin akan mereset password akun ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#001F3F',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, Reset Password!'
    })
    .then((result) => {
        if (result.isConfirmed) {
            let btn = $('#btnResetPassword-'+id);
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
                    if (resp['success']) {
                        Swal
                            .fire({icon: 'success', text: resp['success']['msg']
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
tabel = $('#table-akun').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "paging": true,
    "ajax": {
        "url": $('#table-akun').data('url'),
        "type": $('#table-akun').data('method'),
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
                return row.nama
            }
        }, {
            "render": function (data, type, row) {
                return row.username
            }
        }, {
            "render": function (data, type, row) {
                return row.email
            }
        }, {
            "render": function (data, type, row) {
                return row.foto
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
            "targets": [5]
        }
    ]
});

function reloadDatatables() {
    tabel
        .ajax
        .reload();
}
