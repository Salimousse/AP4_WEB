<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Domaine
 * 
 * @property int $IDDOMAINE
 * @property string $LIBELLEDOMAINE
 * 
 * @property Collection|EtreSpecialiser[] $etre_specialisers
 * @property Collection|Specialiser[] $specialisers
 *
 * @package App\Models
 */
class Domaine extends Model
{
	protected $table = 'domaine';
	protected $primaryKey = 'IDDOMAINE';
	public $timestamps = false;

	protected $fillable = [
		'LIBELLEDOMAINE'
	];

	public function etre_specialisers()
	{
		return $this->hasMany(EtreSpecialiser::class, 'IDDOMAINE');
	}

	public function specialisers()
	{
		return $this->hasMany(Specialiser::class, 'IDDOMAINE');
	}
}
