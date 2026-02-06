<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Billet
 * 
 * @property int $IDBILLET
 * @property int $IDSPONSORS
 * @property int $IDRESERVATION
 * @property int|null $IDTYPEPAIEMENT
 * @property int $IDMANIF
 * @property int $IDPERS
 * @property string|null $QRCODEBILLET
 * @property bool|null $INVITEBILLET
 * 
 * @property Client $client
 * @property Manifestation $manifestation
 * @property Reservation $reservation
 * @property Sponsor $sponsor
 * @property Typepaiement|null $typepaiement
 * @property Collection|Avi[] $avis
 *
 * @package App\Models
 */
class Billet extends Model
{
	protected $table = 'BILLET';
	protected $primaryKey = 'IDBILLET';
	public $timestamps = false;

	protected $casts = [
		'IDSPONSORS' => 'int',
		'IDRESERVATION' => 'int',
		'IDTYPEPAIEMENT' => 'int',
		'IDMANIF' => 'int',
		'IDPERS' => 'int',
		'INVITEBILLET' => 'bool'
	];

	protected $fillable = [
		'IDSPONSORS',
		'IDRESERVATION',
		'IDTYPEPAIEMENT',
		'IDMANIF',
		'IDPERS',
		'QRCODEBILLET',
		'INVITEBILLET'
	];

	public function client()
	{
		return $this->belongsTo(Client::class, 'IDPERS');
	}

	public function manifestation()
	{
		return $this->belongsTo(Manifestation::class, 'IDMANIF');
	}

	public function reservation()
	{
		return $this->belongsTo(Reservation::class, 'IDRESERVATION');
	}

	public function sponsor()
	{
		return $this->belongsTo(Sponsor::class, 'IDSPONSORS');
	}

	public function typepaiement()
	{
		return $this->belongsTo(Typepaiement::class, 'IDTYPEPAIEMENT');
	}

	public function avis()
	{
		return $this->hasMany(Avi::class, 'IDBILLET');
	}
}
