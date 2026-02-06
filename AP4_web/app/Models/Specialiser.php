<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Specialiser
 * 
 * @property int $IDDOMAINE
 * @property int $IDPERS
 * 
 * @property Domaine $domaine
 * @property Intervenant $intervenant
 *
 * @package App\Models
 */
class Specialiser extends Model
{
	protected $table = 'SPECIALISER';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'IDDOMAINE' => 'int',
		'IDPERS' => 'int'
	];

	public function domaine()
	{
		return $this->belongsTo(Domaine::class, 'IDDOMAINE');
	}

	public function intervenant()
	{
		return $this->belongsTo(Intervenant::class, 'IDPERS');
	}
}
