$('#form-login').on('submit', function (e) {
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
                html: 'Memerika...',
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
                if (resp['error']['usr_username']) {
                    $('#usr_username').addClass('is-invalid');
                    $('.errorusr_username').html(resp['error']['usr_username']);
                } else {
                    $('#usr_username').removeClass('is-invalid');
                }
                if (resp['error']['usr_password']) {
                    $('#usr_password').addClass('is-invalid');
                    $('.errorusr_password').html(resp['error']['usr_password']);
                } else {
                    $('#usr_password').removeClass('is-invalid');
                }
                if(resp['error']['msg'])
                {
                    Swal
                    .fire(
                        {icon: 'error', title: 'Mohon maaf', text: resp['error']['msg'], timer: 2000, showConfirmButton: false}
                    )
                }
                swal.close()
                return false;
            }

            if (resp['success']) {
                Swal
                    .fire(
                        {icon: 'success', title: 'Berhasil', text: resp['success']['msg'], timer: 1800, showConfirmButton: false}
                    )
                    .then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            window.location.href = resp['success']['redirect'];
                        } else {
                            window.location.href = resp['success']['redirect'];
                        }
                    });
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
})

$('#look-password').on('click', function () {
    if ($('#password').attr('type') == 'password') {
        $('#look-password-icon').html('<span class="fas fa-eye"></span>');
        $('#password').prop('type', 'text');
    } else {
        $('#look-password-icon').html('<span class="fas fa-eye-slash"></span>');
        $('#password').prop('type', 'password');
    }
})
$('#look-password_confirm').on('click', function () {
    if ($('#password_confirm').attr('type') == 'password') {
        $('#look-password_confirm-icon').html('<span class="fas fa-eye"></span>');
        $('#password_confirm').prop('type', 'text');
    } else {
        $('#look-password_confirm-icon').html('<span class="fas fa-eye-slash"></span>');
        $('#password_confirm').prop('type', 'password');
    }
})

$('.form-control').on('input change', function () {
    $(this).removeClass('is-invalid');
})

