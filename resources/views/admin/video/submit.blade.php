@extends('admin.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form class="form-inline" action="{{ route('video_submit') }}">
                <fieldset class="form-group">
                    <label for="video_link">Video link</label>
                    <input type="url" name="link" id="video_link" class="form-control" size="100">
                </fieldset>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection

