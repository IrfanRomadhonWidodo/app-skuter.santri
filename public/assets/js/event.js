$('.form-control').on('input change', function () {
    $(this).removeClass('is-invalid');
})

$('#form-save').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        processData: false,
        contentType: false,
        chace: false,
        data: formData,
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
            console.log(response);
            let resp = JSON.parse(response);

            if (resp['error']) {
                if (resp['error']['nama_event']) {
                    $('#nama_event').addClass('is-invalid');
                    $('.errornama_event').html(resp['error']['nama_event']);
                } else {
                    $('#nama_event').removeClass('is-invalid');
                }
                if (resp['error']['singkatan_nama']) {
                    $('#singkatan_nama').addClass('is-invalid');
                    $('.errorsingkatan_nama').html(resp['error']['singkatan_nama']);
                } else {
                    $('#singkatan_nama').removeClass('is-invalid');
                }
                if (resp['error']['deskripsi_event']) {
                    $('#deskripsi_event').addClass('is-invalid');
                    $('.errordeskripsi_event').html(resp['error']['deskripsi_event']);
                } else {
                    $('#deskripsi_event').removeClass('is-invalid');
                }
                if (resp['error']['greeting']) {
                    $('#greeting').addClass('is-invalid');
                    $('.errorgreeting').html(resp['error']['greeting']);
                } else {
                    $('#greeting').removeClass('is-invalid');
                }
                if (resp['error']['logo_event']) {
                    $('#logo_event').addClass('is-invalid');
                    $('.errorlogo_event').html(resp['error']['logo_event']);
                } else {
                    $('#logo_event').removeClass('is-invalid');
                }
                Swal.close();
                return false;
            }

            if (resp['success']) {
                Swal.fire(
                    {icon: 'success', title: 'Berhasil', text: resp['success']['msg'], timer: 1800, showConfirmButton: false}
                )

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
})