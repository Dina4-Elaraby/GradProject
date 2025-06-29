@extends('layouts.app')

@section('title', 'All Plants')

@section('content')
    <h1>All Plants</h1>

   

    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Common Name</th>
                <th>Scientific Name</th>
                <th>Family</th>
                <th>Care Instructions</th>
                <th>Actions</th> {{-- Edit/Delete --}}
            </tr>
        </thead>
        <tbody>
            @foreach($plants as $plant)
                <tr>
                    <td>{{ $plant->id }}</td>
                    <td>{{ $plant->common_name }}</td>
                    <td>{{ $plant->scientific_name }}</td>
                    <td>{{ $plant->plant_family }}</td>
                    <td>{{ $plant->care_instructions }}</td>
                    <td>
                        {{-- Edit --}}
                        <a href="{{ route('admin.plants.edit', $plant->id) }}" class="btn btn-sm btn-primary">Edit</a>

                        {{-- Delete --}}
                        <form action="{{ route('admin.plants.destroy', $plant->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure to delete {{$plant->common_name}} plant?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.plants.create') }}" class="btn btn-primary mb-3">Add New Plant</a>
@endsection
