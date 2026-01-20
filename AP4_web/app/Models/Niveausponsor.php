<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Niveausponsor
 * 
 * @property int $IDNIVSPONSORS
 * @property string $LIBELLENIVSPONSORS
 * @property int $NBPLACENIVSPONSORS
 * 
 * @property Collection|Sponsor[] $sponsors
 *
 * @package App\Models
 */
class Niveausponsor extends Model
{
	protected $table = 'niveausponsors';
	protected $primaryKey = 'IDNIVSPONSORS';
	public $timestamps = false;

	protected $casts = [
		'NBPLACENIVSPONSORS' => 'int'
	];

	protected $fillable = [
		'LIBELLENIVSPONSORS',
		'NBPLACENIVSPONSORS'
	];

	public function sponsors()
	{
		return $this->hasMany(Sponsor::class, 'IDNIVSPONSORS');
	}
}
