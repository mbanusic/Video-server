@extends('admin.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('video_upload') }}" class="btn btn-primary">Upload</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table">
                <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Video</th>
                </tr>
                </thead>
                <tbody>
                @foreach($videos as $video)
                    <tr>
                        <td><input type="checkbox" value="{{ $video['id'] }}"></td>
                        <td>
                            <div class="media">
                                <a href="{{ route('video_edit', ['id' => $video['id']]) }}" class="media-left">
                                    <img class="media-object" src="{{ $video['thumbnail'] }}">
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading">{{ $video['title'] }}</h4>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{ elixir('js/upload.js') }}" type="application/javascript"></script>
@endsection
