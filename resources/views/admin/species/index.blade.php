@extends('layouts.master')

@section('title')
Species
@endsection

@section('species-show')
show
@endsection

@section('active-species')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Species</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Species</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                <a href="{{ route('species.create') }}" class="btn btn-sm btn-primary">Add New</a>
            </div>
            <i class="fas fa-feather me-1"></i>
            List of Species
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
            
            <table class="table table-sm" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Scientific Name</th>
                        <th>Common/Local Name</th>
                        <th>Type/Class/Family</th>
                        <th>Is present in this Province?</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($species as $specie)
                    <tr>
                        <td><i>{{ $specie->specie_name }}</i></td>
                        <td><a href="#modalDetails" data-bs-toggle="modal" onclick="showDetails('{{Crypt::encrypt($specie->id)}}', 'specie_details');">{{ $specie->local_name }}</a></td>
                        <td>{{ $specie->specie_type.'/'.$specie->specie_class.'/'.$specie->family }}</td>
                        <td>{{ ($specie->is_present==1) ? 'YES' : 'NO' }}</td>
                        <td>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" id="chkActiveStat{{$specie->id}}" onclick="ajaxUpdateStatus('chkActiveStat{{$specie->id}}', '{{Crypt::encrypt($specie->id)}}');" {{ ($specie->is_active_specie==1) ? 'checked' : '' }}>
                              <!-- <label class="form-check-label" for="flexSwitchCheckChecked">Checked switch checkbox input</label> -->
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('species.edit', ['id'=>Crypt::encrypt($specie->id)]) }}" title="Edit" alt="Edit"><i class="fas fa-edit fa-lg"></i></a>
                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal show details -->
<div class="modal fade" id="modalDetails" tabindex="-1" aria-labelledby="modalDetailsLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div id="specie_details"></div>
    </div>
  </div>
</div>
@endsection

@section('script-extra')
<script type="text/javascript">
    function showDetails(id, show_to) {
        $(this).ajaxRequestLaravel({
            show_result: ['/species/show/'+id, show_to],
            show_result_loader: true,
        });
    }

    function ajaxUpdateStatus(chkbox_id, specie_id) {
        var chkd = $('#'+chkbox_id).is(':checked');
        var stat = 0;
        if (chkd) {
            stat = 1;
        }
        // console.log(stat);
        $.ajax({
            type: 'POST',
            url: "{{ route('species.ajaxupdatestatus') }}",
            data: {specie_id:specie_id, is_active_specie:stat},
            success: function (result){
                // console.log(result);
            },
            error: function (result){
                // console.log(result);
                alert('Oops! Something went wrong. Please reload the page and try again.');
            }
        });
    }
</script>
@endsection