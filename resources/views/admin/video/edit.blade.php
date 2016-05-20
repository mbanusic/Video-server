@extends('admin.layout')

@section('content')
    <div class="row">
        <div class="media">
            <div class="media-left">
                <img class="media-object" src="/thumbnails/{{ $video['unique_id'] }}.jpg" width="120">
            </div>
            <div class="media-body">
                <h4>{{ $video['title'] }}</h4>
                Embed link: http://video.adriaticmedia.hr/videos/{{ $video['unique_id'] }}
                @if( $video['to_encode'] )
                    <span class="btn btn-warning">Processing</span>
                @else
                    <span class="btn btn-success">Transcoded</span>
                @endif
            </div>
        </div>

    </div>
    @if( !$video['to_encode'] )
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <video class="video-js vjs-default-skin" controls poster="http://video.adriaticmedia.hr/thumbnails/{{ $video['unique_id'] }}.jpg">
                <source src="http://video.adriaticmedia.hr/videos/{{ $video['unique_id'] }}.webm" type="video/webm" />
                <source src="http://video.adriaticmedia.hr/videos/{{ $video['unique_id'] }}.mp4" type="video/mp4" />
            </video>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <table class="table">
                <thead>
                <tr>
                    <th>Format</th>
                    <th>Link</th>
                </tr>
                </thead>
                <tbody>
                @if ($video['formats'] && is_array($video['formats']))
                @foreach ($video['formats'] as $format)
                    <tr>
                        <td>{{ $format['name'] }}</td>
                        <td>{{ isset($format['url'])?$format['url']:'' }}</td>
                    </tr>
                @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script type="application/javascript" src="{{ elixir('js/videojs.js') }}"></script>
@endsection

@section('head')
    <link rel="stylesheet" href="{{ elixir('css/videojs.css') }}">
    <title>Edit video - {{$video['unique_id']}}</title>
@endsection
