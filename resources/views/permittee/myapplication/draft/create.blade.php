@extends('layouts.master')

@section('title')
{{$title}}
@endsection

@section('active-myapplication')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{$title}}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('myapplication.index') }}">My Applications</a></li>
        <li class="breadcrumb-item active">{{$title}}</li>
    </ol>

    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                <a href="{{ route('myapplication.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-chevron-left"></i> Back</a>
            </div>
            <i class="fas fa-plus-square me-1"></i>
            Create New Application
        </div>
        <div class="card-body">
            @if(session('failed'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{ session('failed') }}</strong>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{ session('success') }}</strong>
            </div>
            @endif
        	<form id="form_create" method="POST" action="{{ route('myapplication.store') }}" onsubmit="disableSubmitButton('btn_save');">
        	    @csrf
                <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
                <div class="row mb-2">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>Date of Transport [on or before] <span class="text-danger">*</span>:</label>
                            <input type="date" name="transport_date" id="transport_date" class="form-control" min="{{ date('Y-m-d') }}" onchange="addOneMonth('transport_date', 'validity_result');" required>
                            <h5 class="mt-2">Validity: <span id="validity_result"></span></h5>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <label>Purpose <span class="text-danger">*</span>:</label>
                        <select name="purpose" id="purpose" class="form-select mb-2" required onchange="onPurposeChange(event);">
                            <option value="" selected disabled>Select Purpose</option>
                            <option value="research">Research</option>
                            <option value="local sale">Local Sale</option>
                            <option value="export">Export</option>
                        </select>
                    </div>
                    <div class="col-md-7" id="destination_div">
                        <label>Destination <span class="text-danger">*</span>:</label>
                        <select name="destination" id="destination" class="form-select mb-2 select-2">
                            <option value="" selected disabled>Select Destination</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->province_name }}, {{ $province->region->region_name }}</option>
                            @endforeach
                        </select>
                        {{-- <div class="row">
                            <div class="col-md-3 mb-2">
                                <input type="text" name="city" id="city" class="form-control" required placeholder="City">
                            </div>
                            <div class="col-md-3 mb-2">
                                <input type="text" name="state" id="state" class="form-control" required placeholder="State">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" name="country" id="country" class="form-control" required placeholder="Country">
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label>Specie Details:</label>
                        <div class="row">
                            <div class="col-md-7">
                                <input type="search" name="searchField" id="searchField" class="form-control" placeholder="Enter search key">
                                <div class="searchresults" id="results"></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-10">
                        <table id="dataTable" class="table table-sm table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Common Name</th>
                                    <th class="text-center">Scientific Name</th>
                                    <th class="text-center">Family Name</th>
                                    <th class="text-center" width="8%">Quantity</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" align="right"><b>TOTAL</b></td>
                                    <td align="center"><b><span id="txt_total"></span></b></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="float-end mt-4">
                    <button type="submit" id="btn_save" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Save</button>
                </div>
        	</form>
        </div>
    </div>
</div>
@endsection

@section('script-extra')
<script type="text/javascript">

    $(document).ready(function() {
        $("#destination_div").hide();

        // Initialize the select2 plugin for the destination dropdown
        $('#destination').select2({
            placeholder: "Select Destination",
            allowClear: true,
            width: '100%',
        });
    });

    // Function to calculate and update the sum
    function updateDynamicSum(fld_class, result_element) {
        let sum = 0;
        const inputs = document.querySelectorAll('.'+fld_class); // Select all inputs with the class "dynamic-input"

        inputs.forEach(input => {
            const value = parseFloat(input.value);
            if (!isNaN(value)) {
                sum += value;
            }
        });

        // Update the result field
        document.getElementById(result_element).innerHTML = sum;
    }

    function removeAdded(row_id, row_element) {
        $('#'+row_element+row_id).remove();
        updateDynamicSum('quantity', 'txt_total');
    }

    function onPurposeChange(e) {
        const purpose = e.target.value;
        if(purpose == 'local sale') {
            $("#destination_div").show();
        }
        else {
            $("#destination_div").hide();
        }
    }

    jQuery(document).ready(function ($){
        let rowNumber = 1; // Counter for incremental numbers

        // Search field input handler
        $("#searchField").on("keyup", function () {
            let searchkey = $(this).val();

            if (searchkey.length > 0) {
                $.ajax({
                    url: "/myapplication/ajaxgetspecies",
                    method: "GET",
                    data: { searchkey: searchkey },
                    success: function (data) {
                         console.log(data);
                        $("#results").html(data).show();
                    },
                    errr: function (data) {
                        // console.log(data);
                    }
                });
            } else {
                $("#results").hide();
            }
        });

        // Click on a result
        $(document).on("click", ".result-item", function () {
            let itemData = $(this).data(); // Retrieve data attributes
            if($("#row_"+itemData.id).length > 0) {
                showToast("warning", "Specie already added.");
                return;
            }
            let newRow = `
                <tr id="row_${itemData.id}">
                    <td align="center">${rowNumber}</td>
                    <td align="center">${itemData.scientifcname}</td>
                    <td align="center">${itemData.commonname}</td>
                    <td align="center">${itemData.family}</td>
                    <td align="center">
                        <input type="hidden" name="specie_id[]" id="specie_id" value="${itemData.id}" />
                        <input type="number" name="quantity[]" id="quantity" class="form-control text-center quantity" onkeyup="updateDynamicSum('quantity', 'txt_total');" placeholder="Quantity" required />
                    </td>
                    <td align="center">
                        <a href="#" class="btn btn-sm mx-1" onclick="removeAdded(${itemData.id}, 'row_');"><i class="fas fa-trash text-danger"></i></a>
                    </td>
                </tr>
            `;
            $("#dataTable tbody").append(newRow);
            rowNumber++; // Increment the row number
            $("#results").hide(); // Hide results
            $("#searchField").val(''); // Clear search field
        });

        // Hide search results when clicking outside
        $(document).on("click", function (e) {
            if (!$(e.target).closest("#searchField, #results").length) {
                $("#results").hide();
            }
        });

    });

    
</script>
@endsection

@include('components.toast')