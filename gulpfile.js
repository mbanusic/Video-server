var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');

    mix.scripts([
        './node_modules/blueimp-load-image/js/load-image.all.min.js',
        './node_modules/blueimp-file-upload/js/vendor/jquery.ui.widget.js',
        './node_modules/blueimp-file-upload/js/jquery.iframe-transport.js',
        './node_modules/blueimp-file-upload/js/jquery.fileupload.js',
        './node_modules/blueimp-file-upload/js/jquery.fileupload-process.js',
        './node_modules/blueimp-file-upload/js/jquery.fileupload-video.js',
        './node_modules/blueimp-file-upload/js/jquery.fileupload-validate.js'
    ], 'public/js/upload.js');

    mix.scripts([
        './node_modules/tether/dist/js/tether.js',
        './node_modules/bootstrap/dist/js/bootstrap.js'
    ], 'public/js/bootstrap.js');

    mix.copy('node_modules/jquery/dist/jquery.js', 'public/js/jquery.js');
    mix.copy('node_modules/video.js/dist/video-js.min.css', 'public/css/videojs.css');
    mix.copy('node_modules/video.js/dist/video.min.js', 'public/js/videojs.js');

    mix.version(['css/app.css', 'public/js/upload.js', 'public/js/bootstrap.js', 'public/js/jquery.js', 'public/css/videojs.css', 'public/js/videojs.js'])
});
