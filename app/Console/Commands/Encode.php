<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;

class Encode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encode videos';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$videos = Video::where('to_encode', true)->get();
	    /** @var Video $video */
	    foreach ($videos as $video) {
		    if (isset($video->formats['net_mp4']) && $video->formats['net_mp4']['name'] == 'net_mp4') {
			    $video->transcodeNet();
		    }
		    if (isset($video->formats['bg_mp4']) && $video->formats['bg_mp4']['name'] == 'bg_mp4') {
			    $video->transcodeBackground();
		    }
		    if (isset($video->formats['mad_mp4']) && $video->formats['mad_mp4']['name'] == 'mad_mp4') {
			    $video->transcodeMobileAd();
		    }
	    }
    }
}
