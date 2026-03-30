<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['workspace_id', 'message', 'sender_type'])]

class Message extends Model
{
    public function workspace() {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }
}
