<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'owner_id'])]

class Workspace extends Model
{
    public function users() {
        return $this->belongsToMany(User::class, 'workspace_user')
                    ->withPivot('role')->withTimestamps();
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
