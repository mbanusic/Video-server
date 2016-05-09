<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ArchiveVideo;
use GuzzleHttp\Client;

class JW extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download files from JW';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $videos = ArchiveVideo::where('downloaded', false)->take(10)->get();
	    $client = new Client();
	    foreach ($videos as $video) {
		    $top = last($video->sizes['conversions']);
		    $sizes = [];
		    if (isset($top['link']['path'])) {
			    $path1 = 'http://video.telegram.hr' . $top['link']['path'];
			    $client->request( 'GET', $path1, [ 'sink' => '/var/www/originals/jw' . $top['link']['path'] ] );
			    $sizes[$top['template']['key']] = array(
				    'name' => $top['template']['format']['name'],
				    'link' => $path1,
				    'local' => '/var/www/originals/jw'.$top['link']['path']
			    );
		    }
		    $head = $video->sizes['conversions'][1];
		    if (isset($head['link']['path'])) {
			    $path2 = 'http://video.telegram.hr' . $head['link']['path'];
			    $client->request( 'GET', $path2, [ 'sink' => '/var/www/originals/jw' . $head['link']['path'] ] );
			    $sizes[$head['template']['key']] = array(
				    'name' => $head['template']['format']['name'],
				    'link' => $path2,
				    'local' => '/var/www/originals/jw'.$head['link']['path']
			    );
		    }
		    $video->downloaded = true;
		    $video->downloaded_sizes = $sizes;
		    $video->save();
	    }
	    $this->info('Completed');
    }
}
