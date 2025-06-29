<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            display: flex;
            min-height: 100vh;
            margin: 0;
            background-color: #f5fdf6; /* soft plant green background */
        }

       .sidebar {
    width: 250px;
    background-color: #e6f4ea; 
    padding: 20px;
    color: #2e7d32; 
    min-height: 100vh;
    border-right: 1px solid #d0e6d5; 
}


        .sidebar h4 {
            font-weight: bold;
            color: #1b5e20;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: #2e7d32;
            display: block;
            margin: 12px 0;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #c8e6c9;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: #ffffff;
        }

        h1, h2, h3 {
            color: #0b1109;
        }

        .btn-primary {
            background-color: #66bb6a;
            border-color: #66bb6a;
        }

        .btn-primary:hover {
            background-color: #4caf50;
        }

        .table {
            background-color: #d9f5e5;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>🌱 Admin Panel</h4>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.users.show') }}">Users</a>
        <a href="{{ route('admin.plants.show') }}">Plants</a>
        <a href="{{ route('admin.diseases.index') }}">Diseases</a>
        <a href="{{ route('admin.treatments.index') }}">Treatments</a>
        {{-- <a href="{{ route('admin.questions.index') }}">Questions</a>
        <a href="{{ route('admin.answers.index') }}">Answers</a>
        <a href="{{ route('admin.devices.show') }}">Devices</a>
        <a href="{{ route('admin.measurements.show') }}">Measurements</a> --}}

    
    </div>
    <div class="content">
        @yield('content')
    </div>
</body>
</html>
