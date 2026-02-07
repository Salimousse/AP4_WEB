<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model
{
    protected $fillable = ['conversation_id', 'admin_active', 'user_id'];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Optionnel: relation avec l'utilisateur
     * (seulement si migration exécutée)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
