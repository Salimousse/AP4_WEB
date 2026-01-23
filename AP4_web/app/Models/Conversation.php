<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = ['conversation_id', 'admin_active'];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
