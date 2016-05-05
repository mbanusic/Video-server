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
        './node_modules/jquery-ui/widget.js',
        './node_modules/blueimp-file-upload/js/jquery.iframe-transport.js',
        './node_modules/blueimp-file-upload/js/jquery.fileupload.js',
        './node_modules/blueimp-file-upload/js/jquery.fileupload-process.js',
        './node_modules/blueimp-file-upload/js/jquery.fileupload-video.js',
        './node_modules/blueimp-file-upload/js/jquery.fileupload-validate.js'
    ], 'public/js/upload.js');

    mix.copy('node_modules/bootstrap/dist/js/bootstrap.js', 'public/js/bootstrap.js');
    mix.copy('node_modules/jquery/dist/jquery.js', 'public/js/jquery.js');
});
