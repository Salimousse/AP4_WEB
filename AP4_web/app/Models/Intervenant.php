<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Intervenant
 * 
 * @property int $IDPERS
 * @property bool|null $ESTSPECIALISE
 * @property string $NOMPERS
 * @property string $PRENOMPERS
 * 
 * @property Collection|Animer[] $animers
 * @property Collection|Presenter[] $presenters
 * @property Collection|Specialiser[] $specialisers
 *
 * @package App\Models
 */
class Intervenant extends Model
{
	protected $table = 'INTERVENANT';
	protected $primaryKey = 'IDPERS';
	public $timestamps = false;

	protected $casts = [
		'ESTSPECIALISE' => 'bool'
	];

	protected $fillable = [
		'ESTSPECIALISE',
		'NOMPERS',
		'PRENOMPERS'
	];

	public function animers()
	{
		return $this->hasMany(Animer::class, 'IDPERS_INTERVENANT');
	}

	public function presenters()
	{
		return $this->hasMany(Presenter::class, 'IDPERS_INTERVENANT');
	}

	public function specialisers()
	{
		return $this->hasMany(Specialiser::class, 'IDPERS');
	}
}
