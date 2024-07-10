

var petik = "'";
var tabel = null;
tabel = $('#table-peminjaman-barang-detail').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "ajax": {
        "url": $('#table-peminjaman-barang-detail').data('url'),
        "type": $('#table-peminjaman-barang-detail').data('method'),
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
            },
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
                return row.status
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
    ],
    'footerCallback': function (tfoot, data, start, end, display) {
        var response = this
            .api()
            .ajax
            .json();
        console.log(response);
        if (response) {
            $('#total-tagihan').html(response['total_tagihan'] +' x ' + response['total_hari']);
            $('#grand-total').html(response['grand_total']);
        }
    },
});

function reloadDatatables() {
    tabel
        .ajax
        .reload();
}



$('#btnSuratPeminjaman').on('click', function(){
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

$('#btnBayar').on('click', function(){
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

$('#btnDownloadInvoice').on('click', function(){
    let btn = $(this);
    $('#form-generate-invoice').submit();
    btn.attr('disabled', 'disabled');
    btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');
    
    setTimeout(() => {
        btn.removeAttr('disabled');
        btn.html(btn.data('btn'));
    }, 1000);
})

function btnDetailBatal(id)
{
    Swal
    .fire({
        text: "Apakah anda yakin akan membatalkan peminjaman untuk barang ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#001F3F',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, Batalkan'
    })
    .then((result) => {
        if (result.isConfirmed) {
            let btn = $('#btnDetailBatal-' + id);
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

                    if(resp['success']['redirect'])
                    {
                        setTimeout(() => {
                            window.location.href = resp['success']['redirect'];
                        }, 1000);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }

    });
}