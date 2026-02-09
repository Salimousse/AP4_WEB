<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


/**
 * Class Client
 * 
 * @property int $IDPERS
 * @property string $MAILCLIENT
 * @property int $TELCLIENT
 * @property string $NOMPERS
 * @property string $PRENOMPERS
 * @property string $password
 * @property int $is_admin
 * @property string|null $remember_token
 * 
 * @property Collection|Animer[] $animers
 * @property Collection|Billet[] $billets
 * @property Collection|Presenter[] $presenters
 * @property Collection|Reservation[] $reservations
 *
 * @package App\Models
 */
class Client extends Authenticatable
{
	use HasFactory, Notifiable;
	protected $table = 'CLIENT';
	protected $primaryKey = 'IDPERS';
	public $timestamps = false;

	protected $casts = [
		'TELCLIENT' => 'int',
		'is_admin' => 'boolean'
	];

	protected $fillable = [
		'MAILCLIENT',
		'TELCLIENT',
		'NOMPERS',
		'PRENOMPERS',
		'password',
		'is_admin',
		'remember_token',
		'google_id',
		'google_email',
		'microsoft_id',
		'microsoft_email',
		'facebook_id',
		'facebook_email'
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	// Accesseurs pour compatibilité Laravel Auth
	public function getAuthIdentifierName()
	{
		return 'IDPERS';
	}

	public function getAuthPassword()
	{
		return $this->password;
	}

	// Spécifier le champ utilisé pour l'username (email)
	public function username()
	{
		return 'MAILCLIENT';
	}

	public function findForPassport($username)
	{
		return $this->where('MAILCLIENT', $username)->first();
	}

	// Attributs virtuels pour compatibilité
	public function getNameAttribute()
	{
		return trim($this->NOMPERS . ' ' . $this->PRENOMPERS);
	}

	public function getEmailAttribute()
	{
		return $this->MAILCLIENT;
	}

	public function setEmailAttribute($value)
	{
		$this->attributes['MAILCLIENT'] = $value;
	}

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
