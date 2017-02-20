<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller {

	/**
	 * First page, list all videos, link to upload video
	 */
	public function getIndex(Request $r, $page = null) {
		if ($page) {
			$page = abs(intval($page)-1);
			$skip = $page * 10;
		}
		else {
			$skip = 0;
		}
		$videos = Video::orderBy('id', 'desc')->skip($skip)->take(10)->get();
		$total_count = Video::count() / 10;
		return view('admin.video.index', array('videos' => $videos, 'total' => ceil($total_count), 'page' => $page));
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
	public function getEditVideo($id) {
		/** @var Video $video */
		$video = Video::find($id);
		//$video->transcodeNet();
		return view('admin.video.edit', ['video' => $video]);
	}

	public function postUploadVideo(Request $request) {
		$video = new Video();
		$video->original_file = $request->input('url');
		$video->title = $request->input('name');
		$video->to_encode = true;
		$video->uploader_id = 1;
		$formats = [];
		if ($request->input('formats')) {
			foreach ($request->input('formats') as $format) {
				$formats[$format] = ['name' => $format];
			}
		}
		$video->formats = $formats;
		$video->generateUniqueName();
		$video->generateThumbnail();
		$video->save();

		return response()->json(['id' => $video->id, 'url' => route( 'video_edit', ['id' => $video->id] )]);
	}

	public function postEditVideo() {

	}

	public function getSubmitVideo() {
		return view('admin.video.submit');
	}

	public function postSubmitVideo(Request $r) {
		$video = Video::create();
		$video->original_file = $r->input('link');
		$video->uploader_id = 1;
		$video->to_encode = true;
		$video->save();
		return redirect()->route('video_edit', ['id' => $video->id]);
	}

	public function postUploadDB(Request $r, $key) {

	}
}