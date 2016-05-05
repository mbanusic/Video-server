<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;

class VideoController extends Controller {

	/**
	 * First page, list all videos, link to upload video
	 */
	public function getIndex() {
		$videos = Video::all();
		return view('admin.video.index', array('videos' => $videos));
	}

	/**
	 * Upload video page
	 */
	public function getUploadVideo() {
		return view('admin.video.upload');
	}

	/**
	 * View video page, edit and transcode
	 */
	public function getEditVideo() {
		return view('admin.video.edit');
	}

	public function postUploadVideo() {
		
	}

	public function postEditVideo() {

	}
}