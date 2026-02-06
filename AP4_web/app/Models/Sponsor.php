<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sponsor
 * 
 * @property int $IDSPONSORS
 * @property int $IDNIVSPONSORS
 * @property string $NOMSPONSORS
 * 
 * @property Niveausponsor $niveausponsor
 * @property Collection|Billet[] $billets
 * @property Collection|Sponsoriser[] $sponsorisers
 *
 * @package App\Models
 */
class Sponsor extends Model
{
	protected $table = 'SPONSORS';
	protected $primaryKey = 'IDSPONSORS';
	public $timestamps = false;

	protected $casts = [
		'IDNIVSPONSORS' => 'int'
	];

	protected $fillable = [
		'IDNIVSPONSORS',
		'NOMSPONSORS'
	];

	public function niveausponsor()
	{
		return $this->belongsTo(Niveausponsor::class, 'IDNIVSPONSORS');
	}

	public function billets()
	{
		return $this->hasMany(Billet::class, 'IDSPONSORS');
	}

	public function sponsorisers()
	{
		return $this->hasMany(Sponsoriser::class, 'IDSPONSORS');
	}
}
