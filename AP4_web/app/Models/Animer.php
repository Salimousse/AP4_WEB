<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Animer
 * 
 * @property int $IDMANIF
 * @property int $IDPERS
 * @property int $IDPERS_ARTISTE
 * @property int $IDPERS_INTERVENANT
 * @property int $IDPERS_CLIENT
 * 
 * @property Artiste $artiste
 * @property Atelier $atelier
 * @property Client $client
 * @property Intervenant $intervenant
 *
 * @package App\Models
 */
class Animer extends Model
{
	protected $table = 'animer';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'IDMANIF' => 'int',
		'IDPERS' => 'int',
		'IDPERS_ARTISTE' => 'int',
		'IDPERS_INTERVENANT' => 'int',
		'IDPERS_CLIENT' => 'int'
	];

	public function artiste()
	{
		return $this->belongsTo(Artiste::class, 'IDPERS_ARTISTE');
	}

	public function atelier()
	{
		return $this->belongsTo(Atelier::class, 'IDMANIF');
	}

	public function client()
	{
		return $this->belongsTo(Client::class, 'IDPERS_CLIENT');
	}

	public function intervenant()
	{
		return $this->belongsTo(Intervenant::class, 'IDPERS_INTERVENANT');
	}
}
