<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;

class TaskApiController extends Controller
{
    public function getTasks()
    {
        // Get tasks assigned to the current user
        $tasks = Task::with(['priority', 'users', 'creator', 'attachments'])
            ->whereHas('users', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();
        
        // Add user-specific status to each task
        foreach ($tasks as $task) {
            $userTask = $task->users->where('id', Auth::id())->first();
            if ($userTask && $userTask->pivot->status_id) {
                $status = Status::find($userTask->pivot->status_id);
                $task->user_status = $status ? $status->name : 'pending';
            } else {
                $task->user_status = 'pending';
            }
        }
        
        return response()->json($tasks);
    }
    
    // Method to fetch task statistics for the dashboard
    public function getTaskStats()
    {
        // Active tasks (assigned to current user, not completed)
        $activeTasks = Task::whereHas('users', function($query) {
            $query->where('user_id', Auth::id())
                  ->whereNotIn('status_id', [3]); // Not completed (3 = completed)
        })->count();
        
        // Overdue tasks
        $overdueTasks = Task::whereDate('due_date', '<', now())
            ->whereHas('users', function($query) {
                $query->where('user_id', Auth::id())
                      ->whereNotIn('status_id', [3]); // Not completed
            })->count();
        
        // Completed tasks (this month)
        $completedTasks = Task::whereHas('users', function($query) {
            $query->where('user_id', Auth::id())
                  ->where('status_id', 3); // 3 = Completed
        })
        ->whereMonth('due_date', now()->month)
        ->count();
        
        // Calculate completion percentage
        $totalAssignedTasks = $activeTasks + $completedTasks;
        $completedPercentage = $totalAssignedTasks > 0 ? round(($completedTasks / $totalAssignedTasks) * 100) : 0;
        
        // Hours logged this week - This would need a separate model to track hours
        // For now, use a placeholder or estimate based on completed tasks
        $hoursLogged = $completedTasks * 2; // Assuming 2 hours per completed task
        
        return response()->json([
            'activeTasks' => $activeTasks,
            'overdueTasks' => $overdueTasks,
            'completedTasks' => $completedTasks,
            'completedPercentage' => $completedPercentage,
            'hoursLogged' => $hoursLogged
        ]);
    }
}