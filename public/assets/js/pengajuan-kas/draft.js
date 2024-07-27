


$('#btnAddBerkas').on('click', function () {
    let berkasView = $('#berkasView');

    if ($(this).data('show') == '0') {
        berkasView.show();
        $(this).data('show', '1');
        $(this).html('<i class="fas fa-minus"></i>');
    } else {
        berkasView.hide();
        $(this).data('show', '0');
        $(this).html('<i class="fas fa-plus"></i>');
    }
})

$(document).ready(function () {
    $('#file').on('change', function() {
        var fileList = $('#fileList');
        fileList.empty(); // Clear previous file list

        var files = this.files;
        for (var i = 0; i < files.length; i++) {
            var listItem = $('<li></li>').text(files[i].name);
            var deleteButton = $('<button type="button" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>').attr('data-index', i);

            listItem.append(deleteButton);
            fileList.append(listItem);
        }
    });

    $('#fileList').on('click', 'button', function() {
        $(this).parent().remove(); // Remove the parent li element
    });

    reloadBerkastSection();
})

$('#btnAdditionalBerkas').on('click', function(){
    let additionalBerkas = $('.additional-berkas');
    let rowId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    let html = `
        <div class="row mb-1 custom-file-add" id="custom-file-${rowId}">
            <div class="col-10">
                <div class="custom-file" >
                    <input type="file" class="custom-file-input" id="custom-file-input-${rowId}" name="files[]" accept=".pdf, .txt" onchange="changeLabel('${rowId}')">
                    <label class="custom-file-label" id="custom-file-label-${rowId}" for="custom-file-input-${rowId}">Choose file</label>
                </div>
            </div>
            <div class="col-2 my-auto">
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteBerkas('${rowId}')"><i class="fas fa-trash"></i></button>
            </div>  
        </div>
    `

    additionalBerkas.append(html);
})

function deleteBerkas(rowId) {
    $('#custom-file-' + rowId).remove();
}

$('#form-berkas').on('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
            $('#btnSaveBerkas').prop('disabled', true);
            $('#btnSaveBerkas').html('<i class="fas fa-spinner fa-pulse mr-1"></i> Saving...');
        },
        complete: function(){
            $('#btnSaveBerkas').prop('disabled', false);
            $('#btnSaveBerkas').html('<i class="fas fa-save mr-1"></i> Upload');
        },
        success: function(data){
            let resp = JSON.parse(data);
            if(resp['error']){

                if(resp['error']['msg']['files']) {
                    Swal.fire({icon: 'error', text: resp['error']['msg']['files']});
                    return false;
                }
                Swal.fire({icon: 'error', text: resp['error']['msg']});
            }

            if(resp['success']){
                Swal.fire({icon: 'success', text: resp['success']['msg']});

                setTimeout(() => {
                    $('.custom-file-add').remove();
                    $('#custom-file-input-1').val('');
                    $('#custom-file-label-1').text('Choose file');
                    $('#btnAddBerkas').trigger('click');
                    reloadBerkastSection();
                }, 1000)
            }
        },
        error: function(err){
            console.log(err);
        }
    })
})

