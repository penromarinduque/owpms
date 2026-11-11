<div class="">
    <button id="downloadCompleteLtpBtn{{ $ltp->id }}" class="btn btn-outline-primary btn-sm" onclick="downloadCompleteLtp('{{ Crypt::encryptString($ltp->id) }}', '{{ $ltp->id }}', '{{ $ltp->permit_number }}')">Download LTP w/ Attachments</button>
</div>
<script src="https://unpkg.com/pdf-lib@1.4.0/dist/pdf-lib.min.js"></script>
<script>
    function downloadCompleteLtp(ltpId, id, permitNumber) {
        activateLoader(id);
        // Make an AJAX request to fetch the attachments for the given LTP ID
        $.ajax({
            url: '/ltps/ajax-get-attachments/' + ltpId,
            method: 'GET',
            success: function(response) {
                console.log(response);
                if(!response.attachments){
                    alert('No attachments found for this LTP.');
                    deactivateLoader(id);
                    return;
                }
                (async () => {
                    await mergeAndDownloadFiles(response.attachments, permitNumber);
                    deactivateLoader(id);
                })();
            },
            error: function() {
                deactivateLoader(id);
                alert('An error occurred while fetching attachments.');
            }
        });
    }

    function activateLoader(ltpId){
        $("#downloadCompleteLtpBtn" + ltpId).prop("disabled", true).text("Downloading...");
    }

    function deactivateLoader(ltpId){
        $("#downloadCompleteLtpBtn" + ltpId).prop("disabled", false).text("Download LTP w/ Attachments");
    }

    async function mergeAndDownloadFiles(fileUrls, permitNumber) {
        if (!fileUrls || fileUrls.length === 0) return;

        try {
            const mergedPdf = await PDFLib.PDFDocument.create();

            for (const url of fileUrls) {
            const response = await fetch(url);
            
            if (!response.ok) {
                console.error(`HTTP Error ${response.status} when fetching: ${url}`);
                continue;
            }

            const contentType = response.headers.get('content-type') || '';
            const fileBytes = await response.arrayBuffer();

            // Check for HTML response (indicates error page)
            const textHead = new TextDecoder().decode(fileBytes.slice(0, 15));
            if (textHead.toLowerCase().includes('<!doctype') || textHead.toLowerCase().includes('<html')) {
                console.error(`Url returned HTML instead of a file: ${url}`);
                continue;
            }

            const cleanUrl = url.split('?')[0].toLowerCase();

            if (cleanUrl.endsWith('.pdf') || contentType.includes('application/pdf')) {
                const pdfDoc = await PDFLib.PDFDocument.load(fileBytes);
                const copiedPages = await mergedPdf.copyPages(pdfDoc, pdfDoc.getPageIndices());
                copiedPages.forEach((page) => mergedPdf.addPage(page));

            } else if (cleanUrl.match(/\.(png|jpe?g)$/) || contentType.includes('image/')) {
                const isPng = cleanUrl.endsWith('.png') || contentType.includes('image/png');
                const image = isPng ? await mergedPdf.embedPng(fileBytes) : await mergedPdf.embedJpg(fileBytes);

                const page = mergedPdf.addPage([image.width, image.height]);
                page.drawImage(image, { x: 0, y: 0, width: image.width, height: image.height });
            } else {
                console.warn(`Skipping unsupported format: ${url}`);
            }
            }

            const mergedPdfBytes = await mergedPdf.save();
            const blob = new Blob([mergedPdfBytes], { type: 'application/pdf' });
            const blobUrl = URL.createObjectURL(blob);

            const link = document.createElement('a');
            link.href = blobUrl;
            link.download = `${permitNumber}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(blobUrl);

        } catch (error) {
            console.error("Error merging files:", error);
        }
    }
</script>