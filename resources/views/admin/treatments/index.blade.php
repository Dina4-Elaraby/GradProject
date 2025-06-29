@extends('layouts.app')

@section('title', 'All Treatments')

@section('content')
    <h1>All Treatments</h1>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Actions</th> <!-- New -->
            </tr>
        </thead>
        <tbody>
            @foreach ($treatments as $treatment)
                <tr>
                    <td>{{ $treatment->id }}</td>
                    <td>{{ $treatment->description }}</td>
                    <td>
                        <a href="{{ route('admin.treatments.edit', $treatment->id) }}" class="btn btn-sm btn-primary">Edit</a>

                        <form action="{{ route('admin.treatments.destroy', $treatment->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure to delete this treatment?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.treatments.create') }}" class="btn btn-success mt-2">Add New Treatment</a>
@endsection
