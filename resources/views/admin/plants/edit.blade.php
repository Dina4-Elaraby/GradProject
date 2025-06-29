@extends('layouts.app')

@section('title', 'Edit Plant')

@section('content')
    <h1>Edit Plant</h1>

    <form action="{{ route('admin.plants.update', $plant->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="common_name" class="form-label">Common Name</label>
            <input type="text" name="common_name" id="common_name" value="{{ $plant->common_name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="scientific_name" class="form-label">Scientific Name</label>
            <input type="text" name="scientific_name" id="scientific_name" value="{{ $plant->scientific_name }}" class="form-control" required>
        </div>

       <div class="mb-3">
            <label for="plant_family" class="form-label">Family</label>
            <input type="text" name="plant_family" id="plant_family" value="{{ $plant->plant_family }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="care_instructions" class="form-label">Care Instructions</label>
            <textarea name="care_instructions" id="care_instructions" rows="4" class="form-control" required>{{ $plant->care_instructions }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Update Plant</button>
        <a href="{{ route('admin.plants.show') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection