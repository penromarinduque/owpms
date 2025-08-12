<div class="modal-header">
	<h1 class="modal-title fs-5" id="modalDetailsLabel"><i class="fa fa-info-circle fa-lg"></i> Specie Details</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	@if(!empty($specie))
	<div class="row mb-3">
    	<div class="col-sm-4">
    		<label for="specie_type_id" class="form-label mb-0">Wildlife Type:</label>
    		<h6>{{ $specie->specie_type ?? 'N/A' }}</h6>
    	</div>
    	<div class="col-sm-4">
    		<label for="specie_class_id" class="form-label mb-0">Class:</label>
    		<h6>{{ $specie->specie_class ?? 'N/A' }}</h6>
    	</div>
    	<div class="col-sm-4">
    		<label for="specie_family_id" class="form-label mb-0">Family:</label>
    		<h6>{{ $specie->family ?? 'N/A' }}</h6>
    	</div>
    </div>
    <div class="row mb-3">
    	<div class="col-sm-4">
    		<label for="specie_name" class="form-label mb-0">Scientific Name:</label>
    		<h6>{{ $specie->specie_name ?? 'N/A' }}</h6>
    	</div>
    	<div class="col-sm-4">
           	<label for="present" class="mb-0">Present in this Province:</label> 
          	<h6>@if($specie->is_present==1) <b title="Yes" alt="Yes">&#10004; Yes</b> @else <b title="No" alt="No">&#9940; No</b> @endif</h6>
    	</div>
    	<div class="col-sm-4">
    		<label for="local_name" class="form-label mb-0">Common/Local Name:</label>
    		<h6>{{ $specie->local_name ?? 'N/A' }}</h6>
    	</div>
    </div>
    <div class="row mb-3">
    	<div class="col-sm-4">
            <label for="wing_span" class="form-label mb-0">Wing Span (ave.):</label>
            <h6>{{ ($specie->wing_span!=NULL) ? $specie->wing_span : 'N/A' }}</h6>
        </div>
    	<div class="col-sm-4">
            <label for="wing_span" class="form-label mb-0">Convservation Status:</label> <br>
            <h6>{{ ucwords($specie->conservation_status) ?? 'N/A' }}</h6>
        </div>
    	<div class="col-sm-4"></div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-6">
            <label for="color_description" class="form-label mb-0">Color Description:</label>
            <h6>{{ $specie->color_description ?? 'N/A' }}</h6>
        </div>
        <div class="col-sm-6">
            <label for="food_plant" class="form-label mb-0">Food Plants:</label>
            <h6>{{ $specie->food_plant ?? 'N/A' }}</h6>
        </div>
    </div>
	@else
	<center>
	    <h5 class="text-danger">No record found!</h5>
	</center>
	@endif
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>