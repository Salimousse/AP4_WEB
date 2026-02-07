<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Avi
 * 
 * @property int $IDAVIS
 * @property int $IDBILLET
 * @property int $IDMANIF
 * @property int $NOTEAVIS
 * @property string|null $COMMENTAIREAVIS
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * 
 * @property Billet $billet
 * @property Manifestation $manifestation
 *
 * @package App\Models
 */
class Avi extends Model
{
	protected $table = 'AVIS';
	protected $primaryKey = 'IDAVIS';
	public $timestamps = true;

	protected $casts = [
		'IDBILLET' => 'int',
		'IDMANIF' => 'int',
		'NOTEAVIS' => 'int'
	];

	protected $fillable = [
		'IDBILLET',
		'IDMANIF',
		'NOTEAVIS',
		'COMMENTAIREAVIS',
		'created_at',
		'updated_at'
	];

	public function billet()
	{
		return $this->belongsTo(Billet::class, 'IDBILLET');
	}

	public function manifestation()
	{
		return $this->belongsTo(Manifestation::class, 'IDMANIF');
	}
}
