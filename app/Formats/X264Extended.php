<?php

namespace App\Formats;

use FFMpeg\Format\Video\X264;

class X264Extended extends X264 {

	public function getExtraParams() {
		return array("-profile:v", "baseline", "-f", "mp4");
	}
}