<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArchiveVideo
 * @package App\Models
 *
 * @property int $id
 * @property string $jw_key
 * @property string $title
 * @property array $sizes
 * @property boolean $downloaded
 * @property array $downloaded_sizes
 */
class ArchiveVideo extends Model{
	protected $table = 'archive';

	protected $casts = [
		'id' => 'int',
		'jw_key' => 'string',
		'title' => 'string',
		'sizes' => 'array',
		'downloaded' => 'boolean',
		'downloaded_sizes' => 'array'
	];

	public $timestamps = false;

}