var myEditor;
ClassicEditor
    .create(document.querySelector('#rules_content'))
    .then(editor => {
        console.log('Editor was initialized', editor);
        myEditor = editor;
    })
    .catch(error => {
        console.error(error);
    });

    $('#form-save').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('rules_content', myEditor.getData());
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
                    if (resp['error']['msg']) {
                        Swal.fire({icon: 'error', title: 'Oops...', text: resp['error']['msg']})
                    }
                    Swal.close();
                    return false;
                }
    
                if (resp['success']) {
                    Swal.fire(
                        {icon: 'success', title: 'Berhasil', text: resp['success']['msg'], timer: 1800, showConfirmButton: false}
                    )
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    })