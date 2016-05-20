server {
    listen 80;
    server_name video.app;
    root "/home/vagrant/Development/web/video/public";

    index index.html index.htm index.php;

    charset utf-8;

    lua_code_cache off;

    location /upload {
        content_by_lua_file /home/vagrant/Development/web/video/nginx/lua_files/upload.lua;
    }

    location ~ ^/thumbnails/(?P<file>.*).jpg {

            root /var/www/thumbnails;
            try_files /$file.jpg 404;
    }

    location ~ ^/videos/(?P<file>.*) {
            root /var/www/transcoded;
            try_files /$file 404;
        }

    location ~ ^/originals/jw/videos/(?P<file>.*).mp4 {
    	root /var/www/originals/jw/videos;
    	try_files /$file-vuR58fOk.mp4 /$file-cNeUJNK9.mp4 404;
    }

    location / {
    	try_files $uri $uri/ /index.php?$is_args$args;
    }

    include php.conf;

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    client_max_body_size 10g;

    location ~ /\.ht {
        deny all;
    }

    error_log /dev/stdout warn;
}

