
var petik = "'";
var tabel = null;
tabel = $('#table_id').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "paging": true,
    "ajax": {
        "url": $('#table_id').data('url'),
        "type": $('#table_id').data('method'),
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
                return row.notifikasi
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
    "columnDefs": [
        {
            className: "text-nowrap",
            "targets": [4]
        }
    ]
});

function reloadDatatables() {
    tabel
        .ajax
        .reload();
}
