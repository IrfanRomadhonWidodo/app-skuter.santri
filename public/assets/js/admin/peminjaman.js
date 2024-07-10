

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
    'footerCallback': function (tfoot, data, start, end, display) {
        var response = this
            .api()
            .ajax
            .json();
        console.log(response);
        if (response) {
            $('#total').html(response['total']);
        }
    },
});

function reloadDatatables() {
    tabel
        .ajax
        .reload();
}
