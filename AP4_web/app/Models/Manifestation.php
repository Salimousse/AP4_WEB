<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Manifestation
 * 
 * @property int $IDMANIF
 * @property int $IDFESTIVAL
 * @property string $NOMMANIF
 * @property string $AFFICHEMANIF
 * @property string $RESUMEMANIF
 * @property int $NBMAXPARTICIPANTMANIF
 * @property float|null $PRIXMANIF
 * 
 * @property Festival $festival
 * @property Atelier|null $atelier
 * @property Collection|Avi[] $avis
 * @property Collection|Billet[] $billets
 * @property Concert|null $concert
 * @property Conference|null $conference
 * @property Collection|Reservation[] $reservations
 * @property Collection|Session[] $sessions
 *
 * @package App\Models
 */
class Manifestation extends Model
{
	protected $table = 'MANIFESTATIONS';
	protected $primaryKey = 'IDMANIF';
	public $timestamps = false;

	protected $casts = [
		'IDFESTIVAL' => 'int',
		'NBMAXPARTICIPANTMANIF' => 'int',
		'PRIXMANIF' => 'float'
	];

	protected $fillable = [
		'IDFESTIVAL',
		'NOMMANIF',
		'AFFICHEMANIF',
		'RESUMEMANIF',
		'NBMAXPARTICIPANTMANIF',
		'PRIXMANIF'
	];

	public function festival()
	{
		return $this->belongsTo(Festival::class, 'IDFESTIVAL');
	}

	public function atelier()
	{
		return $this->hasOne(Atelier::class, 'IDMANIF');
	}

	public function avis()
	{
		return $this->hasMany(Avi::class, 'IDMANIF');
	}

	public function billets()
	{
		return $this->hasMany(Billet::class, 'IDMANIF');
	}

	public function concert()
	{
		return $this->hasOne(Concert::class, 'IDMANIF');
	}

	public function conference()
	{
		return $this->hasOne(Conference::class, 'IDMANIF');
	}

	public function reservations()
	{
		return $this->hasMany(Reservation::class, 'IDMANIF');
	}

	public function sessions()
	{
		return $this->hasMany(Session::class, 'IDMANIF');
	}
}
