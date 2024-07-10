

function btnBatal(id) {
    Swal
        .fire({
            text: "Apakah anda yakin akan membatalkan peminjaman ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#001F3F',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Batalkan Peminjaman'
        })
        .then((result) => {
            if (result.isConfirmed) {
                let btn = $('#btnBatal-' + id);
                $.ajax({
                    url: btn.data('url'),
                    type: btn.data('method'),
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Membatalkan...',
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
tabel = $('#table-peminjaman-barang').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "ajax": {
        "url": $('#table-peminjaman-barang').data('url'),
        "type": $('#table-peminjaman-barang').data('method'),
        "data": {
            'status':  $('#table-peminjaman-barang').data('status'),
        },
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
                return row.peminjam
            },
        }, {
            "render": function (data, type, row) {
                return row.barang
            }
        }, {
            "render": function (data, type, row) {
                return row.tanggal
            }
        }, {
            "render": function (data, type, row) {
                return row.status
            }
        }, {
            "render": function (data, type, row) {
                return row.aksi
            }
        }
    ],
});

function reloadDatatables() {
    tabel
        .ajax
        .reload();
}
