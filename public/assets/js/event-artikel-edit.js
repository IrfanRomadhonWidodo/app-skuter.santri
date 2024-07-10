var myEditor;
ClassicEditor
    .create(document.querySelector('#evt_art_konten'))
    .then(editor => {
        console.log('Editor was initialized', editor);
        myEditor = editor;
    })
    .catch(error => {
        console.error(error);
    });

$('#evt_art_judul').on('keyup', function () {
    $('#evt_art_slug').val(slugify($(this).val()));
})

function slugify(text) {
    return text.toString().toLowerCase()
      .replace(/\s+/g, '-')           // Replace spaces with -
      .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
      .replace(/\-\-+/g, '-')         // Replace multiple - with single -
      .replace(/^-+/, '')             // Trim - from start of text
      .replace(/-+$/, '');            // Trim - from end of text
  }

$('#form-save').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('editorContent', myEditor.getData());
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
                if (resp['error']['evt_art_judul']) {
                    $('#evt_art_judul').addClass('is-invalid');
                    $('.errorevt_art_judul').html(resp['error']['evt_art_judul']);
                } else {
                    $('#evt_art_judul').removeClass('is-invalid');
                }
                if (resp['error']['evt_art_author']) {
                    $('#evt_art_author').addClass('is-invalid');
                    $('.errorevt_art_author').html(resp['error']['evt_art_author']);
                } else {
                    $('#evt_art_author').removeClass('is-invalid');
                }
                if (resp['error']['evt_art_slug']) {
                    $('#evt_art_slug').addClass('is-invalid');
                    $('.errorevt_art_slug').html(resp['error']['evt_art_slug']);
                } else {
                    $('#evt_art_slug').removeClass('is-invalid');
                }
                if (resp['error']['evt_art_konten']) {
                    $('#evt_art_konten').addClass('is-invalid');
                    $('.errorevt_art_konten').html(resp['error']['evt_art_konten']);
                } else {
                    $('#evt_art_konten').removeClass('is-invalid');
                }
                if (resp['error']['evt_art_gambar']) {
                    $('#evt_art_gambar').addClass('is-invalid');
                    $('.errorevt_art_gambar').html(resp['error']['evt_art_gambar']);
                } else {
                    $('#evt_art_gambar').removeClass('is-invalid');
                }
                Swal.close();
                return false;
            }

            if (resp['success']) {
                Swal.fire(
                    {icon: 'success', title: 'Berhasil', text: resp['success']['msg'], timer: 1800, showConfirmButton: false}
                )

                setTimeout(() => {
                    window.location.href = resp['success']['redirect'];
                }, 1000);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
})