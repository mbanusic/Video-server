<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    @section('head')
        <title>Admin</title>
    @endsection
</head>
<body>
<nav class="navbar navbar-light bg-faded">

    <a class="navbar-brand" href="#">TG</a>
    <ul class="nav navbar-nav">
        <li class="nav-item {{ url()->current()==route('admin_home')?'active':'' }}">
            <a class="nav-link" href="{{ route('admin_home') }}">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item {{ url()->current()==route('video_index')?'active':'' }}">
            <a class="nav-link" href="{{ route('video_index') }}">Videos</a>
        </li>
        <li class="nav-item {{ url()->current()==route('video_upload')?'active':'' }}">
            <a class="nav-link" href="{{ route('video_upload') }}">Upload</a>
        </li>
        <li class="nav-item {{ url()->current()==route('help')?'active':'' }}">
            <a class="nav-link" href="{{ route('help') }}">Help</a>
        </li>
    </ul>
</nav>
<div class="container-fluid">
    @yield('content')
</div>

<footer class="text-muted">
    <div class="container">
        <p>Built by @mbanusic at TG</p>
    </div>
</footer>

@section('scripts')
    <script src="{{ elixir('js/jquery.js') }}"></script>
    <script src="{{ elixir('js/bootstrap.js') }}"></script>
@endsection
</body>
</html>