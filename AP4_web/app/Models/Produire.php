<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Produire
 * 
 * @property int $IDMANIF
 * @property int $IDPERS
 * 
 * @property Artiste $artiste
 * @property Concert $concert
 *
 * @package App\Models
 */
class Produire extends Model
{
	protected $table = 'produire';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'IDMANIF' => 'int',
		'IDPERS' => 'int'
	];

	public function artiste()
	{
		return $this->belongsTo(Artiste::class, 'IDPERS');
	}

	public function concert()
	{
		return $this->belongsTo(Concert::class, 'IDMANIF');
	}
}
