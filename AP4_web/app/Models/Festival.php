<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Festival
 * 
 * @property int $IDFESTIVAL
 * @property string $THEMEFEST
 * @property Carbon $DATEDEBFEST
 * @property Carbon $DATEFINFEST
 * 
 * @property Collection|Manifestation[] $manifestations
 * @property Collection|Sponsoriser[] $sponsorisers
 *
 * @package App\Models
 */
class Festival extends Model
{
	protected $table = 'FESTIVAL';
	protected $primaryKey = 'IDFESTIVAL';
	public $timestamps = false;

	protected $casts = [
		'DATEDEBFEST' => 'datetime',
		'DATEFINFEST' => 'datetime'
	];

	protected $fillable = [
		'THEMEFEST',
		'DATEDEBFEST',
		'DATEFINFEST'
	];

	public function manifestations()
	{
		return $this->hasMany(Manifestation::class, 'IDFESTIVAL');
	}

	public function sponsorisers()
	{
		return $this->hasMany(Sponsoriser::class, 'IDFESTIVAL');
	}
}
