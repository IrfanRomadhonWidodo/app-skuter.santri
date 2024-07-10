$('#btnReset').on('click', function () {
    Swal
    .fire({
        text: "Apakah anda yakin akan mereset keranjang?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#001F3F',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, Reset'
    })
    .then((result) => {
        if (result.isConfirmed) {
            let btn = $('#btnReset');
            $.ajax({
                url: btn.data('url'),
                type: btn.data('method'),
                beforeSend: function () {
                    Swal.fire({
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
                    countCart();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });
})

$('#btnBuatPeminajaman').on('click', function () {
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



function btnDelete(id) {
    Swal
        .fire({
            text: "Apakah anda yakin akan menghapus barang ini dari keranjang?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#001F3F',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Hapus'
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
                        countCart();
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
tabel = $('#table-keranjang-peminjaman').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "paging": false,
    "info":false,
    "searching": false,
    "ajax": {
        "url": $('#table-keranjang-peminjaman').data('url'),
        "type": $('#table-keranjang-peminjaman').data('method')
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
                return row.item
            },
        }, {
            "render": function (data, type, row) {
                return row.price
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
    ],
    'footerCallback': function (tfoot, data, start, end, display) {
        var response = this
            .api()
            .ajax
            .json();
        console.log(response);
        if (response) {
            if(response['total_data'] == 0)
            {
                $('#btnBuatPeminajaman').attr('disabled', 'disabled');
                $('#btnReset').attr('disabled', 'disabled');
                $('#total').html(response['total']);
            }else{
                $('#btnBuatPeminajaman').removeAttr('disabled');
                $('#btnReset').removeAttr('disabled');
                $('#total').html(response['total']);
            }
        }
    },
});

function reloadDatatables() {
    tabel
        .ajax
        .reload();
}
