@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
    <h2>Add New User</h2>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        {{-- Name --}}
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        {{-- Phone --}}
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>

        {{-- Address --}}
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
        </div>

        {{-- Birth Date --}}
        <div class="form-group">
            <label>Birth Date</label>
            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
        </div>

       {{-- Gender --}}
<div class="form-group">
    <label>Gender</label>
    <select name="gender" class="form-control">
        <option value="">Select Gender</option>
        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
    </select>
</div>
        {{-- Role --}}
        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="">User (Default)</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success mt-2">Add User</button>
    </form>
@endsection
