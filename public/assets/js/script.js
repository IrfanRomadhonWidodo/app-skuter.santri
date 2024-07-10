function convertRupiah(angka, prefix) {
    var number_string = angka
            .replace(/[^,\d]/g, "")
            .toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0]
            .substr(sisa)
            .match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa
            ? "."
            : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined
        ? rupiah + "," + split[1]
        : rupiah;
    return prefix == undefined
        ? rupiah
        : rupiah
            ? prefix + rupiah
            : "";
}

function movePage(id, loading = false) {
    let btn = $('#' + id);

    if (loading == false) {
        btn.html('<i class="fas fa-spinner fa-spin"></i>');
    } else {
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Loading');
    }
    window.location.href = btn.data('url');
    setTimeout(() => {
        btn.html(btn.data('btn'));
    }, 1000);
}

function changeLabel(id) {
    const input = document.querySelector(`#custom-file-input-${id}`);
    const inputLabel = document.querySelector(`#custom-file-label-${id}`);
    inputLabel.textContent = input
        .files[0]
        .name;
}

$('.form-control').on('input change', function () {
    $(this).removeClass('is-invalid');
})

function singlePreviewImage(id) {
    const image = document.querySelector('#' + id);
    const imageLabel = document.querySelector('.custom-file-label');
    const imagePreview = document.querySelector('.img-preview');
    imageLabel.textContent = image
        .files[0]
        .name;
    const imageFile = new FileReader();
    imageFile.readAsDataURL(image.files[0]);

    imageFile.onload = function (e) {
        imagePreview.src = e.target.result;
    }
}
function multiplePreviewImage(id) {
    const image = document.querySelector('#' + id);
    const imageLabel = document.querySelector('.custom-file-label-' + id);
    const imagePreview = document.querySelector('.img-preview-' + id);
    imageLabel.textContent = image
        .files[0]
        .name;
    const imageFile = new FileReader();
    imageFile.readAsDataURL(image.files[0]);

    imageFile.onload = function (e) {
        imagePreview.src = e.target.result;
    }
}

function viewModal(id, loading = false) {
    $('.modal-backdrop').remove();
    let csrfName = $('meta[name="csrf-name"]').attr('content');
    let csrfValue = $('meta[name="csrf-value"]').attr('content');

    let btn = $('#' + id);
    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        data: {
            [csrfName]: csrfValue
        },
        beforeSend: function () {
            btn.attr('disabled', 'disabled');
            if (loading == false) {
                btn.html('<i class="fas fa-circle-notch fa-spin"></i>');
            } else {
                btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');
            }
        },
        complete: function () {
            btn.removeAttr('disabled');
            btn.html(btn.data('btn'));
            reloadCsrf();
        },
        success: function (response) {
            console.log(response);
            let resp = JSON.parse(response);

            if (resp['error']) {
                Swal.fire({icon: 'error', title: 'Oops...', text: resp['error']['msg']
                })
            }
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

function reloadCsrf() {
    $.ajax({
        url: base_url() + 'reload-csrf',
        type: 'post',
        success: function (response) {
            let resp = JSON.parse(response);
            $('meta[name="csrf-name"]').attr('content', resp['csrf']['name']);
            $('meta[name="csrf-value"]').attr('content', resp['csrf']['value']);
        }
    });
}

function base_url() {
    return $('#base_url').data('base-url');
}

function formatDate(dateString, format = 'YYYY-MM-DD') {
    // Periksa apakah dateString valid
    if (!moment(dateString).isValid()) {
        return 'Invalid date';
    }

    // Format tanggal menggunakan moment.js
    return moment(dateString).format(format);
}

function btnBack(id) {
    let btn = $('#' + id);
    btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');
    window
        .history
        .back();
    setTimeout(() => {
        btn.html(btn.data('btn'));
    }, 1000);
}
