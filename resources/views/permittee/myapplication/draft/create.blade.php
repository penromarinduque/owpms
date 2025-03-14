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
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Date of Transport [on or before]*:</label>
                            <input type="date" name="transport_date" id="transport_date" class="form-control" onchange="addOneMonth('transport_date', 'validity_result');" required>
                            <h5>Validity: <span id="validity_result"></span></h5>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <label>Place of Transport*:</label>
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="city" id="city" class="form-control" required placeholder="City">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="state" id="state" class="form-control" required placeholder="State">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="country" id="country" class="form-control" required placeholder="Country">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Purpose*:</label>
                        <textarea class="form-control" name="purpose" id="purpose" required placeholder="Purpose"></textarea>
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
                    <button type="button" id="btn_preview" class="btn btn-primary btn-block mr-1"><i class="fas fa-eye"></i> Preview</button>
                    <button type="submit" id="btn_save" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Save</button>
                </div>
        	</form>
        </div>
    </div>
</div>
@endsection

@section('script-extra')
<script type="text/javascript">
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

    jQuery(document).ready(function ($){
        let rowNumber = 1; // Counter for incremental numbers

        // Search field input handler
        $("#searchField").on("keyup", function () {
            let searchkey = $(this).val();

            if (searchkey.length > 0) {
                $.ajax({
                    url: "/myapplication/ajaxgetspecies",
                    method: "POST",
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
            let newRow = `
                <tr id="row_${itemData.id}">
                    <td align="center">${rowNumber}</td>
                    <td align="center">${itemData.scientifcname}</td>
                    <td align="center">${itemData.commonname}</td>
                    <td align="center">${itemData.family}</td>
                    <td align="center">
                        <input type="hidden" name="specie_id[]" id="specie_id" value="${itemData.id}" />
                        <input type="text" name="quantity[]" id="quantity" class="form-control text-center quantity" onkeyup="updateDynamicSum('quantity', 'txt_total');" placeholder="Quantity" required />
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

        // Preview
        document.getElementById('btn_preview').addEventListener('click', function () {
            event.preventDefault(); // Prevent form submission
            
            // Collect all quantity fields
            const quantities = document.querySelectorAll('input[name="quantity[]"]');
            let valid = true;

            // Check each quantity field
            quantities.forEach((input) => {
                if (!input.value || input.value <= 0) {
                    valid = false;
                    input.classList.add('border-red-500'); // Highlight invalid field
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            if (!valid) {
                alert('Please ensure all Quantity fields are valid (greater than 0).');
                return;
            }

            // If valid, proceed with AJAX
            const formData = new FormData(document.getElementById('form_create'));

            fetch('{{ route('myapplication.preview') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            })
            .then(response => response.text())
            .then(html => {
                const previewWindow = window.open('', '_blank');
                previewWindow.document.open();
                previewWindow.document.write(html);
                previewWindow.document.close();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Preview failed.');
            });
        });
    });
</script>
@endsection