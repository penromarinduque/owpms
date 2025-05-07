<div class="card">
    <div class="card-header">
        <h6>Permittee Information</h6>
    </div>
    <div class="card-body">
        <h5 class="card-title"></h5>
        <table class="table table-sm small table-bordered">
            <tr>
                <td class="fw-semibold">Owner</td>
                <td>{{ $user->personalInfo->first_name }} {{ $user->personalInfo->middle_name }} {{ $user->personalInfo->last_name }}</td>
            </tr>
            <tr>
                <td class="fw-semibold">Farm Name</td>
                <td>{{ $wfp->wildlifeFarm->farm_name }}</td>
            </tr>
            <tr>
                <td class="fw-semibold">Farm Location</td>
                <td>{{ $wfp->wildlifeFarm->barangay->barangay_name }}, {{ $wfp->wildlifeFarm->barangay->municipality->municipality_name }}, {{ $wfp->wildlifeFarm->barangay->municipality->province->province_name }}</td>
            </tr>
        </table>
    </div>
</div>
