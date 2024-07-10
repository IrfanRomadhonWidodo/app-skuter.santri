let arrayNama = {};
var btnStart = $('#btnStart');
var randomNameView = $('#randomNameView');
var randomPoin = $('#randomPoin');
var onoff = 0;
let intervalId;
var sfxLoop = document.getElementById('loop-sound-effect');
var sfxWin = document.getElementById('win-sound-effect');
var sfxKlik = document.getElementById('klik-sound-effect');
var pilihHadiahText = 'Pilih Hadiah dulu';
var readyText = 'Are you ready ?';

btnStart.click(function () {
    const names = Object.values(arrayNama);
    if (onoff == 0) {
        clearInterval(intervalId);
        sfxLoop.play();
        randomPoin.show();
        intervalId = setInterval(() => {
            const randomIndex = Math.floor(Math.random() * names.length);
            randomNameView.text(names[randomIndex]['cust_nama']);
            randomPoin.text(names[randomIndex]['pn_id']);
        }, 10);
        $(this).attr('disabled', 'disabled');
        setTimeout(() => {
            $(this).removeAttr('disabled');
        }, 3000);
        $(this).html('Stop!');
        onoff = 1;
    } else {
        clearInterval(intervalId);
        sfxLoop.pause();
        sfxWin.play();
        potonganKertas(true);
        $(this).hide();
        onoff = 0;
        winner(randomNameView.text(), randomPoin.text());
    }
});


$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

function winner(nama, token)
{
    $.ajax({
        url: base_url() + 'lucky-draw/winner',
        type: 'post',
        data: {
            nama: nama,
            token: token,
            hadiah_nama: $('#hadiah').data('nama'),
            hadiah_bulan: $('#hadiah').data('bulan'),
            hadiah_gambar: $('#hadiah').data('gambar'),
        },
        success: function (response) {
            console.log(response);

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert('Gagal memilih pemenang, silahkan ulangi kembali!');
        }
    })
}


function potonganKertas(on = false)
{
    if(on == true)
    {
        $("#tsparticles")
        .particles()
        .ajax("particles.json", function (container) {});
    }else{
        $("#tsparticles").html('');
    }
}

function resetLuckyDraw()
{
    potonganKertas(false);
    $('#hadiah').data('bulan', '');
    $('#hadiah').data('nama', '');
    $('#hadiah').data('gambar', '');
    $('#randomNameView').text(pilihHadiahText);
    $('#randomPoin').text('').hide()
    $('#btnStart').html('Start!');
    $('#btnStart').hide();

    let defaultImage = $('#hadiah-gambar').data('default');

    $('#hadiah-gambar').attr({
        src : defaultImage
    })

    $('#hadiah-nama').text('');
    $('#hadiah-bulan').text('');

    sfxLoop.pause();
    sfxWin.pause();

}

$('button').on('click', function () {
    sfxKlik.play()
})

$(document).click(function(event) {
    // Periksa apakah klik berada di luar elemen sidebar
    if (!$(event.target).closest('#right-sidebar, #toggle-sidebar').length) {
        $('#right-sidebar').removeClass('active');
    }
});


$(document).ready(async function () {
    $('#toggle-sidebar').click(function () {
        $('#right-sidebar').toggleClass('active');
        reloadDatatables();
        reloadFilter();
    });
    await loadFull(tsParticles);

    var currentZoom = 1;

    $('#btnZoomIn').on('click', function () {
        currentZoom += 0.1;
        $('body').css('zoom', currentZoom);
    });

    $('#btnZoomOut').on('click', function () {
        currentZoom -= 0.1;
        $('body').css('zoom', currentZoom);
    });

    $('#btnFullscreen').on('click', function () {
        var elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.mozRequestFullScreen) { // Firefox
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) { // Chrome, Safari and Opera
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { // IE/Edge
            elem.msRequestFullscreen();
        }
    });

    $('#btnExitFullscreen').on('click', function () {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) { // Firefox
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) { // Chrome, Safari and Opera
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { // IE/Edge
            document.msExitFullscreen();
        }
    });

    function checkFullscreen() {
        if (document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement) {
            $('#btnFullscreen').hide();
            $('#btnExitFullscreen').show();
        } else {
            $('#btnFullscreen').show();
            $('#btnExitFullscreen').hide();
        }
    }
    // Event listener to check when fullscreen is entered or exited
    document.addEventListener('fullscreenchange', checkFullscreen);
    document.addEventListener('mozfullscreenchange', checkFullscreen);
    document.addEventListener('webkitfullscreenchange', checkFullscreen);
    document.addEventListener('msfullscreenchange', checkFullscreen);

    // Initial check on page load
    checkFullscreen();
})


