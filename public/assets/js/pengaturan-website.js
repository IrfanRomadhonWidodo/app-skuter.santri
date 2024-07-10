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
                if (resp['error']['nama_perusahaan']) {
                    $('#nama_perusahaan').addClass('is-invalid');
                    $('.errornama_perusahaan').html(resp['error']['nama_perusahaan']);
                } else {
                    $('#nama_perusahaan').removeClass('is-invalid');
                }
                if (resp['error']['deskripsi_perusahaan']) {
                    $('#deskripsi_perusahaan').addClass('is-invalid');
                    $('.errordeskripsi_perusahaan').html(resp['error']['deskripsi_perusahaan']);
                } else {
                    $('#deskripsi_perusahaan').removeClass('is-invalid');
                }
                if (resp['error']['alamat_perusahaan']) {
                    $('#alamat_perusahaan').addClass('is-invalid');
                    $('.erroralamat_perusahaan').html(resp['error']['alamat_perusahaan']);
                } else {
                    $('#alamat_perusahaan').removeClass('is-invalid');
                }
                if (resp['error']['email_perusahaan']) {
                    $('#email_perusahaan').addClass('is-invalid');
                    $('.erroremail_perusahaan').html(resp['error']['email_perusahaan']);
                } else {
                    $('#email_perusahaan').removeClass('is-invalid');
                }
                if (resp['error']['nomor_whatsapp']) {
                    $('#nomor_whatsapp').addClass('is-invalid');
                    $('.errornomor_whatsapp').html(resp['error']['nomor_whatsapp']);
                } else {
                    $('#nomor_whatsapp').removeClass('is-invalid');
                }
                if (resp['error']['template_pesan_whatsapp']) {
                    $('#template_pesan_whatsapp').addClass('is-invalid');
                    $('.errortemplate_pesan_whatsapp').html(resp['error']['template_pesan_whatsapp']);
                } else {
                    $('#template_pesan_whatsapp').removeClass('is-invalid');
                }
                if (resp['error']['url_maps']) {
                    $('#url_maps').addClass('is-invalid');
                    $('.errorurl_maps').html(resp['error']['url_maps']);
                } else {
                    $('#url_maps').removeClass('is-invalid');
                }
                if (resp['error']['url_facebook']) {
                    $('#url_facebook').addClass('is-invalid');
                    $('.errorurl_facebook').html(resp['error']['url_facebook']);
                } else {
                    $('#url_facebook').removeClass('is-invalid');
                }
                if (resp['error']['url_instagram']) {
                    $('#url_instagram').addClass('is-invalid');
                    $('.errorurl_instagram').html(resp['error']['url_instagram']);
                } else {
                    $('#url_instagram').removeClass('is-invalid');
                }
                if (resp['error']['keyword_pencarian']) {
                    $('#keyword_pencarian').addClass('is-invalid');
                    $('.errorkeyword_pencarian').html(resp['error']['keyword_pencarian']);
                } else {
                    $('#keyword_pencarian').removeClass('is-invalid');
                }
                if (resp['error']['logo_perusahaan']) {
                    $('#logo_perusahaan').addClass('is-invalid');
                    $('.errorlogo_perusahaan').html(resp['error']['logo_perusahaan']);
                } else {
                    $('#logo_perusahaan').removeClass('is-invalid');
                }
                if (resp['error']['icon_website']) {
                    $('#icon_website').addClass('is-invalid');
                    $('.erroricon_website').html(resp['error']['icon_website']);
                } else {
                    $('#icon_website').removeClass('is-invalid');
                }
                Swal.close();

                if(resp['error']['msg']) {
                    Swal.fire({icon: 'error', text: resp['error']['msg'], timer: 1800, showConfirmButton: false})
                }
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