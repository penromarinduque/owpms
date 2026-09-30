@extends('layouts.master')

@section('title')
Permittee Species
@endsection

@section('active-permittees')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Permittee Species</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('permittees.index') }}">Permittees</a></li>
        <li class="breadcrumb-item ">Species</li>
        <li class="breadcrumb-item active">Add</li>
    </ol>

    <div class="card">
        <div class="card-header">
            Add Species
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('permitteespecies.store') }}" >
                @csrf
                <div class="row">
                    <div class="mb-3 col-12 col-md-4">
                        <label for="permittee_id" class="form-label">Permittee <span class="text-danger">*</span></label>
                        <select 
                            name="permittee_id" 
                            id="permittee_id" 
                            {{-- class="form-select select2Paginate"  --}}
                            class="form-select select21" 
                            data-placeholder="Select Permittee" 
                            data-ajax--url="{{ route('permitteespecies.ajaxGetPermittees') }}"
                            data-allow-clear="true"
                            >
                            <option value="">sad</option>
                        </select>
                        @error('permittee_id') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-3 col-12 col-md-4">
                        <label for="species_id" class="form-label">Species <span class="text-danger">*</span></label>
                        <select 
                            name="specie_id" 
                            id="specie_id" 
                            {{-- class="form-select select2Paginate" --}}
                            class="form-select select21"
                            data-placeholder="Select Species" 
                            data-ajax--url="{{ route('permitteespecies.ajaxgetspecies') }}"
                            data-allow-clear="true"
                            >
                        </select>
                        @error('specie_id') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-3 col col-md-4">
                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" min="1" class="form-control" >
                        @error('quantity') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-3 col-12 col-md-4 ">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script-extra')
{{-- <script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });
</script> --}}
<script>
     $(document).ready(function() {
         console.log("fdsf")
        //  $(".select21").select2({});
         $(".select21").select2({
             ajax: {
                 delay: 250,
                 dataType: 'json',
                 data : function(params){
                     var query = {
                         search: params.term,
                         type: 'public',
                         page: params.page || 1
                     }
                     console.log("tagfd")
                     // Query parameters will be ?search=[term]&type=public
                     return query;
                 },
                 processResults: function (data) {
                     console.log("test search");
                     return {
                         results: data.data,
                         // pagination: {
                         //     more: (data.current_page < data.last_page)
                         // }
                     };
                 },
             }
         });
     })
    // $(".select2Paginate").select2({
    //     ajax : {
    //         delay: 250,
    //         data : function(params){
    //             var query = {
    //                 search: params.term,
    //                 type: 'public',
    //                 page: params.page || 1
    //             }
    //             console.log("tagfd  ")
    //             // Query parameters will be ?search=[term]&type=public
    //             return query;
    //         },
    //         processResults: function (data) {
    //             console.log("sdffd");
    //             return {
    //                 results: data.data,
    //                 pagination: {
    //                     more: (data.current_page < data.last_page)
    //                 }
    //             };
    //         },
    //     }
    // });
</script>
@endsection