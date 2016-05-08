<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArchiveVideo;
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

	public function jw() {
		$botr_api = new BotrAPI('mhJb8EHW', 'Qavj2DbQ8kIY0bSmcD1lDnme');
		$videos = $botr_api->call("/videos/list", array('result_offset' => 0, 'result_limit' => 10));
		$out = [];
		foreach ($videos['videos'] as $video) {
			$key = $video['key'];
			$title = $video['title'];
			$sizes = $botr_api->call("/videos/conversions/list", array('video_key' => $key));
			$v = new ArchiveVideo();
			$v->jw_key = $key;
			$v->title = $title;
			$v->sizes = $sizes;
			$v->save();
			$out[$v->id] = $key;
		}
		return response()->json($out);
	}

	private function get_signed_url($path,$secret,$timeout = false,$domain = false) {
		if($timeout) {
			$expires = time() + $timeout;
		} else {
			$expires = time() + 3600;
		}
		$signature = md5($path.':'.$expires.':'.$secret);
		if($domain) {
			$url = 'http://'.$domain.$path.'?exp='.$expires.'&sig='.$signature;
		} else {
			$url = 'http://content.bitsontherun.com/'.$path.'?exp='.$expires.'&sig='.$signature;
		}
		return $url;
	}
}

class BotrAPI {
	private $_version = '1.4';
	private $_url = 'http://api.bitsontherun.com/v1';
	private $_library;

	private $_key, $_secret;

	public function __construct($key, $secret) {
		$this->_key = $key;
		$this->_secret = $secret;

		// Determine which HTTP library to use:
		// check for cURL, else fall back to file_get_contents
		if (function_exists('curl_init')) {
			$this->_library = 'curl';
		} else {
			$this->_library = 'fopen';
		}
	}

	public function version() {
		return $this->_version;
	}

	// RFC 3986 complient rawurlencode()
	// Only required for phpversion() <= 5.2.7RC1
	// See http://www.php.net/manual/en/function.rawurlencode.php#86506
	private function _urlencode($input) {
		if (is_array($input)) {
			return array_map(array('_urlencode'), $input);
		} else if (is_scalar($input)) {
			return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($input)));
		} else {
			return '';
		}
	}

	// Sign API call arguments
	private function _sign($args) {
		ksort($args);
		$sbs = "";
		foreach ($args as $key => $value) {
			if ($sbs != "") {
				$sbs .= "&";
			}
			// Construct Signature Base String
			$sbs .= $this->_urlencode($key) . "=" . $this->_urlencode($value);
		}

		// Add shared secret to the Signature Base String and generate the signature
		$signature = sha1($sbs . $this->_secret);

		return $signature;
	}

	// Add required api_* arguments
	private function _args($args) {
		$args['api_nonce'] = str_pad(mt_rand(0, 99999999), 8, STR_PAD_LEFT);
		$args['api_timestamp'] = time();

		$args['api_key'] = $this->_key;

		if (!array_key_exists('api_format', $args)) {
			// Use the serialised PHP format,
			// otherwise use format specified in the call() args.
			$args['api_format'] = 'php';
		}

		// Add API kit version
		$args['api_kit'] = 'php-' . $this->_version;

		// Sign the array of arguments
		$args['api_signature'] = $this->_sign($args);

		return $args;
	}

	// Construct call URL
	public function call_url($call, $args=array()) {
		$url = $this->_url . $call . '?' . http_build_query($this->_args($args), "", "&");
		return $url;
	}

	// Make an API call
	public function call($call, $args=array()) {
		$url = $this->call_url($call, $args);

		$response = null;
		switch($this->_library) {
			case 'curl':
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_URL, $url);
				$response = curl_exec($curl);
				curl_close($curl);
				break;
			default:
				$response = file_get_contents($url);
		}

		$unserialized_response = @unserialize($response);

		return $unserialized_response ? $unserialized_response : $response;
	}

	// Upload a file
	public function upload($upload_link=array(), $file_path, $api_format="php") {
		$url = $upload_link['protocol'] . '://' . $upload_link['address'] . $upload_link['path'] .
		       "?key=" . $upload_link['query']['key'] . '&token=' . $upload_link['query']['token'] .
		       "&api_format=" . $api_format;

		// A new variable included with curl in PHP 5.5 - CURLOPT_SAFE_UPLOAD - prevents the
		// '@' modifier from working for security reasons (in PHP 5.6, the default value is true)
		// http://stackoverflow.com/a/25934129
		// http://php.net/manual/en/migration56.changed-functions.php
		// http://comments.gmane.org/gmane.comp.php.devel/87521
		if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50500) {
			$post_data = array("file"=>"@" . $file_path);
		} else {
			$post_data = array("file"=>new \CURLFile($file_path));
		}
		$response = null;
		switch($this->_library) {
			case 'curl':
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
				$response = curl_exec($curl);
				$err_no = curl_errno($curl);
				$err_msg = curl_error($curl);
				curl_close($curl);
				break;
			default:
				$response = "Error: No cURL library";
		}

		if ($err_no == 0) {
			return unserialize($response);
		} else {
			return "Error #" . $err_no . ": " . $err_msg;
		}
	}
}