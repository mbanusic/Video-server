<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Video
 * @package App\Models
 *
 * @property int $id,
 * @property string $title
 * @property string $original_file
 * @property array $formats
 * @property int $uploader_id
 * @property boolean $to_encode
 */
class Video extends Model{
	protected $table = 'videos';

	protected $casts = [
		'id' => 'int',
		'title' => 'string',
		'original_file' => 'string',
		'formats' => 'array',
		'to_encode' => 'boolean'
	];

	public function uploader()
	{
		return $this->belongsTo('App\User');
	}

}