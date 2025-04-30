<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // Correct import for SoftDeletes
use App\Models\Task;  // Explicit import
use App\Models\Role;
use App\Models\Department;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone',
        'avatar',
        'role_id',
        'department_id',
        'email_verification_token'
    ];
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user')
            ->withPivot('status')
            ->withTimestamps();
    }
    public function taskStatus()
    {
        return $this->hasOne(Status::class, 'id', 'pivot.status_id');
    }
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getAvatarUrlAttribute()
    {
        return asset($this->avatar ?? 'profile/avatar/profile.png');
    }

    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class)
            ->using(Task_User::class)  // Only if you created the pivot model
            ->withPivot('status')
            ->withTimestamps();
    }


    // Optional: Add this for tasks created by the user
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id');
    }

    public function getRoleNameAttribute()
    {
        return $this->role?->name;
    }


}