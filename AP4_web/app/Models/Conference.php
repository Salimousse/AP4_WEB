<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Conference
 * 
 * @property int $IDMANIF
 * @property bool $DEBATCONF
 * @property string $DESCCONFDEBAT
 * 
 * @property Manifestation $manifestation
 * @property Collection|Presenter[] $presenters
 *
 * @package App\Models
 */
class Conference extends Model
{
	protected $table = 'CONFERENCE';
	protected $primaryKey = 'IDMANIF';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'IDMANIF' => 'int',
		'DEBATCONF' => 'bool'
	];

	protected $fillable = [
		'DEBATCONF',
		'DESCCONFDEBAT'
	];

	public function manifestation()
	{
		return $this->belongsTo(Manifestation::class, 'IDMANIF');
	}

	public function presenters()
	{
		return $this->hasMany(Presenter::class, 'IDMANIF');
	}
}
