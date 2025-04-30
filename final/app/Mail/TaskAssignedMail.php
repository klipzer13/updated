<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $assignee;

    public function __construct(Task $task, $assignee)
    {
        $this->task = $task;
        $this->assignee = $assignee;
    }

    public function build()
    {
        return $this->subject('New Task Assigned: ' . $this->task->title)
                    ->markdown('task-assigned');
    }
    
}