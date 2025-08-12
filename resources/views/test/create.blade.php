@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('test.store') }}" method="POST">
                @csrf
                <input type="file" name="pdf_file" class="form-control" accept=".pdf" required>
    
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
@endsection