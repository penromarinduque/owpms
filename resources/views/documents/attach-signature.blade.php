@extends('layouts.master')

@section('title') Attach Signature @endsection

@section('content')
    <div class="container-fluid px-4">
        <div class="my-3">
            <div class="row">
                <input type="file" id="signatureInput" class="form-control col col-md-6 col-lg-4" accept="image/png">
            </div>
        </div>
        <div style="background-color: gray !important; width: 100%; overflow: scroll;" class="p-3 mb-3">
            <div style="position: relative; margin: 0 auto;" >
                <canvas id="pdfCanvas" class="mx-auto"></canvas>
                
                <img id="signaturePreview" src="/signature.png" style="position:absolute; width:120px; display:none; pointer-events:none;">
            </div>
        </div>
        <form action="">
            <input type="hidden" name="xCoordinate" required id="xCoordinate">
            <input type="hidden" name="yCoordinate" required id="yCoordinate">
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary">
                    Continue
                </button>
            </div>
        </form>
    </div>
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

        // Load PDF
        pdfjsLib.getDocument(url).promise.then(pdf => {
            pdfDoc = pdf;
            renderPage(1);
        });

        let signatureFile = null;
        document.getElementById("signatureInput").addEventListener("change", function(e) {
            const file = e.target.files[0];
            if (!file) return;

            signatureFile = file;

            const reader = new FileReader();
            reader.onload = function(event) {
                signature.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });

        function renderPage(num) {
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
        }

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
            signature.style.display = "block";
        });

        function getPdfCoordinates() {
            return {
                x: clickData.x / scale,
                y: clickData.y / scale,
                page: clickData.page
            };
        }

        function savePosition() {
            const coords = getPdfCoordinates();

            fetch('/save-signature-position', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(coords)
            });
        }
    </script>
@endsection