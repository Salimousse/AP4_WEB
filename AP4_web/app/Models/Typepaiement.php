<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Typepaiement
 * 
 * @property int $IDTYPEPAIEMENT
 * @property string $LIBTYPEPAIEMENT
 * 
 * @property Collection|Billet[] $billets
 *
 * @package App\Models
 */
class Typepaiement extends Model
{
	protected $table = 'typepaiement';
	protected $primaryKey = 'IDTYPEPAIEMENT';
	public $timestamps = false;

	protected $fillable = [
		'LIBTYPEPAIEMENT'
	];

	public function billets()
	{
		return $this->hasMany(Billet::class, 'IDTYPEPAIEMENT');
	}
}
