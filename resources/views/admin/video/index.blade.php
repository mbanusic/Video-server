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
                                    <img class="media-object" src="/thumbnails/{{ $video['unique_id'] }}.jpg" width="120">
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading"><a href="{{ route('video_edit', ['id' => $video['id']]) }}">{{ $video['title'] }}</a></h4>
                                    Embed link: http://video.adriaticmedia.hr/videos/{{ $video['unique_id'] }}
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
        <nav>
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="{{ route('paged_index', ['page' => ($page-1<0)?'0':($page)]) }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                @for ($i = 1; $i <= $total; $i++)
                    <li class="page-item {{ ($i-1==$page)?'active':'' }}"><a class="page-link" href="{{  route('paged_index', ['page' => $i]) }}">{{ $i }}</a></li>
                @endfor
                <li class="page-item">
                    <a class="page-link" href="{{ route('paged_index', ['page' => ($page-1>$total)?$total:($page+2)]) }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
        </div>
    </div>
@endsection
