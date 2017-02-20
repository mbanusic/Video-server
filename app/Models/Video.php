<?php

namespace App\Models;

use App\Formats\X264Extended;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Driver\FastStartDriver;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Model;
use FFMpeg\Filters\Video\ResizeFilter;

/**
 * Class Video
 * @package App\Models
 *
 * @property int $id,
 * @property string $title
 * @property string $unique_id
 * @property string $original_file
 * @property string $thumbnail
 * @property array $formats
 * @property int $uploader_id
 * @property boolean $to_encode
 */
class Video extends Model{
	protected $table = 'videos';

	protected $casts = [
		'id' => 'int',
		'title' => 'string',
		'unique_id' => 'string',
		'original_file' => 'string',
		'formats' => 'array',
		'to_encode' => 'boolean'
	];

	public function uploader()
	{
		return $this->belongsTo('App\User');
	}

	public function generateUniqueName() {
		while (!$this->unique_id) {
			$token = $this->getToken( 8 );
			$existing = Video::where('unique_id', $token)->first();
			if (!$existing) {
				$this->unique_id = $token;
			}
		}
	}

	private function crypto_rand_secure($min, $max)
	{
		$range = $max - $min;
		if ($range < 1) return $min; // not so random...
		$log = ceil(log($range, 2));
		$bytes = (int) ($log / 8) + 1; // length in bytes
		$bits = (int) $log + 1; // length in bits
		$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd >= $range);
		return $min + $rnd;
	}

	public function getToken($length)
	{
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		$max = strlen($codeAlphabet) - 1;
		for ($i=0; $i < $length; $i++) {
			$token .= $codeAlphabet[$this->crypto_rand_secure(0, $max)];
		}
		return $token;
	}

	public function generateThumbnail() {
		$ffmpeg = FFMpeg::create( [
			'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
		]);
		$video = $ffmpeg->open( $this->original_file );
		$video->frame(TimeCode::fromSeconds(1))->save( '/var/www/thumbnails/' . $this->unique_id . '.jpg');
	}

	public function getVideoData() {
		$probe = FFProbe::create([
			'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
			'ffprobe.binaries' => '/usr/bin/ffprobe',
		]);
		$probe->streams( $this->original_file )->videos()->first();
		$this->formats['original'] = array(
			'name' => 'original',
			'path' => $this->original_file,
			'url' => $this->original_file,
			'width' => $probe->get('width'),
			'height' => $probe->get('height')
		);

	}

	public function transcodeBackground() {
		$ffmpeg = FFMpeg::create([
			'ffmpeg.binaries'  => '/home/marko/bin/ffmpeg',
			'ffprobe.binaries' => '/home/marko/bin/ffprobe',
			'ffmpeg.threads'   => 5,
		]);
		$video = $ffmpeg->open( $this->original_file );
		$video->filters()
		      ->resize( new Dimension( 1024, 768 ), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, true );
		$format = new X264Extended('libfdk_aac');
		$format->setKiloBitrate( 500 )
		       ->setAudioChannels( 1 )
		       ->setAudioKiloBitrate( 128 );
		$video->save( $format, '/var/www/transcoded/' . $this->unique_id . '-bg.mp4' );
		$formats = $this->formats;
		$formats['bg_mp4'] = array(
			'name' => 'BG-mp4',
			'path' => '/var/www/transcoded/' . $this->unique_id . '-bg.mp4',
			'url' => 'http://video.adriaticmedia.hr/videos/'.$this->unique_id.'-bg.mp4');
		$this->formats = $formats;
		$this->to_encode = false;
		$this->save();
	}

	public function transcodeNet() {
		//670x400
		$ffmpeg = FFMpeg::create([
			'ffmpeg.binaries'  => '/home/marko/bin/ffmpeg',
			'ffprobe.binaries' => '/home/marko/bin/ffprobe',
			'ffmpeg.threads'   => 6,
			'timeout'          => 3600,
		]);
		$video = $ffmpeg->open( $this->original_file );
		$video->filters()
			->resize( new Dimension( 660, 400 ), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, true );
		$format = new X264Extended('libfdk_aac');
		$format->setKiloBitrate( 700 )
			->setAudioChannels( 1 )
			->setAudioKiloBitrate( 128 );
		$video->save( $format, '/var/www/transcoded/' . $this->unique_id . '-net.mp4' );
		$formats = $this->formats;
		$formats['net_mp4'] = array(
			'name' => 'Net-mp4',
			'path' => '/var/www/transcoded/' . $this->unique_id . '-net.mp4',
			'url' => 'http://video.adriaticmedia.hr/videos/'.$this->unique_id.'-net.mp4',
		);
		$this->formats = $formats;
		$this->to_encode = false;
		$this->save();
	}

	public function transcodeMobileAd() {
		$ffmpeg = FFMpeg::create([
			'ffmpeg.binaries'  => '/home/marko/bin/ffmpeg',
			'ffprobe.binaries' => '/home/marko/bin/ffprobe',
			'ffmpeg.threads'   => 5,
		]);
		$video = $ffmpeg->open( $this->original_file );
		$video->filters()
		      ->resize( new Dimension( 320, 240 ), ResizeFilter::RESIZEMODE_SCALE_HEIGHT, true );
		$format = new X264Extended('libfdk_aac');
		$format->setKiloBitrate( 700 )
		       ->setAudioChannels( 1 )
		       ->setAudioKiloBitrate( 128 );
		$video->save( $format, '/var/www/transcoded/' . $this->unique_id . '-mad.mp4' );
		$formats = $this->formats;
		$formats['mad_mp4'] = array(
			'name' => 'MAD-mp4',
			'path' => '/var/www/transcoded/' . $this->unique_id . '-mad.mp4',
			'url' => 'http://video.adriaticmedia.hr/videos/'.$this->unique_id.'-mad.mp4');
		$this->formats = $formats;
		$this->to_encode = false;
		$this->save();
	}

	public function downloadYoutube() {

	}


}