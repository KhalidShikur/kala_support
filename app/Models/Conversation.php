<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['workspace_id', 'customer_id', 'bot_id', 'status'])]

class Conversation extends Model
{
    public function workspace() {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function bot() {
        return $this->belongsTo(Bot::class, 'bot_id');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }
}
