@extends('admin.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>Add files...</span>
                <!-- The file input field used as target for the file upload widget -->
                <input id="fileupload" type="file" name="files[]" multiple>
            </span>
            <br>
            <br>
            <div class="form-group">
                <label class="checkbox-inline"><input class="formats" type="checkbox" name="formats[]" value="net_mp4" id="net_mp4" checked> In article</label>
                <label class="checkbox-inline"><input class="formats" type="checkbox" name="formats[]" value="bg_mp4" id="bg_mp4" > Background</label>
                <label class="checkbox-inline"><input class="formats" type="checkbox" name="formats[]" value="mad_mp4" id="mad_mp4" > Mobile Ad</label>
            </div>
            <!-- The global progress bar -->
            <progress id="progress" class="progress" value="0" max="100">
            </progress>
            <!-- The container for the uploaded files -->
            <div id="files" class="files"></div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{ elixir('js/upload.js') }}" type="application/javascript"></script>
    <script>
        /*jslint unparam: true, regexp: true */
        /*global window, $ */
        $(function () {
            'use strict';
            // Change this to the location of your server-side upload handler:
            var url = '/upload',
                finish = '/admin/video/upload',
                    uploadButton = $('<button/>')
                            .addClass('btn btn-primary')
                            .prop('disabled', true)
                            .text('Processing...')
                            .on('click', function () {
                                var $this = $(this),
                                        data = $this.data();
                                $this
                                        .off('click')
                                        .text('Abort')
                                        .on('click', function () {
                                            $this.remove();
                                            data.abort();
                                        });
                                data.submit().always(function () {
                                    $this.remove();
                                });
                            });
            $('#fileupload').fileupload({
                url: url,
                dataType: 'json',
                autoUpload: false,
                limitMultiFileUploads: 1,
                acceptFileTypes: /(\.|\/)(mkv|mov|avi|mp4|3gp|mpe?g|m4v|flv|mp2|aaf|wmv|asf||dv||gifv|h264|hdv)$/i,
            })
                    .on('fileuploadadd', function (e, data) {
                        data.context = $('<div/>').appendTo('#files');
                        $.each(data.files, function (index, file) {
                            var node = $('<p/>')
                                    .append($('<span/>').text(file.name));
                            if (!index) {
                                node
                                        .append('<br>')
                                        .append(uploadButton.clone(true).data(data));
                            }
                            node.appendTo(data.context);
                        });
                        $('#progress').addClass('progress-striped').addClass('progress-animated');
                    })
                    .on('fileuploadprocessalways', function (e, data) {
                        var index = data.index,
                                file = data.files[index],
                                node = $(data.context.children()[index]);
                        if (file.preview) {
                            node
                                    .prepend('<br>')
                                    .prepend(file.preview);
                        }
                        if (file.error) {
                            node
                                    .append('<br>')
                                    .append($('<span class="text-danger"/>').text(file.error));
                        }
                        if (index + 1 === data.files.length) {
                            data.context.find('button')
                                    .text('Upload')
                                    .prop('disabled', !!data.files.error);
                        }
                    })
                    .on('fileuploadprogressall', function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#progress').val(progress);
                    })
                    .on('fileuploaddone', function (e, data) {
                        $.each(data.result.files, function (index, file) {
                            if (file.url) {
                                var link = $('<a>')
                                        .attr('target', '_blank')
                                        .prop('href', file.url);
                                $(data.context.children()[index])
                                        .wrap(link);
                                file['_token'] = "{{ csrf_token() }}";
                                file['formats'] = [];
                                jQuery('.formats').each(function(index, elem) {
                                    if(jQuery(this).is(':checked')) {
                                        file['formats'].push(jQuery(this).val());
                                    }
                                });
                                $.post(finish, file, function (response) {
                                    window.location.href= response.url;
                                });
                            } else if (file.error) {
                                var error = $('<span class="text-danger"/>').text(file.error);
                                $(data.context.children()[index])
                                        .append('<br>')
                                        .append(error);
                            }
                        });
                        $('#progress').removeClass('progress-animated').removeClass('progress-striped');
                    })
                    .on('fileuploadfail', function (e, data) {
                        $.each(data.files, function (index) {
                            var error = $('<span class="text-danger"/>').text('File upload failed.');
                            $(data.context.children()[index])
                                    .append('<br>')
                                    .append(error);
                        });
                    })
                    .prop('disabled', !$.support.fileInput)
                    .parent().addClass($.support.fileInput ? undefined : 'disabled');


        });
    </script>
@endsection
