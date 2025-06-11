<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_completed',
        'project_id',
        'assigned_to',
    ];

    public function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function assignedUser() {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function user() {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
