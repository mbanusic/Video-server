# Video server

An application to enable uploading of videos file, transcoding and serving them back. 

# Goal

We want to create functioning interface to replicate functionality of JWPlayer, FlowPlayer Driver and other video hosting sites. This will enable us to serve specific video formats tailored for our websites.

# Structure and logic

We will use [jQuery-File-Upload](https://github.com/blueimp/jQuery-File-Upload) for upload interface to upload directly to [openresty](http://openresty.org/en/). Openresty should handle upload to avoid PHP timeouts. Laravel will be used to keep track of uploaded media, and provide oEmbed and other endpoints to serve video.

# TODO 

 - docs
 
 
# Installation

 - Compile openresty with modules 
 - Install php, mysql
 - load Laravel