@extends('layouts.app')

@section('title', 'Edit Disease')

@section('content')
    <h2>Edit Disease</h2>

    <form action="{{ route('admin.diseases.update', $disease->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name">Disease Name</label>
            <input type="text" name="name" value="{{ $disease->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="symptoms">Symptoms (comma separated)</label>
            <input type="text" name="symptoms" value="{{ implode(',', $disease->symptoms) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="factors">Factors (comma separated)</label>
            <input type="text" name="factors" value="{{ implode(',', $disease->factors) }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
@endsection
