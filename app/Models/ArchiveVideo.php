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
 */
class ArchiveVideo extends Model{
	protected $table = 'archive';

	protected $casts = [
		'id' => 'int',
		'jw_key' => 'string',
		'title' => 'string',
		'sizes' => 'array'
	];

	public $timestamps = false;

}