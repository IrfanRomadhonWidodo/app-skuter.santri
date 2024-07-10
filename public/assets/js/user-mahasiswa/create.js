$(document).ready(function () {
    $('#usr_password').val(generatePassword(8));
    $('#btnGeneratePassword').on('click', function () {
        $('#usr_password').val(generatePassword(8));
    })

    $('#usr_kewarganegaraan').on('change', function () {
        let selectedOption = $(this).find('option:selected');
        let flag = selectedOption.data('flag');
        let img = `<img src="${base_url() + 'file?file=/master/img-country-flag/' +
                flag}" alt="">`;
        $('#usr_kewarganegaraan_bendera').html(img);
    })
})
function generatePassword(length) {
    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    let password = '';
    for (let i = 0; i < length; i++) {
        let randomIndex = Math.floor(Math.random() * chars.length);
        password += chars[randomIndex];
    }
    return password;
}

let back = false;
$('#btn-save').on('click', function () {
    back = true;
    $('#form-save').submit();
})

$('#btn-save-and-add').on('click', function () {
    back = false;
    $('#form-save').submit();
    resetForm();
})

function resetForm() {
    $(
        '#usr_nama, #usr_nik, #usr_tempat_lahir, #usr_tanggal_lahir, #usr_nomor_hp, #us' +
        'r_email, #usr_id'
    ).val('')
    $('#usr_jenis_kelamin option:first').prop('selected', true);
    $('#usr_golongan_darah option:first').prop('selected', true);
    $('#usr_agama option:first').prop('selected', true);
    $('#usr_mhs_prodi_id option:first').prop('selected', true);
    $('#usr_mhs_angkatan option:first').prop('selected', true);
    let usr_foto = $('#usr_foto');
    $('.img-preview').attr('src', usr_foto.data('default'));
    $('.custom-file-label').text('Pilih Foto');
    usr_foto.val('');

    $('#usr_password').val(generatePassword(8));
}

$('#form-save').on('submit', function (e) {
    let btnSave = $('#btn-save');
    e.preventDefault();
    let formData = new FormData(this);
    formData.append(
        $('meta[name=csrf-name]').attr('content'),
        $('meta[name=csrf-value]').attr('content'),
    );

    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: formData,
        processData: false,
        contentType: false,
        chace: false,
        beforeSend: function () {
            btnSave
                .attr('disabled', 'disabled')
                .html('Menyimpan...');
        },
        complete: function () {
            btnSave
                .removeAttr('disabled', 'disabled')
                .html('<i class="fas fa-save mr-1"></i>Simpan');
        },
        success: function (response) {
            console.log(response);
            let resp = JSON.parse(response);

            if (resp['error']) {
                if (resp['error']['usr_nama']) {
                    $('#usr_nama').addClass('is-invalid');
                    $('.errorusr_nama').html(resp['error']['usr_nama']);
                } else {
                    $('#usr_nama').removeClass('is-invalid');
                }
                if (resp['error']['usr_nik']) {
                    $('#usr_nik').addClass('is-invalid');
                    $('.errorusr_nik').html(resp['error']['usr_nik']);
                } else {
                    $('#usr_nik').removeClass('is-invalid');
                }
                if (resp['error']['usr_tempat_lahir']) {
                    $('#usr_tempat_lahir').addClass('is-invalid');
                    $('.errorusr_tempat_lahir').html(resp['error']['usr_tempat_lahir']);
                } else {
                    $('#usr_tempat_lahir').removeClass('is-invalid');
                }
                if (resp['error']['usr_tanggal_lahir']) {
                    $('#usr_tanggal_lahir').addClass('is-invalid');
                    $('.errorusr_tanggal_lahir').html(resp['error']['usr_tanggal_lahir']);
                } else {
                    $('#usr_tanggal_lahir').removeClass('is-invalid');
                }
                if (resp['error']['usr_nomor_hp']) {
                    $('#usr_nomor_hp').addClass('is-invalid');
                    $('.errorusr_nomor_hp').html(resp['error']['usr_nomor_hp']);
                } else {
                    $('#usr_nomor_hp').removeClass('is-invalid');
                }
                if (resp['error']['usr_email']) {
                    $('#usr_email').addClass('is-invalid');
                    $('.errorusr_email').html(resp['error']['usr_email']);
                } else {
                    $('#usr_email').removeClass('is-invalid');
                }
                if (resp['error']['usr_mhs_prodi_id']) {
                    $('#usr_mhs_prodi_id').addClass('is-invalid');
                    $('.errorusr_mhs_prodi_id').html(resp['error']['usr_mhs_prodi_id']);
                } else {
                    $('#usr_mhs_prodi_id').removeClass('is-invalid');
                }
                if (resp['error']['usr_mhs_angkatan']) {
                    $('#usr_mhs_angkatan').addClass('is-invalid');
                    $('.errorusr_mhs_angkatan').html(resp['error']['usr_mhs_angkatan']);
                } else {
                    $('#usr_mhs_angkatan').removeClass('is-invalid');
                }
                if (resp['error']['usr_id']) {
                    $('#usr_id').addClass('is-invalid');
                    $('.errorusr_id').html(resp['error']['usr_id']);
                } else {
                    $('#usr_id').removeClass('is-invalid');
                }
                return false;
            }

            if (resp['success']) {
                Swal.fire(
                    {icon: 'success', title: 'Berhasil', text: resp['success']['msg'], timer: 1800, showConfirmButton: false}
                )

                if (back) {
                    setTimeout(() => {
                        $('#btnBack').click();
                    }, 1000)
                } else {
                    resetForm();
                }
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
});