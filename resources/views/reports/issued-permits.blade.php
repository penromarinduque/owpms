@extends('layouts.master')

@section('title')
    Issued Permits
@endsection

@section('active-issued-permits')
active
@endsection

@section('content') 
<div class="container-fluid px-4">
    <h1 class="mt-4">Issued Permits</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Reports</a></li>
        <li class="breadcrumb-item">Issued Permits</li>
    </ol>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('reports.print-issued-permits') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col col-lg-4 mb-3">
                        <select class=" form-control" name="type" id="type">
                            <option value="1">Issued LTP Summary</option>
                            <option value="2">Issued LTP By Permittee</option>
                            <option value="3">PAWCZMS Form 4a</option>
                            <option value="4">BMB Statistical Form 3 - Statistical Report</option>
                        </select>
                    </div>
                    <div class="col col-lg-4 mb-3">
                        <select class=" form-control" name="frequency" id="frequency">
                            <option value="1">Monthly</option>
                            <option value="2">Quarterly</option>
                            <option value="3">Semestral</option>
                            <option value="4">Annual</option>
                        </select>
                    </div>
                    <div class="col col-lg-4 mb-3" id="monthWrapper">
                        <select class=" form-control" name="month" id="month">
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="col col-lg-4 mb-3" id="quarterWrapper">
                        <select class=" form-control" name="quarter" id="quarter">
                            <option value="1">First Quarter</option>
                            <option value="2">Second Quarter</option>
                            <option value="3">Third Quarter</option>
                            <option value="4">Fourth Quarter</option>
                        </select>
                    </div>
                    <div class="col col-lg-4 mb-3 " id="semesterWrapper">
                        <select class=" form-control" name="semester" id="semester">
                            <option value="1">First Semester</option>
                            <option value="2">Second Semester</option>
                        </select>
                    </div>
                    <div class="col col-lg-4 mb-3" id="yearWrapper">
                        <select class=" form-control" name="year" id="year">
                            @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col col-lg-4 mb-3 " id="paperWrapper">
                        <select class=" form-control" name="paper" id="paper">
                            <option value="a4">A4</option>
                            <option value="letter">Letter</option>
                            <option value="legal">Legal</option>
                        </select>
                    </div>
                    <div class="col col-lg-4 mb-3 " id="orientationWrapper">
                        <select class=" form-control" name="orientation" id="orientation">
                            <option value="portrait">Portrait</option>
                            <option value="landscape">Landscape</option>
                        </select>
                    </div>
                    <div class="col col-lg-4 mb-3">
                        <button class="btn btn-primary"><i class="fa-solid fa-print"></i> Print Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script-extra')
<script>
    $(document).ready(function () {

        $('#monthWrapper, #quarterWrapper, #semesterWrapper').hide();

        $('#frequency').on('change', function () {

            var type = parseInt($(this).val());

            // Hide all wrappers
            $('#monthWrapper, #quarterWrapper, #semesterWrapper').hide();

            if ([1].includes(type)) {

                $('#monthWrapper').show();

            } else if ([2].includes(type)) {

                $('#quarterWrapper').show();

            } else if ([3].includes(type)) {

                $('#semesterWrapper').show();

            }

        });

    });
</script>
@endsection