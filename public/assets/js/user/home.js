$(document).ready(function () {
    reloadPaginationWithAjax();
})

function reloadPaginationWithAjax(search = null) {

    // Make an AJAX request to fetch the new page content
    $.ajax({
        url: $('#pagination-container').data('url'),
        type: 'POST',
        data: {
            search: $('#search').val(),
            kategori: $('#barang_kategori_id').val()
        },
        beforeSend: function () {
            let html = `
                <div class="text-center p-5 font-weight-bold text-primary" style="font-size: 30px">
                    <i class="fas fa-circle-notch fa-spin mr-1"></i> Mengambil data
                </div>
            `;
            $('#data-container').html(html);
         },
        success: function (response) {
            let resp = JSON.parse(response)
            $('#pagination-container').pagination({
                dataSource: resp.items,
                pageSize: 10,
                callback: function (data, pagination) {
                    if(data.length == 0)
                    {
                        let html = `
                            <div class="text-center p-5 font-weight-bold text-primary" style="font-size: 30px">
                                Data tidak ditemukan
                            </div>
                        `;
                        $('#data-container').html(html);
                        return false;
                    }
                    var html = templating(data);
                    $('#data-container').html(html);
                }
            });
        },
        error: function (xhr, status, error) {
            // Handle the error case, if any ...
        }
    });
}

function templating(data) {
    let base_url = $('#pagination-container').data('baseurl');
    var html = `<div class="row" id="row-barang">`;
    $.each(data, function (index, item) {
        html += `
            <div class="col-md-3">
            <div class="card card-barang rounded-0">
            <div class="card-body">
                ${createImg(
            item.foto,
            base_url
        )}
                ${createFooter(item, base_url)}
            </div>
            </div>
            </div>
        `
    });
    html += `</div>`;
    return html;
}

function createImg(foto, base_url) {
    return `
    <div class="text-center img-barang border">
    <img src="${base_url}/file?file=${foto}">
    </div>
    `
}

function createFooter(item, base_url) {
    return `
    <div class="text-center">
        <h5 class="font-weight-bold excerpt">${item.nama}</h5>
        <button class="btn btn-primary btn-sm"
            id="btnDetail-${item.barang_id}"
            onclick="return btnDetail('${item.barang_id}')"
            data-url="${base_url}/modal-detail/${item.barang_id}"
            data-method="post"
            data-btn="<i class='fas fa-eye'></i>">
            <i class="fas fa-eye"></i>
        </button>
        <button class="btn btn-secondary btn-sm"
            id="btnAddCart-${item.barang_id}"
            onclick="return btnAddCart('${item.barang_id}')"
            data-url="${base_url}/keranjang-peminjaman/${item.barang_id}/add"
            data-nama="${item.nama}"
            data-method="post"
            data-btn="<i class='fas fa-cart-plus'></i>">
            <i class="fas fa-cart-plus"></i>
        </button>
    </div>
    `
}

function btnDetail(id)
{
    let btn = $('#btnDetail-'+id);
    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        beforeSend: function () {
           btn.attr('disabled', 'disabled');
           btn.html('<i class="fas fa-circle-notch fa-spin"></i>');
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

function btnAddCart(id)
{
    let btn = $('#btnAddCart-'+id);
    Swal
    .fire({
        text: "Masukan "+btn.data('nama')+" ke keranjang?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#001F3F',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, Masukan'
    })
    .then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: btn.data('url'),
                type: btn.data('method'),
                beforeSend: function () {
                    btn.attr('disabled', 'disabled');
                    btn.html('<i class="fas fa-circle-notch fa-spin"></i>');
                 },
                 complete: function () {
                     btn.removeAttr('disabled');
                     btn.html(btn.data('btn'));
                 },
                success: function (result) {
                    // console.log(result);
                    resp = JSON.parse(result);
                    if (resp['success']) {
                        Swal
                            .fire({icon: 'success', text: resp['success']['msg'], timer: 1800, showConfirmButton: false
                            })
                    }
                    reloadPaginationWithAjax();             
                    countCart();       
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
       
    });
}


$('#barang_kategori_id').on('change', function(){
    reloadPaginationWithAjax();
})

$('#search').on('input', function(){
    reloadPaginationWithAjax();
})