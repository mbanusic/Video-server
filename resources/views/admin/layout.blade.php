<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ elixir('app.css') }}">
    @section('head')
        <title>Admin</title>
    @endsection
</head>
<body>
<nav class="navbar navbar-light bg-faded">

    <a class="navbar-brand" href="#">TG</a>
    <ul class="nav navbar-nav">
        <li class="nav-item {{ Request::is('admin_home')?'active':'' }}">
            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item {{ Request::is('video_index')?'active':'' }}">
            <a class="nav-link" href="#">Videos</a>
        </li>
        <li class="nav-item {{ Request::is('video_upload')?'active':'' }}">
            <a class="nav-link" href="#">Upload</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Help</a>
        </li>
    </ul>
</nav>
@yield('content')

@section('scripts')
    <script src="{{ elixir('jquery.js') }}"></script>
    <script src="{{ elixir('bootstrap.js') }}"></script>
@endsection
</body>
</html>