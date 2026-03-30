<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['workspace_id', 'bot_name', 'bot_token', 'bot_username'])]

class Bot extends Model
{
    public function workspace() {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }
}
