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
 * 
 * @property Billet $billet
 * @property Manifestation $manifestation
 *
 * @package App\Models
 */
class Avi extends Model
{
	protected $table = 'avis';
	protected $primaryKey = 'IDAVIS';
	public $timestamps = false;

	protected $casts = [
		'IDBILLET' => 'int',
		'IDMANIF' => 'int',
		'NOTEAVIS' => 'int'
	];

	protected $fillable = [
		'IDBILLET',
		'IDMANIF',
		'NOTEAVIS',
		'COMMENTAIREAVIS'
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
