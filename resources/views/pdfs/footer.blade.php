{{-- @extends('layouts.pdf')
@section('content') --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <style>
        .header div img {
            width: 100%;
            height: auto;
            object-fit: contain;
            font-family: 'Times New Roman', Times, serif !important;
        }
        .header {
            margin: 0px auto;
            padding: 0rem 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        p{
            font-size: 7px;
            text-align: center;
            margin-bottom: 0px;
            margin-top: 0px;
        }
        .fw-bold{
            font-weight: bold;
        }
    </style>
    <div class="header d-flex justify-content-center align-items-center mb-3 border-bottom pb-3 border-2">
        @php
            $isoLogo = public_path('images/iso-stamp.jpg');
            $socotecLogo = public_path('images/socotec-stamp.jpg');
        @endphp
        <div  alt="DENR Logo" class="me-3" style="width: 120px; ">
            {{-- @inlinedImage($isoLogo) --}}
            <img
                        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images//iso-stamp.jpg'))) }}"
                        style="width:80px;height:80px;">
        </div>
        <div class="text-center">
            <p class="m-0 " style="font-size: 8px !important;">
                Capitol Compound, Barangay Bangbangalon, Boac, Marinduque<br>
                Telephone Nos.: (042) 332-1490/(042) 332-0727/(042) 332-0927/(042) 332-1913<br>
                Website: https://penromarinduque.gov.ph/<br>
                Email: penromarinduque@denr.gov.ph<br>
            </p>
        </div>
        <div alt="Bagong Pilipinas Logo" class="ms-3" style="width: 120px;">
            {{-- @inlinedImage($socotecLogo) --}}
            <img
                        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images//socotec-stamp.jpg'))) }}"
                        style="width:80px;height:80px;">
        </div>
    </div>
</body>
</html>
{{-- @endsection --}}