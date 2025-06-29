@extends('layouts.app')

@section('title', 'Edit Treatment')

@section('content')
    <h2>Edit Treatment</h2>

    <form action="{{ route('admin.treatments.update', $treatment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ $treatment->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Update Treatment</button>
    </form>
@endsection
