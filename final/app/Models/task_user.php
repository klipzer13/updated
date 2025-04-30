<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Task_User extends Pivot
{
    protected $table = 'task_user';
    
    // Enable these if you need timestamps in your pivot table
    public $timestamps = true;
    
    // Fields that are mass assignable
    protected $fillable = [
        'status_id',
        // Add any other pivot fields here
    ];
    
    // Relationship to Status
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    
    // Relationship to Task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    
    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}