$('#btnPilihHadiah').on('click', function () {
    $('.modal-backdrop').remove();

    let overlay = $('#btnPilihHadiah .overlay');
    let overlayHtml = overlay.html();
    $.ajax({
        url: $(this).data('url'),
        type: $(this).data('method'),
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
})

$('#btnRules').on('click', function () {
    $('.modal-backdrop').remove();
    $.ajax({
        url: $(this).data('url'),
        type: $(this).data('method'),
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
})

function setHadiah(key) {
    let btnSetHadiah = $('#btnSetHadiah-' + key)
    $('#hadiah-gambar').attr({
        src: base_url() + 'file?file=' + btnSetHadiah.data('gambar')
    });

    $('#hadiah-nama').html(btnSetHadiah.data('nama'));
    let hadiahBulan = `
        <span>Hadiah Bulan</span>
        <span class="badge badge-warning">${btnSetHadiah.data(
        'bulan'
    )}</span>`
    $('#hadiah-bulan').html(hadiahBulan);

    $('#hadiah').data('bulan', btnSetHadiah.data('bulan'));
    $('#hadiah').data('nama', btnSetHadiah.data('nama'));
    $('#hadiah').data('gambar', btnSetHadiah.data('gambar'));

    getPeserta();
}

function getPeserta() {
    let hadiah = $('#hadiah');
    $.ajax({
        url: hadiah.data('url'),
        type: hadiah.data('method'),
        data: {
            bulan: $('#hadiah').data('bulan'),
        },
        success: function (response) {
            // console.log(response);
            let resp = JSON.parse(response);
            if (resp['error']) {
                Swal.fire({icon: 'error', title: 'Oops...', text: resp['error']['msg']
                })
            }
            if (resp['success']) {
                arrayNama = resp['success']['data'];
                randomNameView.text(readyText);
                $('#btnStart').show();
            }

            // console.log(arrayNama)
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}


function btnKuponPeserta()
{
    $('.modal-backdrop').remove();

    let btn = $('#btnKuponPeserta');
    $.ajax({
        url: btn.data('url'),
        type: btn.data('method'),
        data: {
            bulan: $('#hadiah').data('bulan'),
            nama: $('#hadiah').data('nama'),
            gambar: $('#hadiah').data('gambar'),
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

dataAjax = {
    win_date: $('#win_date').val(),
    win_confirm: $('#win_confirm').val(),
    win_hdh_bulan: $('#win_hdh_bulan').val(),
}
tabel = null;
tabel = $('#table-winner').DataTable({
    "processing": true, 
    "serverSide": true, "ordering": true,
    "scrollY": true,
    "ajax": {
        "url": $('#table-winner').data('url'),
        "type": $('#table-winner').data('method'),
        "data": function(d){
            return $.extend(d, dataAjax);
        },
    },
    "deferRender": true,
    "lengthChange": false,
    "paging": false,
    "info": false,
    "searching": false,
    "ordering": false,
    "columns": [
        {
            "data": "cust_nama",
            "class": "vertical-middle",
            "render": function (data, type, row) {
                return '<b>'+data+'</b>' + '<br><small>' + row.cust_id +' - ' + row.cust_nomor_hp + '</small>';
            }
        }, {
            "data": "win_hdh_gambar",
            "class": "vertical-middle",
            "render": function (data, type, row) {
                return `<img src="${base_url()+'file?file='+ data}" width="100px" height="100px">` + row.win_hdh_nama
            }
        }, {
            "data": "win_hdh_bulan",
            "class": "vertical-middle",
        }, {
            "data": "win_confirm",
            "class": "vertical-middle",
            "render": function (data, type, row) {
                if(data == null || data == ''){ 
                    return '<span class="badge badge-warning">Menunggu Konfirmasi</span>'
                };
                return `<span class="badge badge-${data == '1' ? 'success' : 'danger'}">${data == '1' ? 'Valid' : 'Tidak Valid'}</span>`
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


function reloadFilter()
{
    $.ajax({
        url: $('#win_date').data('url'),
        type: $('#win_date').data('method'),
        success: function (response) {
            let resp = JSON.parse(response);
            if(resp['win_date'])
                {
                    let option = '<option value="">-- Pilih --</option>';
                    $.each(resp['win_date'], function (key, value) {
                        const months = [
                            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                        ];
                        const date = new Date(value['win_date']);
                        const day = date.getDate();
                        const month = months[date.getMonth()];
                        const year = date.getFullYear();
                        
                        option += '<option value="'+value['win_date']+'">'+`${day} ${month} ${year}`+'</option>';
                    });

                    $('#win_date').html(option);

                    let option2 = '<option value="">-- Pilih --</option>';
                    $.each(resp['win_hdh_bulan'], function (key, value) {
                        option2 += '<option value="'+value['win_hdh_bulan']+'">'+value['win_hdh_bulan']+'</option>';
                    });

                    $('#win_hdh_bulan').html(option2);
                }
        }
    });
}

$('#win_date, #win_hdh_bulan, #win_confirm').on('change', function () {
    reloadDatatables();
});