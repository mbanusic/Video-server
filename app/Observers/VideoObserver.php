<?php
/**
 *
 * Filename: UserObserver.php
 *
 * User: mbanusic
 * Date: 01/03/2017
 * Time: 14:50
 *
 * Contact: http://mbanusic.com
 * License: MIT
 */

namespace App\Observers;

use App\Models\Video;
use Illuminate\Support\Facades\Storage;


class VideoObserver {
	public function deleting (Video $video) {
		//TODO: delete video files
		$files = array();
		foreach ($video->formats as $format) {
			$files[] = $format['path'];
		}
		Storage::delete($files);
	}
}