@extends('layouts.app')

@section('title', 'Add Treatment')

@section('content')
    <h2>Add New Treatment</h2>

    <form method="POST" action="{{ route('admin.treatments.store') }}">
        @csrf
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-success mt-2">Save</button>
    </form>
@endsection
