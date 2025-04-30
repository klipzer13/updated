<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attachment;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'due_date', 'priority_id', 'created_by'];
    protected $casts = [
        'due_date' => 'datetime', // This will automatically cast due_date to a Carbon instance
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status_id') // if you need to access the pivot table data
            ->withTimestamps(); // if your pivot table has timestamps
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_user')
            ->withPivot('status_id')
            ->withTimestamps();
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }


    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user')
            ->withPivot('status_id')
            ->withTimestamps();
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');

    }
    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }





}