<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Authenticatable implements JWTSubject
{
    protected $table = "Account";

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['email', 'password', 'login', 'role'];
    protected $hidden = ['password'];
    public $timestamps = false;

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assignedTo');
    }

    public function assignedProjects()
    {
        return $this->belongsToMany(Project::class, 'ProjectAccount', 'accountId', 'projectId');
    }
}
