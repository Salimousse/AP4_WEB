<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Client
 * 
 * @property int $IDPERS
 * @property string $MAILCLIENT
 * @property int $TELCLIENT
 * @property string $NOMPERS
 * @property string $PRENOMPERS
 * 
 * @property Collection|Animer[] $animers
 * @property Collection|Billet[] $billets
 * @property Collection|Presenter[] $presenters
 * @property Collection|Reservation[] $reservations
 *
 * @package App\Models
 */
class Client extends Model
{
	protected $table = 'client';
	protected $primaryKey = 'IDPERS';
	public $timestamps = false;

	protected $casts = [
		'TELCLIENT' => 'int'
	];

	protected $fillable = [
		'MAILCLIENT',
		'TELCLIENT',
		'NOMPERS',
		'PRENOMPERS'
	];

	public function animers()
	{
		return $this->hasMany(Animer::class, 'IDPERS_CLIENT');
	}

	public function billets()
	{
		return $this->hasMany(Billet::class, 'IDPERS');
	}

	public function presenters()
	{
		return $this->hasMany(Presenter::class, 'IDPERS_CLIENT');
	}

	public function reservations()
	{
		return $this->hasMany(Reservation::class, 'IDPERS');
	}
}
