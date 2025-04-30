<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Attachment;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        // Get task statistics for the current user
        $activeTasks = Task::whereHas('users', function($query) {
            $query->where('user_id', Auth::id())
                  ->whereNotIn('status_id', [4]); // Not completed (3 = completed)
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
                  ->where('status_id',[4] ); // 3 = Completed
        })
    
        // ->whereMonth('due_date', now()->month)
        ->count();
    
        // Calculate completion percentage
        $totalAssignedTasks = $activeTasks + $completedTasks;
        $completedPercentage = $totalAssignedTasks > 0 ? round(($completedTasks / $totalAssignedTasks) * 100) : 0;
        
        // Hours logged this week - This would need a separate model to track hours
        // For now, use a placeholder or estimate based on completed tasks
        $hoursLogged = $completedTasks * 2; // Assuming 2 hours per completed task
        
        // Get data needed for forms
        $priorities = Priority::all();
        $comments = Task::with('comments')->whereHas('users', function($query) {
            $query->where('user_id', Auth::id());
        })->get();
        $users = User::where('id', '!=', Auth::id())->get();
       
        
        return view('employee.dashboard', compact(
            'activeTasks',
            'overdueTasks',
            'completedTasks',
            'completedPercentage',
            'hoursLogged',
            'priorities',
            'users',
            'comments'
        ));
    }
}