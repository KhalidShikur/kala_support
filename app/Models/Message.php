<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['conversation_id', 'message', 'sender_type'])]

class Message extends Model
{
    public function conversation() {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }
}
