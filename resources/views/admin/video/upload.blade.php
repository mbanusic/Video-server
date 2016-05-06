@extends('admin.layout')

@section('content')
    Upload
@endsection

@section('scripts')
    @parent
    <script src="{{ elixir('js/upload.js') }}" type="application/javascript"></script>
@endsection
