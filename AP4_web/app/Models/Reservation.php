<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reservation
 * 
 * @property int $IDRESERVATION
 * @property int $IDMANIF
 * @property int $IDPERS
 * @property Carbon $DATEHEURERESERVATION
 * @property int $NBPERSRESERVATION
 * 
 * @property Client $client
 * @property Manifestation $manifestation
 * @property Billet|null $billet
 *
 * @package App\Models
 */
class Reservation extends Model
{
	protected $table = 'reservation';
	protected $primaryKey = 'IDRESERVATION';
	public $timestamps = false;

	protected $casts = [
		'IDMANIF' => 'int',
		'IDPERS' => 'int',
		'DATEHEURERESERVATION' => 'datetime',
		'NBPERSRESERVATION' => 'int'
	];

	protected $fillable = [
		'IDMANIF',
		'IDPERS',
		'DATEHEURERESERVATION',
		'NBPERSRESERVATION'
	];

	public function client()
	{
		return $this->belongsTo(Client::class, 'IDPERS');
	}

	public function manifestation()
	{
		return $this->belongsTo(Manifestation::class, 'IDMANIF');
	}

	public function billet()
	{
		return $this->hasOne(Billet::class, 'IDRESERVATION');
	}
}
