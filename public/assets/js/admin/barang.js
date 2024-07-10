function btnDelete(id) {
    Swal
        .fire({
            text: "Apakah anda yakin akan menghapus barang ini?",
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
                            Swal.fire({icon: 'error', text: resp['error']['msg']
                            })

                            return false;
                        }

                        if (resp['success']) {
                            Swal.fire({icon: 'success', text: resp['success']['msg']
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

function btnDetail(id)
{
    let btn = $('#btnDetail-'+id);
    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        beforeSend: function () {
           btn.attr('disabled', 'disabled');
           btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');
        },
        complete: function () {
            btn.removeAttr('disabled');
            btn.html('<i class="fas fa-eye"></i>');
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

function btnQR(id)
{
    let btn = $('#btnQR-'+id);
    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        beforeSend: function () {
           btn.attr('disabled', 'disabled');
           btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');
        },
        complete: function () {
            btn.removeAttr('disabled');
            btn.html('<i class="fas fa-qrcode"></i>');
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

$('#btnGenerateQR').on('click', function () {
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

var petik = "'";
var tabel = null;
tabel = $('#table-barang').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "paging": true,
    "ajax": {
        "url": $('#table-barang').data('url'),
        "type": $('#table-barang').data('method')
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
                return row.kode
            }
        }, {
            "render": function (data, type, row) {
                return row.harga
            }
        }, {
            "render": function (data, type, row) {
                return row.kategori
            }
        }, {
            "render": function (data, type, row) {
                return row.status
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
            "targets": [6]
        }
    ]
});

function reloadDatatables() {
    tabel
        .ajax
        .reload();
}

var myEditor;
ClassicEditor
    .create(document.querySelector('#deskripsi'))
    .then(editor => {
        console.log('Editor was initialized', editor);
        myEditor = editor;
    })
    .catch(error => {
        console.error(error);
    });

$('#btnSimpan').on('click', function () {

    $('#form-save').submit();
    let btn = $(this);

    btn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Loading');
    btn.attr('disabled', 'disabled');

    setTimeout(() => {
        btn.html('<i class="fas fa-save mr-2"></i>Simpan');
        btn.removeAttr('disabled');
    }, 1000);
})

$('#form-save').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('deskripsi', myEditor.getData());
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        chace: false,
        beforeSend: function () {
            Swal.fire({
                html: 'Menyimpan...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        },
        success: function (response) {
            let resp = JSON.parse(response);
            console.log(resp);

            if (resp['error']) {
                if (resp['error']['nama']) {
                    $('#nama').addClass('is-invalid');
                    $('.errornama').html(resp['error']['nama']);
                } else {
                    $('#nama').removeClass('is-invalid');
                }
                if (resp['error']['kode']) {
                    $('#kode').addClass('is-invalid');
                    $('.errorkode').html(resp['error']['kode']);
                } else {
                    $('#kode').removeClass('is-invalid');
                }
                if (resp['error']['deskripsi']) {
                    $('#deskripsi').addClass('is-invalid');
                    $('.errordeskripsi').html(resp['error']['deskripsi']);
                } else {
                    $('#deskripsi').removeClass('is-invalid');
                }
                if (resp['error']['harga']) {
                    $('#harga').addClass('is-invalid');
                    $('.errorharga').html(resp['error']['harga']);
                } else {
                    $('#harga').removeClass('is-invalid');
                }
                if (resp['error']['foto']) {
                    $('#foto').addClass('is-invalid');
                    $('.errorfoto').html(resp['error']['foto']);
                } else {
                    $('#foto').removeClass('is-invalid');
                }
                Swal.close();
                return false;
            }

            if (resp['success']) {
                Swal
                    .fire(
                        {icon: 'success', title: 'Berhasil', text: resp['success']['msg'], timer: 1800, showConfirmButton: false}
                    )
                    .then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            window.location.href = resp['success']['redirect'];
                        } else {
                            window.location.href = resp['success']['redirect'];
                        }
                    });
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
})

$('#btnSimpanPerubahan').on('click', function () {

    $('#form-update').submit();
    let btn = $(this);

    btn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Loading');
    btn.attr('disabled', 'disabled');

    setTimeout(() => {
        btn.html('<i class="fas fa-save mr-2"></i>Simpan Perubahan');
        btn.removeAttr('disabled');
    }, 1000);
})


$('#form-update').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('deskripsi', myEditor.getData());
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        chace: false,
        beforeSend: function () {
            Swal.fire({
                html: 'Menyimpan...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        },
        success: function (response) {
            let resp = JSON.parse(response);
            console.log(resp);

            if (resp['error']) {
                if (resp['error']['nama']) {
                    $('#nama').addClass('is-invalid');
                    $('.errornama').html(resp['error']['nama']);
                } else {
                    $('#nama').removeClass('is-invalid');
                }
                if (resp['error']['kode']) {
                    $('#kode').addClass('is-invalid');
                    $('.errorkode').html(resp['error']['kode']);
                } else {
                    $('#kode').removeClass('is-invalid');
                }
                if (resp['error']['deskripsi']) {
                    $('#deskripsi').addClass('is-invalid');
                    $('.errordeskripsi').html(resp['error']['deskripsi']);
                } else {
                    $('#deskripsi').removeClass('is-invalid');
                }
                if (resp['error']['harga']) {
                    $('#harga').addClass('is-invalid');
                    $('.errorharga').html(resp['error']['harga']);
                } else {
                    $('#harga').removeClass('is-invalid');
                }
                if (resp['error']['foto']) {
                    $('#foto').addClass('is-invalid');
                    $('.errorfoto').html(resp['error']['foto']);
                } else {
                    $('#foto').removeClass('is-invalid');
                }
                Swal.close();
                return false;
            }

            if (resp['success']) {
                Swal
                    .fire(
                        {icon: 'success', title: 'Berhasil', text: resp['success']['msg'], timer: 1800, showConfirmButton: false}
                    )
                    .then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        location.reload()
                    });
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
})