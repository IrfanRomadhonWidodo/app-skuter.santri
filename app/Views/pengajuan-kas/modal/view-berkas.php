<!-- Modal -->
<div
    class="modal fade"
    id="<?= $modalId ?>"
    data-backdrop="static"
    data-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><?= $file_name ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <style>
                     #view-berkas canvas {
                        width: 100%;
                    }
                    #view-berkas .box-pdf {
                        height: 80vh;
                        overflow-y: scroll;
                        border: 3px solid #001F3F;
                        transition: ease-in-out 1s;
                    }
                    #view-berkas .box-pdf.view-more {
                        height: 600px !important;
                    }
                </style>
                <div id="view-berkas" data-content="<?= $content ?>" data-type="<?= $type ?>">
                    <div class="box-pdf">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    
    $(document).ready(function () {
        reloadViewBerkas();
    })
    function reloadViewBerkas() {
        let view =  $('#view-berkas');
        let content = view.data('content');
        let type = view.data('type');

        if(type == 'pdf') {
            loadPdfFile(content)
        }

        if(type == 'txt')
        {
            view.html(content)
        }

        console.log(content)

    }
    function loadPdfFile(data) {
        var loadingTask = pdfjsLib.getDocument(data);

        loadingTask
            .promise
            .then(function (pdf) {
                renderAllPages(pdf)
            }, function (reason) {
                // PDF loading error
                let alert = `<div class="alert alert-warning text-center font-weight-bold">Gagal menampilkan file</div>`
                $(`#view-berkas`)
                    .html(alert)
                    .css({height: 'auto', border: 'none', overflow: 'visible'});

                // $(`#col-aksi-${key}`).hide();
                // $(`#check-box-${key}`).hide();
                // $(`#col-left-${key}`).css()
            });
    }

    function renderAllPages(pdf) {
        console.log('PDF loaded');
        let view =  $('.box-pdf');
        for (var pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
            let createCanvas = $('<canvas>')
                .attr({id: `canvas-pdf-${pageNumber}`})
                .appendTo(view);
            let canvas = document.getElementById(`canvas-pdf-${pageNumber}`)
            pdf
                .getPage(pageNumber)
                .then(function (page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({scale: scale});

                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask
                        .promise
                        .then(function () {
                            console.log('Page rendered');
                        });
                });
        }
    }

    
</script>