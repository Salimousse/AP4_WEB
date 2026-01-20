<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Concert
 * 
 * @property int $IDMANIF
 * @property Carbon $DATEHEUREFINCONCERT
 * 
 * @property Manifestation $manifestation
 * @property Collection|Produire[] $produires
 *
 * @package App\Models
 */
class Concert extends Model
{
	protected $table = 'concert';
	protected $primaryKey = 'IDMANIF';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'IDMANIF' => 'int',
		'DATEHEUREFINCONCERT' => 'datetime'
	];

	protected $fillable = [
		'DATEHEUREFINCONCERT'
	];

	public function manifestation()
	{
		return $this->belongsTo(Manifestation::class, 'IDMANIF');
	}

	public function produires()
	{
		return $this->hasMany(Produire::class, 'IDMANIF');
	}
}
