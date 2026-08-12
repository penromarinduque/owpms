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
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* gap: 60px; */
    }
    p{
        font-family: 'Times New Roman', Times, serif !important;
        font-size: 12px !important;
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
        $denrLogo = public_path('images/denr_logo.png');
        $bpLogo = public_path('images/bagong-pilipinas.png');
    @endphp
    <div  alt="DENR Logo" class="me-3" style="width: 80px; height: 80px;">
        {{-- @inlinedImage($denrLogo) --}}
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/denr_logo.png'))) }}"
                        style="width:80px;height:80px;" />
    </div>
    <div class="text-center">
        <p class="m-0 fw-bold ">DEPARTMENT OF ENVIRONMENT AND NATURAL RESOURCES</p>
        <p class="m-0 ">KAGAWARAN NG KAPALIGIRAN AT LIKAS NA YAMAN</p>
        <p class="m-0  fw-bold">PENRO MARINDUQUE</p>
    </div>
    <div alt="Bagong Pilipinas Logo" class="ms-3" style="width: 80px; height: 80px;">
        {{-- @inlinedImage($bpLogo) --}}
        <img
                        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images//bagong-pilipinas.png'))) }}"
                        style="width:80px;height:80px;">
    </div>
</div>
</body>
</html>

{{-- @endsection --}}