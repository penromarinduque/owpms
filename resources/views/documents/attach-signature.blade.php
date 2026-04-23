@extends('layouts.master')

@section('title') Attach Signature @endsection

@section('content')
    <div class="container-fluid px-4">
        <div class="my-3">
            <h5>Attach Signature</h5>
            <div class="row">
                <div class="col col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ request()->get('webhook') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" required id="id" value="{{ \Illuminate\Support\Facades\Crypt::decryptString(request()->get('id')) }}">
                                <input type="hidden" name="xCoordinate" required id="xCoordinate">
                                <input type="hidden" name="yCoordinate" required id="yCoordinate">
                                <input type="hidden" name="signatureWidth" required id="signatureWidth">
                                <input type="hidden" name="signatureHeight" required id="signatureHeight">
                                <input type="hidden" name="page" required id="page">
                                @error('xCoordinate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('yCoordinate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('signatureWidth')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('signatureHeight')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="mb-3">
                                    <label for="" class="form-label">Select Signature</label>
                                    <input type="file" name="signatureInput" id="signatureInput" class="form-control col col-md-6 col-lg-4" accept="image/png" onchange="onSignatureInputChange(event)">
                                    @error('signatureInput')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Page</label>
                                    <input type="number" value="1"  id="page" class="form-control col col-md-6 col-lg-4" name="page" onchange="onPageChange(event)">
                                    @error('page')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="signatureResizer" class="form-label">Resize Signature</label>
                                    <input type="range" class="form-range" id="signatureResizer" oninput="onSignatureResize(event)" min="1" max="5" value="1" step="0.001" >
                                    @error('signatureScale')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <hr>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary btn-submit">
                                        Continue
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col col-lg-9">
                    <div style="background-color: gray !important; width: 100%; overflow: scroll;" class="p-3 mb-3 d-flex justify-content-center">
                        <div style="position: relative; margin: 0 auto;" >
                            <canvas id="pdfCanvas" class="mx-auto"></canvas>
                            <img id="signaturePreview"  style="position:absolute; width:120px; display:none; x:0; y:0; cursor:move;" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script-extra')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        const url = "{!! $pdfUrl !!}";
        let pdfDoc = null;
        let scale = 1.5;
        let canvas = document.getElementById("pdfCanvas");
        let ctx = canvas.getContext("2d");
        let signature = document.getElementById("signaturePreview");
        let clickData = {
            x: 0,
            y: 0,
            page: 1
        };

        $(function(){
            init();
        });

        function init(){
            
            pdfjsLib.getDocument(url).promise.then(pdf => {
                pdfDoc = pdf;
                renderPage(clickData.page);
            });
            initSignatureDragging();
        }

        function initSignatureDragging() {
            let isDragging = false;
            let offsetX = 0;
            let offsetY = 0;

            // Mouse down on signature
            signature.addEventListener("mousedown", function (e) {
                isDragging = true;
                const rect = signature.getBoundingClientRect();
                offsetX = e.clientX - rect.left;
                offsetY = e.clientY - rect.top;
            });

            // Mouse move
            document.addEventListener("mousemove", function (e) {
                if (!isDragging) return;

                const canvasRect = canvas.getBoundingClientRect();

                let x = e.clientX - canvasRect.left - offsetX;
                let y = e.clientY - canvasRect.top - offsetY;

                // Prevent going outside canvas
                x = Math.max(0, Math.min(x, canvas.width - signature.offsetWidth));
                y = Math.max(0, Math.min(y, canvas.height - signature.offsetHeight));

                signature.style.left = x + "px";
                signature.style.top = y + "px";

            });

            // Mouse up
            document.addEventListener("mouseup", function () {
                isDragging = false;
                getPdfCoordinates();
            });

            // Click handler
            canvas.addEventListener("click", function (e) {
                const rect = canvas.getBoundingClientRect();

                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                clickData = {
                    x,
                    y,
                    page: 1
                };

                // Show preview
                signature.style.left = (x - 60) + "px";
                signature.style.top = (y - 20) + "px";
                getPdfCoordinates();
                signature.style.display = "block";
            });
        }

        function onSignatureInputChange(e) {
            let signatureFile = null;
            const file = e.target.files[0];
            if (!file) return;
            signatureFile = file;
            const reader = new FileReader();
            reader.onload = function(event) {
                signature.src = event.target.result;
            };
            reader.readAsDataURL(file);
            signature.style.display = "block";
            getPdfCoordinates();
        }

        function onSignatureResize(event) {
            $("#signaturePreview").css("scale", event.target.value);
            getPdfCoordinates();

        }

        function renderPage(num) {
            if (!pdfDoc || num < 1 || num > pdfDoc.numPages) {
                console.error("Invalid page request:", num);
                return;
            }

            pdfDoc.getPage(num).then(page => {
                const viewport = page.getViewport({ scale });

                canvas.width = viewport.width;
                canvas.height = viewport.height;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                page.render(renderContext);
            });
            getPdfCoordinates();
        }

        function onPageChange(event) {
            let page = parseInt(event.target.value);

            if (!page || page < 1 || page > pdfDoc.numPages) {
                console.error("Invalid page:", page);
                return;
            }

            clickData.page = page;
            renderPage(page);
        }

        function getPdfCoordinates() {
            let data = {
                page: clickData.page,
                x: (parseInt(signature.style.left)) / canvas.clientWidth,
                y: (parseInt(signature.style.top)) / canvas.clientHeight,
                w: $("#signaturePreview")[0].clientWidth / canvas.clientWidth,
                h: $("#signaturePreview")[0].clientHeight / canvas.clientHeight
            };
            $("#signatureWidth").val(data.w);
            $("#signatureHeight").val(data.h);
            $("#xCoordinate").val(data.x);
            $("#yCoordinate").val(data.y);
            $("#page").val(data.page); 
            console.log({
                page: clickData.page,
                x: (parseInt(signature.style.left)) / canvas.clientWidth,
                y: (parseInt(signature.style.top)) / canvas.clientHeight,
                w: $("#signaturePreview")[0].clientWidth / scale,
                h: $("#signaturePreview")[0].clientHeight / scale
            });
            return {
                x: clickData.x / scale,
                y: clickData.y / scale,
                page: clickData.page
            };
        }
    </script>
@endsection