@extends('layouts.app')

@section('title', 'Diseases List')

@section('content')
    <h2>All Diseases</h2>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Symptoms</th>
                <th>Factors</th>
                <th>Actions</th> <!-- New column -->
            </tr>
        </thead>
        <tbody>
            @foreach($diseases as $disease)
                <tr>
                    <td>{{ $disease->id }}</td>
                    <td>{{ $disease->name }}</td>
                    <td>
                        <ul>
                            @foreach($disease->symptoms as $symptom)
                                <li>{{ $symptom }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul>
                            @foreach($disease->factors as $factor)
                                <li>{{ $factor }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <a href="{{ route('admin.diseases.edit', $disease->id) }}" class="btn btn-sm btn-primary">Edit</a>

                        <form action="{{ route('admin.diseases.destroy', $disease->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure to delete {{$disease->name}}?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.diseases.create') }}" class="btn btn-primary mb-3">Add New Disease</a>
@endsection