function reloadBerkastSection()
{
    let berkasSection = $('#berkas-section');

    $.ajax({
        url: berkasSection.data('url'),
        type: berkasSection.data('method'),
        success: function(data){
            let resp = JSON.parse(data);
            html = '';
            $.each(resp['success']['data'], function(key, value){

                btnView = `
                         <button class="btn btn-primary btn-sm" 
                            data-url="${base_url() + 'pengajuan-kas/'+value['kas_bks_id']+'/modal-view-berkas'}"
                            data-method="post"
                            id="btnViewBerkas-${value['kas_bks_id']}"
                            data-btn="<i class='fas fa-eye'></i>"
                            onclick="return viewModal('btnViewBerkas-${value['kas_bks_id']}')">
                                <i class="fas fa-eye"></i>
                        </button>
                `
                btnDelete = `
                         <button class="btn btn-danger btn-sm" 
                            data-url="${base_url() + 'pengajuan-kas/'+value['kas_bks_id']+'/delete-berkas'}"
                            data-method="delete"
                            id="btnDeleteBerkas-${value['kas_bks_id']}"
                            onclick="deleteBerkasSection('${value['kas_bks_id']}')">
                                <i class="fas fa-trash"></i>
                        </button>
                `
                btnDownload = `
                 <button class="btn btn-success btn-sm"
                            data-url="${base_url() + 'pengajuan-kas/'+value['kas_bks_id']+'/download-berkas'}"
                            data-method="post"
                            id="btnDownloadBerkas-${value['kas_bks_id']}" 
                            onclick="downloadBerkasSection('${value['kas_bks_id']}')"
                            >
                                <i class="fas fa-download"></i>
                            </button>
                    `
                let btn = '';
                btn = btnView + btnDelete + btnDownload;
                html += `
                <div class="border p-1 mb-1" >
                    <div class="row">
                        <div class="col-8 my-auto berkas-uploaded">
                            ${value['kas_bks_file']}
                        </div>
                        <div class="col-4 text-right my-auto">
                        ${ btn }
                        </div>
                    </div>
                </div>
                `
                berkasSection.html(html);
            })

            if(resp['success']['data'].length == 0){
                berkasSection.html('');
            }
        }
    })
}


function deleteBerkasSection(id)
{
    let btnDeleteBerkas = $('#btnDeleteBerkas-' + id);
    Swal
    .fire(
        {title: 'Are you sure?', text: 'You won\'t be able to revert this!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, delete it!'},
    )
    .then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: btnDeleteBerkas.data('url'),
                type: btnDeleteBerkas.data('method'),
                beforeSend: function () {
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Please wait...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                },
                success: function (data) {
                    console.log(data);
                    let resp = JSON.parse(data);
                    Swal.fire({icon: 'success', title: 'Success', text: resp['success']['msg']
                    })
                    reloadBerkastSection();
                }
            })
        }
    })
}

function downloadBerkasSection(id)
{
    window.location.href = base_url() + 'pengajuan-kas/' + id + '/download-berkas';
}


$('#btn-submit').on('click', function () {
    
    let arrayInputKey = ['kas_judul', 'kas_keterangan', 'kas_unkj_id', 'kas_nominal'];
    let arrayInputValue = [];
    for(let i = 0; i < arrayInputKey.length; i++){
        let value = $('#' + arrayInputKey[i]).val();
        if(value != ''){
            arrayInputValue.push($('#' + arrayInputKey[i]).val());
        }
    }

    if(arrayInputKey.length != arrayInputValue.length){
        Swal.fire({icon: 'error', text: 'Data berkas belum lengkap!'});
        return false;
    }

    let berkasUploaded = $('.berkas-uploaded');

    if(berkasUploaded.length == 0){
        Swal.fire({icon: 'error', text: 'Tidak ada berkas yang diupload!'});
        return false;
    }

    Swal
    .fire({
        title: 'Apakah anda yakin ?',
        text: 'Ingin mengajukan kas ini ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#186429',
        cancelButtonColor: '#353535',
        confirmButtonText: 'Ya, Ajukan'
    })
    .then((result) => {
        if (result.isConfirmed) {
            let btn = $(this);
            $.ajax({
                url: btn.data('url'),
                type: btn.data('method'),
                beforeSend: function () {
                    $('.btn').attr('disabled', 'disabled');
                    btn.html('<i class="fas fa-circle-notch fa-spin mr-1"></i>Loading');
                },
                complete: function () {
                    $('.btn').removeAttr('disabled');
                    btn.html(btn.data('btn'));
                },
                success: function (data) {
                    console.log(data);
                    let resp = JSON.parse(data);
                    if(resp['error'])
                    {
                        Swal.fire({icon: 'error', title: 'Oops...', text: resp['error']['msg']
                        })

                        if(resp['error']['reload'] == true)
                        {
                            window.location.reload();
                        }

                        return false;
                    }

                    if(resp['success'])
                    {
                        Swal.fire({icon: 'success', title: 'Success', text: resp['success']['msg']
                        }).then(() => {
                            window.location.reload();
                        })
                    }
                }
            })
        }
    })
})