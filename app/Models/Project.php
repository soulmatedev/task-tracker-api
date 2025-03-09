<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{    use HasFactory;

    protected $table = 'Project';

    protected $fillable = [
        'name',
        'description',
        'createdBy',
        'createdAt',
    ];

    public $timestamps = false;

    public function creator()
    {
        return $this->belongsTo(Account::class, 'createdBy');
    }

    public function assignedAccounts()
    {
        return $this->belongsToMany(Account::class, 'ProjectAccount', 'projectId', 'accountId');
    }

    protected $casts = [
        'createdAt' => 'datetime',
    ];
}

