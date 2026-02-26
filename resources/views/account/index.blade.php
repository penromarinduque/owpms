@extends('layouts.master')

@section('title')
Account
@endsection

@section('content')
<div class="container px-4">
    <h2 class="mt-4">Account</h2>

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-user-circle me-2"></i>Account Info</div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Username</small></label>
                    <h6>{{ $user->username }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Email</small></label>
                    <h6>{{ $user->email }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">User Type</small></label>
                    <h6>{{ Str::title($user->usertype) }}</h6>
                </div>
            </div>
            <div id="list-example" class="list-group">
                <a class="list-group-item list-group-item-action" href="{{ route('password.request') }}"><i class="fas fa-key me-2"></i>Reset Password</a>
                <a class="list-group-item list-group-item-action" href="#list-item-2"><i class="fas fa-user-edit me-2"></i>Change Username</a>
                <a class="list-group-item list-group-item-action" href="#list-item-3"><i class="fas fa-envelope me-2"></i>Change Email</a>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-user me-2"></i>Personal Info</div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">First Name</small></label>
                    <h6>{{ $user->personalInfo->first_name }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Middle Name</small></label>
                    <h6>{{ $user->personalInfo->middle_name }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Last Name</small></label>
                    <h6>{{ $user->personalInfo->last_name }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Gender</small></label>
                    <h6>{{ ucfirst($user->personalInfo->gender) }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Email</small></label>
                    <h6>{{ $user->personalInfo->email }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Contact Number</small></label>
                    <h6>{{ ucfirst($user->personalInfo->contact_no) }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Address</small></label>
                    <h6>{{ $user->personalInfo->barangay->barangay_name }}, {{ $user->personalInfo->barangay->municipality->municipality_name }}, {{ $user->personalInfo->barangay->municipality->province->province_name }}</h6>
                </div>
            </div>
            <div id="list-example" class="list-group">
                <a class="list-group-item list-group-item-action" href="{{ route('account.personalInfo.edit', [Crypt::encryptString($user->personalInfo->id)]) }}"><i class="fas fa-user-edit me-2"></i>Update Personal Info</a>
            </div>
        </div>
    </div>
    @if ($user->usertype == 'permittee')
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-id-card me-2"></i>Permittee Info</div>
        </div>
        <div class="card-body">
            <h5>Wildlife Collectors Permit</h5>
            <div class="row mb-3">
                @php
                    $wcp = $user->wcp();
                @endphp
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Permit No.</small></label>
                    <h6>{{ $wcp->permit_number }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Valid From</small></label>
                    <h6>{{ $wcp->valid_from->format("F d, Y") }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Valid To</small></label>
                    <h6>{{ $wcp->valid_to->format("F d, Y") }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Date Issued</small></label>
                    <h6>{{ $wcp->date_of_issue->format("F d, Y") }}</h6>
                </div>
            </div>
            <hr>
            <h5>Wildlife Farm Permit</h5>
            <div class="row mb-3">
                @php
                    $wfp = $user->wfp();
                @endphp
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Permit No.</small></label>
                    <h6>{{ $wfp->permit_number }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Valid From</small></label>
                    <h6>{{ $wcp->valid_from->format("F d, Y") }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Valid To</small></label>
                    <h6>{{ $wcp->valid_to->format("F d, Y") }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Date Issued</small></label>
                    <h6>{{ $wcp->date_of_issue->format("F d, Y") }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Farm Name</small></label>
                    <h6>{{ $wfp->wildlifeFarm->farm_name }}</h6>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label><small class="text-secondary">Location</small></label>
                    <h6>{{ $wfp->wildlifeFarm->barangay->barangay_name }}, {{ $wfp->wildlifeFarm->barangay->municipality->municipality_name }}, {{ $wfp->wildlifeFarm->barangay->municipality->province->province_name }}</h6>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="card-title"><i class="fa-solid fa-signature me-2"></i>Signature</div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadSignatureModal"><i class="fa-solid fa-upload me-2"></i> Upload eSignature</button>
            </div>
        </div>
        <div class="card-body">
            <div class="mx-auto">
                @php
                    $signature = auth()->user()->getSignature();
                @endphp
                @if ($signature)
                    <img class="d-block mx-auto" src="{{ route("account.viewSignature", ['id' => auth()->user()->id ]) }}" alt="">
                @else
                    <h6 class="text-center">No Signatures uploaded</h6>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadSignatureModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        <i class="fa-solid fa-signature me-2"></i>
                        Upload Signature
                    </h6>
                </div>
                <div class="modal-body">
                    <input type="file" id="signature-upload" accept=".png" class="form-control">
                    <img id="imagePreview"  alt="">
                    <div id="croppie"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-submit-dynamic" id="btnUploadSignature">Upload</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script-extra')
    <script>
        $(function(){
            const croppie = $('#croppie').croppie({
                viewport: {
                    width: 300,
                    height: 150
                },
                boundary: { height: 500 },
                enableOrientation: true
            });

            $("#btnUploadSignature").click(function(){
                $(".btn-submit-dynamic").attr("disabled", true)
                croppie.croppie("result", "base64")
                .then(function(p) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route("account.uploadSignature") }}',
                        data: {
                            userId: '{{ auth()->user()->id }}',
                            signature: p
                        },
                        success: function (res) {
                            console.log(res);
                            $("#signature-upload").val("")
                            croppie.croppie("destroy")
                            $("#uploadSignatureModal").modal("hide")
                            location.href = location.href
                        },
                        complete: function(){
                            $(".btn-submit-dynamic").attr("disabled", false)
                        }
                    })
                })
            });

            $("#signature-upload").change(function(){
                const file = $(this)[0].files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        initCroppie(e.srcElement.result, croppie);
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Please select a valid image file.');
                }
            })
        });

        function initCroppie(fileUrl, croppie) {
            croppie.croppie('bind',{
                url: fileUrl
            })
        }
    </script>
@endsection