<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Atelier
 * 
 * @property int $IDMANIF
 * @property Carbon $DATEHEUREFINATELIER
 * 
 * @property Manifestation $manifestation
 * @property Collection|Animer[] $animers
 *
 * @package App\Models
 */
class Atelier extends Model
{
	protected $table = 'atelier';
	protected $primaryKey = 'IDMANIF';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'IDMANIF' => 'int',
		'DATEHEUREFINATELIER' => 'datetime'
	];

	protected $fillable = [
		'DATEHEUREFINATELIER'
	];

	public function manifestation()
	{
		return $this->belongsTo(Manifestation::class, 'IDMANIF');
	}

	public function animers()
	{
		return $this->hasMany(Animer::class, 'IDMANIF');
	}
}
