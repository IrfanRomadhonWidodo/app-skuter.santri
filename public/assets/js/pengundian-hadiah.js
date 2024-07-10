let dataAjax = {
    win_date: $('#win_date').val(),
    win_confirm: $('#win_confirm').val(),
    win_hdh_bulan: $('#win_hdh_bulan').val()
};

table = null;
tabel = $('#table-pemenang').DataTable({
    "processing": true, "serverSide": true, "ordering": true,
    "scrollX": true,
    "order": [
        [0, 'desc']
    ],
    "ajax": {
        "url": $('#table-pemenang').data('url'),
        "type": $('#table-pemenang').data('method'),
        "data": function(d){
            return $.extend(d, dataAjax);
        },
    },
    "deferRender": true,
    "aLengthMenu": [
        [
            20, 50, 100
        ],
        [
            20, 50, 100
        ]
    ],
    "columns": [
        {
            "data": 'win_id',
            "sortable": false,
            "class": "text-center vertical-middle",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            "data": "cust_nama",
            "class": "vertical-middle",
            "render" : function (data, type, row, meta) {
                return data + '<br>' + '<small>' + row.cust_id + ' ' + row.cust_nomor_hp + '</small>';
            }
        }, {
            "data": "win_hdh_gambar",
            "class": "vertical-middle",
            "render": function (data, type, row) {
                return `<img src="${base_url()+'file?file='+ data}" width="50px" height="50px">` + row.win_hdh_nama
            }
        }, {
            "data": "win_hdh_bulan",
            "class": "vertical-middle",
        }, {
            "data": "win_date",
            "class": "vertical-middle",
        }, {
            "data": "win_confirm",
            "class": "vertical-middle",
            "render": function (data, type, row) {
                console.log(data);
                if(data == null || data == ''){ 
                    return '<span class="badge badge-warning">Menunggu Konfirmasi</span>'
                };
                return `<span class="badge badge-${data == '1' ? 'success' : 'danger'}">${data == '1' ? 'Valid' : 'Tidak Valid'}</span>`
            }
        }, {
            "data": "win_id",
            "sortable": false,
            "class": "vertical-middle text-nowrap",
            "render": function (data, type, row, meta) {

                let btnConfirm = `<button class="btn btn-primary btn-sm mb-1 mr-1 text-nowrap" data-url="${base_url() +
                    'pengundian-hadiah/' + data + '/validasi'}"
                    data-method="post" id="btnConfirm-${data}" onclick="return btnConfirm('${data}')"
                    data-btn="<i class='fas fa-check-square mr-1'></i>"
                    ><i class="fas fa-check-square mr-1"></i>Konfirmasi</button>`
                let btnLock = `<button class="btn btn-dark btn-sm"><i class="fas fa-lock"></i></button>`
                if(row.win_confirm != null || row.win_confirm == ''){
                    return btnLock
                }
                return btnConfirm;
            }
        }
    ]
});
function reloadDatatables() {
    dataAjax.win_date = $('#win_date').val();
    dataAjax.win_confirm = $('#win_confirm').val();
    dataAjax.win_hdh_bulan = $('#win_hdh_bulan').val();
    tabel
        .ajax
        .reload();
}


$('#btnReset').on('click', function () {
    $('#win_date').val('');
    $('#win_confirm').val('');
    $('#win_hdh_bulan').val('');
    reloadDatatables();
});


function btnConfirm(id) {
    Swal
        .fire({
            text: "Apakah anda yakin peserta merupakan pemenang hadiah ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#001F3F',
            cancelButtonColor: '#353535',
            cancelButtonText: 'Batal',
            denyButtonText: 'Tidak Valid',
            showDenyButton: true,
            confirmButtonText: 'Valid'
        })
        .then((result) => {
            if (result.isConfirmed) {
                let btn = $('#btnConfirm-' + id);
                $.ajax({
                    url: btn.data('url'),
                    type: btn.data('method'),
                    data:{
                        valid : 1
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
            }else if(result.isDenied){
                let btn = $('#btnConfirm-' + id);
                $.ajax({
                     url: btn.data('url'),
                    type: btn.data('method'),
                    data:{
                        valid : 0
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
                })
            }

        });
}
