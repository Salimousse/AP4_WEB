<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Sponsoriser
 * 
 * @property int $IDSPONSORS
 * @property int $IDFESTIVAL
 * 
 * @property Festival $festival
 * @property Sponsor $sponsor
 *
 * @package App\Models
 */
class Sponsoriser extends Model
{
	protected $table = 'sponsoriser';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'IDSPONSORS' => 'int',
		'IDFESTIVAL' => 'int'
	];

	public function festival()
	{
		return $this->belongsTo(Festival::class, 'IDFESTIVAL');
	}

	public function sponsor()
	{
		return $this->belongsTo(Sponsor::class, 'IDSPONSORS');
	}
}
