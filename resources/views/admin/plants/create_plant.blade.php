@extends('layouts.app')

@section('title', 'Add New Plant')

@section('content')
    <h2>Add New Plant</h2>

    <form action="{{ route('admin.plants.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Scientific Name</label>
            <input type="text" name="scientific_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Common Name</label>
            <input type="text" name="common_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Plant Family</label>
            <input type="text" name="plant_family" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Care Instructions</label>
            <textarea name="care_instructions" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success mt-2">Add Plant</button>
    </form>
@endsection
