@extends('layouts.app')

@section('title', 'Add New Disease')

@section('content')
    <h2>Add New Disease</h2>

    <form action="{{ route('admin.diseases.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Symptoms (Comma Separated)</label>
            <input type="text" name="symptoms[]" class="form-control" placeholder="e.g. yellow leaves, dry spots" required>
        </div>

        <div class="form-group">
            <label>Factors (Comma Separated)</label>
            <input type="text" name="factors[]" class="form-control" placeholder="e.g. bacteria, humidity" required>
        </div>

        

        <button type="submit" class="btn btn-success mt-2">Save</button>
    </form>
@endsection
