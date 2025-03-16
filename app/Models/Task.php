<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'Task';

    protected $fillable = [
        'title', 'description', 'projectId', 'createdBy', 'assignedTo', 'dueDate', 'status',
    ];


    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo(Project::class, 'projectId');
    }

    public function assignedToAccount()
    {
        return $this->belongsTo(Account::class, 'assignedTo');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status');
    }
}
