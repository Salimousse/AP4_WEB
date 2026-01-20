<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Lieux
 * 
 * @property int $IDLIEUX
 * @property string $NOMLIEUX
 * @property string $ADRESSELIEUX
 * @property int|null $CAPACITEMAXLIEUX
 * 
 * @property Collection|Session[] $sessions
 *
 * @package App\Models
 */
class Lieux extends Model
{
	protected $table = 'lieux';
	protected $primaryKey = 'IDLIEUX';
	public $timestamps = false;

	protected $casts = [
		'CAPACITEMAXLIEUX' => 'int'
	];

	protected $fillable = [
		'NOMLIEUX',
		'ADRESSELIEUX',
		'CAPACITEMAXLIEUX'
	];

	public function sessions()
	{
		return $this->hasMany(Session::class, 'IDLIEUX');
	}
}
