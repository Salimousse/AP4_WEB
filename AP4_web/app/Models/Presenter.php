<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Presenter
 * 
 * @property int $IDMANIF
 * @property int $IDPERS
 * @property int $IDPERS_ARTISTE
 * @property int $IDPERS_INTERVENANT
 * @property int $IDPERS_CLIENT
 * 
 * @property Artiste $artiste
 * @property Client $client
 * @property Conference $conference
 * @property Intervenant $intervenant
 *
 * @package App\Models
 */
class Presenter extends Model
{
	protected $table = 'presenter';
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

	public function client()
	{
		return $this->belongsTo(Client::class, 'IDPERS_CLIENT');
	}

	public function conference()
	{
		return $this->belongsTo(Conference::class, 'IDMANIF');
	}

	public function intervenant()
	{
		return $this->belongsTo(Intervenant::class, 'IDPERS_INTERVENANT');
	}
}
