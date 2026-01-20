<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EtreSpecialiser
 * 
 * @property int $IDDOMAINE
 * @property int $IDPERS
 * 
 * @property Artiste $artiste
 * @property Domaine $domaine
 *
 * @package App\Models
 */
class EtreSpecialiser extends Model
{
	protected $table = 'etre_specialiser';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'IDDOMAINE' => 'int',
		'IDPERS' => 'int'
	];

	public function artiste()
	{
		return $this->belongsTo(Artiste::class, 'IDPERS');
	}

	public function domaine()
	{
		return $this->belongsTo(Domaine::class, 'IDDOMAINE');
	}
}
