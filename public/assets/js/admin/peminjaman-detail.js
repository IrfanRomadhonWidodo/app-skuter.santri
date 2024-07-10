var petik = "'";
var tabel = null;
tabel = $('#table-peminjaman-barang-detail').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "info": false,
    "ajax": {
        "url": $('#table-peminjaman-barang-detail').data('url'),
        "type": $('#table-peminjaman-barang-detail').data('method')
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
                return row.status
            }
        }, {
            "render": function (data, type, row) {
                return row.aksi
            }

        }, {
            "render": function (data, type, row) {
                if (row.status_barang == '' || row.status_barang == null) {
                    return `<div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input check-item" id="check-${row.peminjaman_detail_id}" 
                    value="${row.peminjaman_detail_id}"
                    onclick="return checkItem('${row.peminjaman_detail_id}')">
                    <label class="custom-control-label" for="check-${row.peminjaman_detail_id}">Pilih</label></div>`
                }else if(row.status_barang == 'diterima' && row.keperluan == 'pribadi' && row.pembayaran == "Lunas"){
                    return `<div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input check-item" id="check-${row.peminjaman_detail_id}" 
                    value="${row.peminjaman_detail_id}"
                    onclick="return checkItem('${row.peminjaman_detail_id}')">
                    <label class="custom-control-label" for="check-${row.peminjaman_detail_id}">Pilih</label></div>`
                }else if(row.status_barang == 'diterima' && row.keperluan == 'praktik'){
                    return `<div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input check-item" id="check-${row.peminjaman_detail_id}" 
                    value="${row.peminjaman_detail_id}"
                    onclick="return checkItem('${row.peminjaman_detail_id}')">
                    <label class="custom-control-label" for="check-${row.peminjaman_detail_id}">Pilih</label></div>`
                }else{
                    return `<div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input check-item" id="check-${row.peminjaman_detail_id}" 
                    value="${row.peminjaman_detail_id}"
                    disabled
                    onclick="return checkItem('${row.peminjaman_detail_id}')">
                    <label class="custom-control-label" for="check-${row.peminjaman_detail_id}">Pilih</label></div>`
                }
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
            $('#total-tagihan').html(response['total_tagihan']);
        }
    }
});

$('#check-semua').on('change', function () {
    if ($(this).is(':checked')) {
        $('.check-item:not(:disabled)').prop('checked', true);
    } else {
        $('.check-item:not(:disabled)').prop('checked', false);
    }

    countAllCheckboxItem();
})

function countAllCheckboxItem() {
    let total = $('.check-item').length;
    let checked = $('.check-item:checked').length;
    if (total == checked) {
        $('#check-semua').prop('checked', true);
    } else {
        $('#check-semua').prop('checked', false);
    }

    if (checked == 0) {
        $('#btnKonfirmasiMasal').attr('disabled', 'disabled');
        $('#btnBarangKeluarSelected').attr('disabled', 'disabled');
        $('#count-selected').html('');
    } else {
        $('#btnKonfirmasiMasal').removeAttr('disabled');
        $('#btnBarangKeluarSelected').removeAttr('disabled');
        $('#count-selected').html(checked + ' item');
    }
}

function checkItem(id) {
    countAllCheckboxItem();
}

function reloadDatatables() {
    tabel
        .ajax
        .reload();
}

function btnKonfirmasi(id) {
    let btn = $('#btnKonfirmasi-' + id);
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

$('#btnKonfirmasiMasal').on('click', function () {
    let btn = $(this);
    btnHtml = btn.html();
    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        data: {
            "peminjaman_detail_id": $('.check-item:checked')
                .map(function () {
                    return $(this).val()
                })
                .get()
        },
        beforeSend: function () {
            btn.attr('disabled', 'disabled');
            btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');
        },
        complete: function () {
            btn.removeAttr('disabled');
            btn.html(btnHtml);
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
$('#btnBarangKeluarSelected').on('click', function () {
    let btnHtml = $(this).html();
    let btn = $(this);
    btnHtml = btn.html();
    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        data: {
            "peminjaman_detail_id": $('.check-item:checked')
                .map(function () {
                    return $(this).val()
                })
                .get()
        },
        beforeSend: function () {
            btn.attr('disabled', 'disabled');
            btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');
        },
        complete: function () {
            btn.removeAttr('disabled');
            btn.html(btnHtml);
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
$('#btnKonfirmasiBuktiPembayaran').on('click', function () {
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
$('#btnBuktiPembayaran').on('click', function () {
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

$('#btnSuratPeminjaman').on('click', function () {
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

$('#btnDownloadInvoice').on('click', function () {
    let btn = $(this);
    $('#form-generate-invoice').submit();
    btn.attr('disabled', 'disabled');
    btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');

    setTimeout(() => {
        btn.removeAttr('disabled');
        btn.html(btn.data('btn'));
    }, 1000);
})
function btnPengambilan(id) {
    let btn = $('#btnPengambilan-' + id);
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
function btnPengembalian(id) {
    let btn = $('#btnPengembalian-' + id);
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