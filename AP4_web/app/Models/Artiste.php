<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Artiste
 * 
 * @property int $IDPERS
 * @property string|null $LIENWEBARTISTE
 * @property string|null $PAGEFACEBOOKARTISTE
 * @property string $NOMPERS
 * @property string $PRENOMPERS
 * 
 * @property Collection|Animer[] $animers
 * @property Collection|EtreSpecialiser[] $etre_specialisers
 * @property Collection|Presenter[] $presenters
 * @property Collection|Produire[] $produires
 *
 * @package App\Models
 */
class Artiste extends Model
{
	protected $table = 'ARTISTE';
	protected $primaryKey = 'IDPERS';
	public $timestamps = false;

	protected $fillable = [
		'LIENWEBARTISTE',
		'PAGEFACEBOOKARTISTE',
		'NOMPERS',
		'PRENOMPERS'
	];

	public function animers()
	{
		return $this->hasMany(Animer::class, 'IDPERS_ARTISTE');
	}

	public function etre_specialisers()
	{
		return $this->hasMany(EtreSpecialiser::class, 'IDPERS');
	}

	public function presenters()
	{
		return $this->hasMany(Presenter::class, 'IDPERS_ARTISTE');
	}

	public function produires()
	{
		return $this->hasMany(Produire::class, 'IDPERS');
	}
}
