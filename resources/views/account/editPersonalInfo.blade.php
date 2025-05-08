@extends('layouts.master')

@section('title')
Account
@endsection

@section('content')
<div class="container px-4">
    <h2 class="mt-4">Account</h2>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('account.index') }}">Account</a></li>
        <li class="breadcrumb-item active">Edit Personal Info</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-user-circle me-2"></i>Edit Personal Info</div>
        </div>
        <div class="card-body">
            <form action="{{ route('account.personalInfo.update', [Crypt::encryptString($personInfo->id)]) }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-4 col-sm-6">
                        <label for="first_name" class="form-label">Firstname<b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="first_name" id="first_name" value="{{ old("first_name") ?? $personInfo->first_name }}" >
                        @error('first_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label for="middle_name" class="form-label">Middlename<b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="middle_name" id="middle_name" value="{{ old("middle_name") ?? $personInfo->middle_name }}" >
                        @error('middle_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label for="last_name" class="form-label">Lastname<b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="last_name" id="last_name" value="{{ old("last_name") ?? $personInfo->last_name }}" >
                        @error('last_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-4 col-sm-6">
                        <label for="gender" class="form-label">Gender<b class="text-danger">*</b></label>
                        <select class="form-control" name="gender" id="gender" required>
                            <option value="">- Gender -</option>
                            <option value="male" {{ (old('gender') == 'male') ? 'selected' : ($personInfo->gender == 'male' ? 'selected' : '') }}>Male</option>
                            <option value="female" {{ (old('gender') == 'female') ? 'selected' : ($personInfo->gender == 'female' ? 'selected' : '') }}>Female</option>
                        </select>
                        @error('gender')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label for="email" class="form-label">Email<b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="email" id="email" value="{{ old("email") ?? $personInfo->email }}" >
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label for="contact_no" class="form-label">Email<b class="text-danger">*</b></label>
                        <input type="text" class="form-control" name="contact_no" id="contact_no" value="{{ old("contact_no") ?? $personInfo->contact_no }}" >
                        @error('contact_no')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-3 col-sm-6">
                        <label for="barangay_id" class="form-label">Address: Barangay / Municipality<b class="text-danger">*</b></label>
                        <select class="form-control select2" name="barangay_id" id="barangay_id" style="width: 100%;" required>
                            <option value="">- Barangay / Municipality -</option>
                            @foreach($barangays as $barangay)
                            <?php $sel_brgy = (old('barangay_id')==$barangay->id ) ? 'selected' : ($personInfo->barangay_id == $barangay->id ? 'selected' : ''); ?>
                            <option value="{{$barangay->id}}" {{$sel_brgy}}>{{$barangay->barangay_name.' / '.$barangay->municipality_name.' / '.$barangay->province_name}}</option>
                            @endforeach
                        </select>
                        @error('barangay_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary btn-submit" type="submit"><i class="fas fa-save me-2"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script-extra')
    <script>
        $(function(){
            $('.select2').select2();
        })
    </script>
@endsection